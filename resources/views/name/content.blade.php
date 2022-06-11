@extends('layout.site')
@section('title', 'Kuran Fihristi')
@section('keywords', '')
@section('description', '')

@section('content')

<div id="main">
    <div class="row">
        <div class="col s12">
          <div class="card mobile-full">
            <div class="card-image">
            </div>
            <div class="card-content">
                <span class="card-title">{{$name->name_text}}</span>
                <span class="card-desc">{{$name->name_description}}</span>
                <br/><br/>
                {!! $name->name_html !!}
            </div>
          </div>
        </div>
      </div>
</div>

@endsection
