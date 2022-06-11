<?php

namespace App\View\Components;

use Illuminate\View\Component;

class JuzCard extends Component
{

    public $juzId;
    public $url;
    public $chapters;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($juzId, $url, $chapters)
    {
      $this->juzId = $juzId;
      $this->url = $url;
      // $this->chapters = $chapters;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.juz-card');
    }
}
