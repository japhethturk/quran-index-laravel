@extends('layout.site')
@section('title', $life->life_name.' - '.__('app.index_title'))
@section('keywords', $meta['keywords'])
@section('description', $meta['description'])
@section('name', $meta['name'])
@section('author', $meta['author'])
@section('twitter', $meta['twitter'])
@section('locale', $locale)
@section('url', $url)

@section('content')


      <div class="card">
        <nav class="gradient-45deg-purple-light-blue">
            <div class="nav-wrapper">
              <div class="col s12" style="max-height:3em;">
                <a class="breadcrumb">{{$life->life_name}}</a>
              </div>
            </div>
          </nav>
        <div class="card-content" style="padding: 0">
          <div class="collection" style="margin:0">
            @foreach($versesByLives as $verse)
            <a href="{{$slugVerse.str_slug($verse->chapter_name).'/'.$verse->chapter_id.'#verse-'.$verse->verse_id}}" class="collection-item" >
              <b style="color:#000">{{$verse->chapter_id.'. '.$verse->chapter_name. ' '.$verse->verse_id}}</b>
              <br/>
              <span style="color:#343434">{{$verse->verse_text}}</span>
            </a>
            @endforeach
          </div>
        </div>
      </div>

@endsection
