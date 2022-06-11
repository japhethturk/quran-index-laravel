<?php

namespace App\View\Components;

use Illuminate\View\Component;

class CityPrayTime extends Component
{

    public $location;
    public $fajr;
    public $sun;
    public $zuhr;
    public $asr;
    public $sunset;
    public $maghrib;
    public $isha;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($location,  $fajr, $sun, $zuhr, $asr, $maghrib, $sunset, $isha)
    {
        $this->location = $location;
        $this->fajr = $fajr;
        $this->sun = $sun;
        $this->zuhr = $zuhr;
        $this->asr = $asr;
        $this->maghrib = $maghrib;
        $this->sunset = $sunset;
        $this->isha = $isha;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.city-pray-time');
    }
}
