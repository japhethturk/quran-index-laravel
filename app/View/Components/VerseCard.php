<?php

namespace App\View\Components;

use Illuminate\View\Component;

class VerseCard extends Component
{

    public $chapterId;
    public $verseId;
    public $verseText;
    public $chapterName;
    public $compareUrl;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($chapterId, $verseId, $verseText, $chapterName, $compareUrl)
    {
        $this->chapterId = $chapterId;
        $this->verseId = $verseId;
        $this->verseText = $verseText;
        $this->chapterName = $chapterName;
        $this->compareUrl = $compareUrl;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.verse-card');
    }
}
