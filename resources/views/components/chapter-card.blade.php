<div class="col s12 m6 l4 chapter-card">
  <div class="card chapter-card-margin clickable"  onclick="window.open('{{$url}}', '_self');">
    <div class="card-action-me waves-effect waves-block waves-dark">
      <div class="number-pad col s1">
        <div class="surah-card__number">
          <span>{{$row}}</span>
        </div>
      </div>
      <div class="desc-pad col s11">
        <div class="col s8 chapter-name">
          <a href="{{$url}}">{{$name}}</a>
        </div>
        <div class="col s4 right-align arabic-name"><span class="icon-surah icon-surah{{$chapterId}}"></span></div>
        <div class="col s8">{{$mean}}</div>
        <div class="col s4 right-align">{{$verseCount}}</div>
      </div>
    </div>
  </div>
</div>
