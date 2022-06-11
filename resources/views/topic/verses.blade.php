@extends('layout.site')
@section('title', 'Kuran Fihristi')
@section('keywords', '')
@section('description', '')

@section('content')

      <div class="card">
        <nav class="gradient-45deg-purple-light-blue">
            <div class="nav-wrapper">
              <div class="col s12"  style="max-height:3em;">
                <a onclick="window.history.go(-1); return false;" href="" class="breadcrumb">{{$phrase->topic_name}}</a>
                <a class="breadcrumb">{{$phrase->phrase_name}}</a>
              </div>
            </div>
          </nav>
        <div class="card-content" style="padding:0">
          <div class="collection" style="margin:0">
            @foreach($versesByPhrase as $verse)
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
