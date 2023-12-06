<?php

namespace App\Http\Controllers;

use App\Models\Rule;
use App\Models\Schedule;
use Illuminate\Http\Request;

class RuleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('rules.index');
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
            'pills' => 'required|integer|min:0|max:255',
            'days_of_week' => 'required|json',
            'time' => 'required|date_format:H:i',
        ]);

        $rule = Rule::create($attributes);

        $daysOfWeek = json_decode($request->days_of_week);

        foreach ($daysOfWeek as $day) {
            Schedule::create([
                'rule_id' => $rule->id,
                'day' => $day,
                'time' => $rule->time,
            ]);
        }

        return redirect()->route('rules.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Rule $rule)
    {
        return view('rules.show', compact('rule'));
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Rule $rule)
    {
        //
    }
}
