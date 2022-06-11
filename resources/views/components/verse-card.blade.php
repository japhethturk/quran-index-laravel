<div class="collapsible-header" id="verse-{{$verseId}}">
    <div class="verse">
      <span class="num-holder">﴾</span><span class="num">{{$verseId}}</span><span class="num-holder">﴿</span>
      <span class="ayath-content">{!!$verseText!!}</span>
    </div>
  </div>
  <div class="collapsible-body">
    <a href="{{$compareUrl}}">
    <button class="btn waves-effect waves-light operation-btn ">
      {{__('app.compare')}}
      <i class="material-icons right">segment</i>
    </button>
    </a>
{{--    <button onclick="bookmark({{$verseId}})" class="btn waves-effect waves-light operation-btn">--}}
{{--      {{__('app.bookmark')}}--}}
{{--      <i class="material-icons right">turned_in_not</i>--}}
{{--    </button>--}}
    <button onclick="openShareModal({{$verseId}}, '{{__('app.chapter_verse', ['chapter' => $chapterName, 'verse' => $verseId])}}')" class="btn waves-effect waves-light operation-btn">
      {{__('app.share')}}
      <i class="material-icons right">share</i>
    </button>
    <button  onclick="copyText({{$verseId}}, '{{__('app.chapter_verse', ['chapter' => $chapterName, 'verse' => $verseId])}}')" class="btn waves-effect waves-light operation-btn">
      {{__('app.copy')}}
      <i class="material-icons right">content_copy</i>
    </button>
  </div>
