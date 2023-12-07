<?php

namespace App\Console;

use App\Models\Dose;
use App\Models\Refill;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Aws\IotDataPlane\IotDataPlaneClient;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();
        $schedule->call(function () {
                
            $iotDataPlaneClient = new IotDataPlaneClient([
                'region'  => env('AWS_DEFAULT_REGION'),  // e.g., 'us-west-2'
                'version' => 'latest',
                'endpoint' => 'https://a3g64zddycx1fg-ats.iot.us-west-2.amazonaws.com',
            ]);

            $result = $iotDataPlaneClient->getThingShadow([
                'thingName'  => 'PillThing',
            ]);

            $shadow = json_decode($result->get('payload')->getContents());

            // The result contains the new state of the thing shadow
            // $newState = json_decode($result->get('payload'));

            // return response()->json($newState);

            $doses = $shadow->state->reported->doses;

            foreach ($doses as $dose) {
                if (!Dose::where('time', $dose->time)->get()->count() > 0) {
                    $newDose = new Dose();
                    $newDose->time = $dose->time;
                    $newDose->schedule_id = $dose->schedule_id;
                    $newDose->save();
                }
            }

            $latestRefill = Refill::latest()->first();

            if ($latestRefill) {
                $pillCount = $latestRefill->pills;
                $doses = Dose::where('time', '>', $latestRefill->created_at)->get();

                foreach ($doses as $dose) {
                    $pillCount -= $dose->schedule->rule->pills;
                }

                $pills = $pillCount;
            } else {
                $pills = 0;
            }

            if ($pills < 1) {
                // Notify that it needs to be refilled
            } elseif ($pills < 10) {
                // Notify that it is low
            } 

        })->everyFiveMinutes();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
