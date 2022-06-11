@extends('layout.site')
@section('title', 'Kuran Fihristi')
@section('keywords', '')
@section('description', '')

@section('content')

      <div class="card">
        <nav class="gradient-45deg-purple-light-blue">
            <div class="nav-wrapper">
              <div class="col s12" style="max-height:3em;">
                <a class="breadcrumb">{{$topic->topic_name}}</a>
              </div>
            </div>
          </nav>
        <div class="card-content" style="padding: 0">
          <div class="collection" style="margin:0">
            @foreach($phrases as $phrase)
              <a id="phrase-{{$topic->topic_id}}"
                href="{{$slugVerse.str_slug($topic->topic_name).'/'.$topic->topic_id.'/'.str_slug($phrase->phrase_name).'/'.$phrase->phrase_id}}" class="collection-item">{{$phrase->phrase_name}}</a>
            @endforeach
          </div>
        </div>
      </div>

@endsection
