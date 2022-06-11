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

    <h4 class="show-mobile">{{__('app.quran_dictionary')}}</h4>
    <div class="card">
        <div class="card-content letter-box">
            @foreach($letterWords as $letter)
                <a href="#letter-{{$letter->letter_id}}" class="waves-effect waves-teal btn-flat btn-letter">{{$letter->letter_name}}</a>
            @endforeach
        </div>
    </div>

        <div class="card">
            <div class="card-content-l">
                <div class="collection">
                    @foreach($letterWords as $letter)
                        <div id="letter-{{$letter->letter_id}}" class="collection-header" style="margin-left:15px;"><h5>{{$letter->letter_name}}</h5></div>
                        @php
                            $words = json_decode($letter->words);
                        @endphp
                        @isset($words)
                            @foreach($words as $word)
                                <a href="{{$slugWord.str_slug($letter->letter_name).'/'.$letter->letter_id.'/'.str_slug($word->word_name).'/'.$word->word_id}}" class="collection-item">{{$word->word_name}}</a>
                            @endforeach
                        @endisset

                    @endforeach
                </div>
            </div>
        </div>

@endsection
