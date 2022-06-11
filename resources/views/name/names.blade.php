@extends('layout.site')
@section('title', 'Kuran Fihristi')
@section('keywords', '')
@section('description', '')

@section('content')

      <h4 class="show-mobile">{{__('app.names_of_god')}}</h4>
      <div class="card">
        <div class="card-content-l">
          <div class="collection">
            @foreach($names as $name)
            <a id="name-{{$name->name_id}}" href="{{$slugName.str_slug($name->name_text).'/'.$name->name_id}}" class="collection-item">
                <div class="row">
                    <div class="col s12 m6 l8">
                        {{$name->name_text}}
                    </div>
                    <div class="col s12 m6 l4" style="color:#343434">
                        {{$name->name_description}}
                    </div>
                </div>
            </a>
            @endforeach
          </div>
        </div>
      </div>

@endsection
