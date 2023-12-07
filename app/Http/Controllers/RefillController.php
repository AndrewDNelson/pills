<?php

namespace App\Http\Controllers;

use App\Models\Dose;
use App\Models\Refill;
use Exception;
use Illuminate\Http\Request;

class RefillController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $latestRefill = Refill::latest()->first();

        if ($latestRefill) {
            $pillCount = $latestRefill->pills;
            $doses = Dose::where('created_at', '>', $latestRefill->created_at)->get();

            foreach ($doses as $dose) {
                $pillCount -= $dose->schedule->rule->pills;
            }
            
            return view('refills.index', ['pills' => $pillCount]);
        } else {
            return view('refills.index', ['pills' => 0]);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('refills.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $attributes = $request->validate([
            'pills' => 'required|integer|min:1|max:500',
        ]);

        Refill::create($attributes);
        return redirect()->route('refill.index');
    }
}
