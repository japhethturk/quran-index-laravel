<div class="juz-card clickable"  onclick="window.open('{{$url.__('slug.juz').'/'.$juzId}}', '_self');">
  <div class="surah-card__number">
    <span>{{$juzId}}</span>
  </div>
    @foreach ($chapters as $chapter)
      <div class="row">
          <div class="col s12 chapter-card">
            <div class="card chapter-card-margin">
              <div class="card-action-me">
                <div class="number-pad col s1">
                  <div class="surah-card__number">
                    <span>{{$chapter["chapter_id"]}}</span>
                  </div>
                </div>
                <div class="desc-pad col s11">
                  <div class="col s6 chapter-name">
                    <a href="{{$url.__('slug.juz').'/'.$juzId.'#'.$chapter["chapter_id"]}}">{{$chapter["name"]}}</a>
                  </div>
                  <div class="col s6 right-align arabic-name"><span class="icon-surah icon-surah{{$chapter["chapter_id"]}}"></span></div>
                  <div class="col s8">{{$chapter["mean"]}}</div>
                  <div class="col s4 right-align">{{$chapter["verse_count"]}}</div>
                </div>
              </div>
            </div>
          </div>
        </div>
    @endforeach
</div>
