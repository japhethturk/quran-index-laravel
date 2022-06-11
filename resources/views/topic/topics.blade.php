@extends('layout.site')
@section('title', 'Kuran Fihristi')
@section('keywords', '')
@section('description', '')

@section('content')

      <h4 class="show-mobile">{{__('app.quran_by_topics')}}</h4>
      <div class="card">
        <div class="card-content-l">
          <div class="collection">
            @foreach($topics as $topic)
            <a id="topic-{{$topic->topic_id}}" href="{{$slugPhrase.str_slug($topic->topic_name).'/'.$topic->topic_id}}" class="collection-item">{{$topic->topic_name}}</a>
            @endforeach
          </div>
        </div>
      </div>

@endsection
