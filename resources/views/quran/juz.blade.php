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
                <div class="col s12" style="max-height:3em;">
                    <a class="breadcrumb">{{ __('app.juz_name', ['juzId' => $juzId]) }}</a>
                </div>
            </div>
        </nav>
        <div class="card-content" style="padding: 0">
            <div class="collection" style="margin:0">
                @foreach($verses as $verse)
                    <a href="{{$slugVerse.str_slug($verse->chapter_name).'/'.$verse->chapter_id.'#verse-'.$verse->verse_id}}" class="collection-item" >
                        <b style="color:#000">{{$verse->chapter_id.'. '.$verse->chapter_name. ' '.$verse->verse_id}}</b>
                        <br/>
                        <span style="color:#343434">{{$verse->verse_text}}}</span>
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
    </script>
@endsection
