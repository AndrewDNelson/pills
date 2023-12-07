<?php

namespace App\View\Components;

use App\Models\Dose;
use App\Models\Refill;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Alert extends Component
{
    public $isVisible;
    public $message;
    public $route;

    /**
     * Create a new component instance.
     */
    public function __construct()
    {
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
            $this->isVisible = true;
            $this->message = "The device is out of pills. Click here to go to pill refill page.";
        } elseif ($pills < 4) {
            $this->isVisible = true;
            $this->message = "The device is low on pills. Click here to go to pill refill page.";
        } else {
            $this->isVisible = false;
        }

        $this->route = 'refill.index';
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.alert');
    }
}
