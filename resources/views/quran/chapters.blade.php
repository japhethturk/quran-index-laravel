@extends('layout.site')
@section('title', $meta['title'])
@section('keywords', $meta['keywords'])
@section('description', $meta['description'])
@section('name', $meta['name'])
@section('author', $meta['author'])
@section('twitter', $meta['twitter'])
@section('locale', $locale)
@section('url', $url)

@section('head')
<link  href="https://cdn.qurancdn.com/assets/font-faces-ce31844729f73b6891355c245709cf3751c315aeb572c074ac6445ad0be7dd4b.css"  rel="stylesheet" >
@endsection


@section('search-large')
    <x-search-large
        placeholder="{{__('app.search_in_quran')}}"
        name="chapter"
        lang="{{$locale}}">
    </x-search-large>
@endsection

@section('search-small')
    <x-search-small
        placeholder="{{__('app.search_in_quran')}}"
        name="chapter"
        lang="{{$locale}}">
    </x-search-small>
@endsection


@section('content')

    <div class="section">

        <div class="row" style="margin-top: 1em;">

            <div class="col l4 s1 m2 pr-0"></div>
            <div class="col l4 s10 m8 pl-0">
                <ul class="tabs chapter-tabs">
                    @foreach ($chapterOrderList as $order)
                        <li class="tab col s4 p-0">
                            <a href="#{{$order['slug']}}" @class(['active' => $order['slug'] == $selectedOrderTab])>
                                <span>{{$order['label']}}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="col l4 s1 m2 pr-0"></div>

        </div>

        <div class="tab-contents">

            <!-- Surah Tab -->
            <div id="surah" class="col s12">
                <div class="row">
                    @foreach ($chaptersBySurah as $chapter)
                        <x-chapter-card
                            row="{{$chapter->chapter_id}}"
                            chapterId="{{$chapter->chapter_id}}"
                            url="{{$url. __('slug.chapter') .'/'. $chapter->url}}"
                            name="{{$chapter->chapter_name}}"
                            mean="{{$chapter->chapter_mean}}"
                            verseCount="{{$chapter->verse_count}}"
                        />
                    @endforeach
                </div>
            </div>
            <!-- End of Surah tab -->

            <!-- Revelation Tab -->
            <div id="revelation" class="col s12">
                <div class="row">
                    @foreach ($chaptersByDescent as $chapter)
                        <x-chapter-card
                            row="{{$chapter->descent_id}}"
                            chapterId="{{$chapter->chapter_id}}"
                            url="{{$url.__('slug.chapter').'/'.$chapter->url}}"
                            name="{{$chapter->chapter_id.'. '.$chapter->chapter_name}}"
                            mean="{{$chapter->chapter_mean}}"
                            verseCount="{{$chapter->verse_count}}"
                        />
                    @endforeach
                </div>
            </div>
            <!-- End of Revelation Tab -->

            <!-- Juz Tab -->
            <div id="juz" class="col s12">
                <div class="row" style="margin-top:1em">
                    <div class="col s12 m6 l4 chapter-card-pad">
                        @foreach (array_slice($chaptersByJuz, 0, 19) as $juz)
                            @component('components.juz-card', ['juzId'=>$juz['id'], 'url'=>$url, 'chapters' => $juz['chapters'] ] )
                            @endcomponent
                        @endforeach
                    </div>
                    <div class="col s12 m6 l4 chapter-card-pad">
                        @foreach (array_slice($chaptersByJuz, 19, 9) as $juz)
                            @component('components.juz-card', ['juzId'=>$juz['id'], 'url'=>$url, 'chapters' => $juz['chapters'] ] )
                            @endcomponent
                        @endforeach
                    </div>
                    <div class="col s12 m6 l4 chapter-card-pad">
                        @foreach (array_slice($chaptersByJuz, 28, 2) as $juz)
                            @component('components.juz-card', ['juzId'=>$juz['id'], 'url'=>$url, 'chapters' => $juz['chapters'] ] )
                            @endcomponent
                        @endforeach
                    </div>
                </div>
            </div>
            <!-- End of Juz Tab -->

        </div>

    </div>

@endsection

@section('footer')

<script type="text/javascript">

document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.autocomplete');
});
// verse-search search-box
$( "#verse-search" ).click(function() {
    const searchText = $('#search-box').val();
    window.location.href = `{{$url.__('slug.search')}}/${searchText}`;
});

$('#search-box').on('keypress', function (e) {
    if(e.which === 13){
        const searchText = $('#search-box').val();
        window.location.href = `{{$url.__('slug.search')}}/${searchText}`;
    }
});

{{--$(document).ready(function(){--}}
{{--  $('input.autocomplete').autocomplete({--}}
{{--      data: {--}}
{{--        @foreach ($chaptersByDescent as $chapter)--}}
{{--        "{{$chapter->chapter_name}}" : "{{$url . __('slug.chapter') .'/'. $chapter->url}}",--}}
{{--        @endforeach--}}
{{--      }--}}
{{--  });--}}
{{--});--}}

</script>
@endsection
