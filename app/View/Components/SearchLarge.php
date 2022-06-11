<?php

namespace App\View\Components;

use Illuminate\View\Component;

class SearchLarge extends Component
{

    public $placeholder;
    public $name;
    public $lang;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($placeholder, $name, $lang)
    {
        $this->placeholder = $placeholder;
        $this->name = $name;
        $this->lang = $lang;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.search-large');
    }
}
