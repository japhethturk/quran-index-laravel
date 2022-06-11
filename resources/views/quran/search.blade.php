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


@section('content')
    <div class="card">
        <nav class="gradient-45deg-purple-light-blue">
            <div class="nav-wrapper">
                <div class="col s10" style="max-height:3em;">
                    <input id="search" value="{{$search}}" placeholder="{{__('app.search_in_verses')}}" id="search" type="text">
                </div>
                <div class="col s2" style="max-height:3em;">
                    <button onclick="search()" style="min-width: 100%" class="btn waves-effect waves-light operation-btn">
                        {{__('app.search')}}
                    </button>
                </div>
            </div>
        </nav>
        <div class="card-content" style="padding: 0">
            <div class="collection" style="margin:0">
                @foreach($verses as $verse)
                    <a href="{{$slugVerse.str_slug($verse->chapter_name).'/'.$verse->chapter_id.'#verse-'.$verse->verse_id}}" class="collection-item" >
                        <b style="color:#000">{{$verse->chapter_id.'. '.$verse->chapter_name. ' '.$verse->verse_id}}</b>
                        <br/>
                        <span style="color:#343434">{!! str_replace($search,"<b><u>".$search."</u></b>", $verse->verse_text) !!}</span>
                    </a>
                @endforeach
            </div>
        </div>
    </div>

@endsection

@section('footer')

    <script type="text/javascript">

        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.autocomplete');
        });

        const search = () => {
            const searchText = $('#search').val();
            window.location.href = `{{$url.__('slug.search')}}/${searchText}`;
        }

        $('#search').on('keypress', function (e) {
            if(e.which === 13){
                search();
            }
        });

    </script>
@endsection
