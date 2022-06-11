@extends('layout.site')
@section('title', __('app.index_title'))
@section('keywords', $meta['keywords'])
@section('description', $meta['description'])
@section('name', $meta['name'])
@section('author', $meta['author'])
@section('twitter', $meta['twitter'])
@section('locale', $locale)
@section('url', $url)
@section('content')


      <h4 class="show-mobile">{{__('app.life_with_quran')}}</h4>
      <div class="card">
        <div class="card-content-l">
          <div class="collection">
            @foreach($lives as $life)
            <a href="{{$slugWord.str_slug($life->life_name).'/'.$life->life_id}}" class="collection-item">{{$life->life_name}}</a>
            @endforeach
          </div>
        </div>
      </div>

@endsection
