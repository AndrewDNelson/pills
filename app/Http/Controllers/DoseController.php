<?php

namespace App\Http\Controllers;

use App\Models\Dose;
use Illuminate\Http\Request;
use Aws\IotDataPlane\IotDataPlaneClient;


class DoseController extends Controller
{

    private function getShadow()
    {

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
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('doses.index', ['doses' => Dose::all()]); 
    }

    /**
     * Update the specified resource in storage.
     */
    public function update()
    {
        $this->getShadow();
        return redirect()->route('doses.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Dose $dose)
    {
        $dose->delete();    
        return redirect()->route('doses.index');
    }
}
