<?php

namespace App\View\Components;

use Illuminate\View\Component;

class ChapterCard extends Component
{


    public $row;
    public $chapterId;
    public $url;
    public $name;
    public $mean;
    public $verseCount;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($row, $chapterId, $url, $name, $mean, $verseCount)
    {
       $this->row = $row;
       $this->chapterId = $chapterId;
       $this->url = $url;
       $this->name = $name;
       $this->mean = $mean;
       $this->verseCount = $verseCount;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.chapter-card');
    }
}
