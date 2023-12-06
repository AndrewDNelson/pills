<?php

namespace App\Http\Controllers;

use App\Models\Rule;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Aws\Exception\AwsException;
use Aws\IotDataPlane\IotDataPlaneClient;

class RuleController extends Controller
{

    private function updateShadow()
    {

        // Prepare schedules for the shadow
        $schedules = Schedule::all(); // Replace 'Schedule' with your actual model name
        $formattedSchedules = $schedules->map(function ($schedule) {
            return [
                'day' => $schedule->day,
                'time' => $schedule->time,
                'pillCount' => $schedule->rule->pills
            ];
        })->toArray();

        $iotDataPlaneClient = new IotDataPlaneClient([
            'region'  => env('AWS_DEFAULT_REGION'),  // e.g., 'us-west-2'
            'version' => 'latest',
            'endpoint' => 'https://a3g64zddycx1fg-ats.iot.us-west-2.amazonaws.com',
            'credentials' => [
                'key'    => env('AWS_ACCESS_KEY_ID'),
                'secret' => env('AWS_SECRET_ACCESS_KEY'),
            ],
        ]);

        // The state to set
        $state = [
            'desired' => [
                'led' => ['onboard' => 0],
                'schedule' => $formattedSchedules,
                "schedules"=> []
            ],
        ];

        try {
            $result = $iotDataPlaneClient->updateThingShadow([
                'thingName'  => 'PillThing',
                'payload' => json_encode(['state' => $state]),
            ]);
        } catch (AwsException $e) {
            ray($e->getMessage());
        }

        // The result contains the new state of the thing shadow
        // $newState = json_decode($result->get('payload'));

        // return response()->json($newState);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('rules.index', ['rules' => Rule::all()]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('rules.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $attributes = $request->validate([
            'pills' => 'required|integer|min:1|max:5',
            'days_of_week' => 'required|array|min:1|max:7',
            'time' => 'required|date_format:H:i',
        ]);

        $attributes['days_of_week'] = json_encode($attributes['days_of_week']);

        $rule = Rule::create($attributes);

        $daysOfWeek = json_decode($attributes['days_of_week']);

        foreach ($daysOfWeek as $day) {
            Schedule::create([
                'rule_id' => $rule->id,
                'day' => $day,
                'time' => $rule->time,
            ]);
        }

        $this->updateShadow();

        return redirect()->route('rules.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Rule $rule)
    {
        return view('rules.edit', compact('rule'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Rule $rule)
    {
        $attributes = $request->validate([
            'pills' => 'required|integer|min:1|max:5',
            'days_of_week' => 'required|array|min:1|max:7',
            'time' => 'required|date_format:H:i',
        ]);
    
        $attributes['days_of_week'] = json_encode($attributes['days_of_week']);
    
        $rule->update($attributes);

        $currentDays = $rule->schedules->pluck('day')->toArray();
        $newDays = json_decode($attributes['days_of_week']);

        // Delete Schedules for days that are no longer present
        foreach ($currentDays as $day) {
            if (!in_array($day, $newDays)) {
                $rule->schedules()->where('day', $day)->delete();
            }
        }

        // Update or create Schedules for new days
        foreach ($newDays as $day) {
            $schedule = $rule->schedules()->where('day', $day)->first();

            if ($schedule) {
                // Update the existing schedule
                $schedule->update(['time' => $rule->time]);
            } else {
                // Create a new schedule
                Schedule::create([
                    'rule_id' => $rule->id,
                    'day' => $day,
                    'time' => $rule->time,
                ]);
            }
        }

        $this->updateShadow();
    
        return redirect()->route('rules.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Rule $rule)
    {
        $rule->schedules()->each(function ($schedule) {
            $schedule->delete();
        });
    
        $rule->delete();

        $this->updateShadow();
    
        return redirect()->route('rules.index');
    }
}
