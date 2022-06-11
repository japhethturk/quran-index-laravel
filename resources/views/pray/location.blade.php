@extends('layout.site')
@section('title', 'Kuran Fihristi')
@section('keywords', '')
@section('description', '')

@section('search-large')
    <x-search-large
        placeholder="{{__('app.search_city')}}"
        name="pray"
        lang="{{$lang}}"
    ></x-search-large>
@endsection

@section('search-small')
    <x-search-small placeholder="{{__('app.search_city')}}"
                    name="pray"
                    lang="{{$lang}}"></x-search-small>
@endsection

@section('content')

    <h4 class="show-mobile">{{__('app.prayer_time')}}</h4>
    <div class="card">
        <nav class="gradient-45deg-purple-light-blue">
            <div class="nav-wrapper">
                <div class="col s12" style="max-height:3em;">
                    <a href="{{$url.$lang.'/'.trans('slug.country').'/'.trans('slug.location').'/'.str_slug($city->country_name).'/'.$city->country_id}}" class="breadcrumb">{{$city->country_name}}</a>
                    <a href="{{$url.$lang.'/'.trans('slug.state').'/'.trans('slug.location').'/'.str_slug($city->country_name).'/'.$city->country_id.'/'.str_slug($city->state_name).'/'.$city->state_id}}" class="breadcrumb">{{$city->state_name}}</a>
                    <a href="{{$url.$lang.'/'.trans('slug.location').'/'.str_slug($city->country_name).'/'.$city->country_id.'/'.str_slug($city->state_name).'/'.$city->state_id.'/'.str_slug($city->city_name).'/'.$city->city_id}}" class="breadcrumb">{{$city->city_name}}</a>
                </div>
            </div>
        </nav>
        <div class="card-content-l">
            <div class="row">
                <div class="col s12">
                    <div class="row" style="margin-top: 1em;">

                        <div class="col l3 s0 m1 pr-0"></div>
                        <div class="col l6 s12 m10 pl-0">
                            <ul class="tabs chapter-tabs" style="margin: 1em 0 0 8px;">
                                <li class="tab col s3 p-0">
                                    <a href="#day" class="active">
                                        <span>{{__('app.day')}}</span>
                                    </a>
                                </li>
                                <li class="tab col s3 p-0">
                                    <a href="#week">
                                        <span>{{__('app.week')}}</span>
                                    </a>
                                </li>
                                <li class="tab col s3 p-0">
                                    <a href="#month">
                                        <span>{{__('app.month')}}</span>
                                    </a>
                                </li>
                                <li class="tab col s3 p-0">
                                    <a href="#range">
                                        <span>{{__('app.pick_date')}}</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="col l3 s0 m1 pr-0"></div>

                    </div>
                </div>
                <div class="col s12">
                    <div class="tab-contents">
                        <div id="day" class="col s12">
                            <h5 class="header">{{$now}}</h5>
{{--                            <blockquote>  Sonraki namaz: 3 dakika içinde İkindi</blockquote>--}}
                            <table class="responsive-table" style="margin-bottom: 2em">
                                <thead>
                                <tr>
                                    <th>{{__('app.fajr')}}</th>
                                    <th>{{__('app.sun')}}</th>
                                    <th>{{__('app.dhuhr')}}</th>
                                    <th>{{__('app.asr')}}</th>
                                    <th>{{__('app.sunset')}}</th>
                                    <th>{{__('app.maghrib')}}</th>
                                    <th>{{__('app.isha')}}</th>
                                </tr>
                                </thead>

                                <tbody>
                                <tr>
                                    @foreach($day as $time)
                                    <td>{{$time}}</td>
                                    @endforeach
                                </tr>
                                </tbody>
                            </table>
                        </div>

                        <div id="week" class="col s12">
                            <h5 class="header">{{$startWeek .' - '.$endWeek}}</h5>
                            <div class="row">
                                <table class="responsive-table" style="margin-bottom: 2em">
                                    <thead>
                                    <tr>
                                        <th>{{__('app.day')}}</th>
                                        <th>{{__('app.fajr')}}</th>
                                        <th>{{__('app.sun')}}</th>
                                        <th>{{__('app.dhuhr')}}</th>
                                        <th>{{__('app.asr')}}</th>
                                        <th>{{__('app.sunset')}}</th>
                                        <th>{{__('app.maghrib')}}</th>
                                        <th>{{__('app.isha')}}</th>
                                    </tr>
                                    </thead>

                                    <tbody>
                                    @foreach($week as $weekDay)
                                    <tr>
                                        <td>{{$weekDay['day']}}</td>
                                        @foreach($weekDay['times'] as $time)
                                            <td>{{$time}}</td>
                                        @endforeach
                                    </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div id="month" class="col s12">
                                <h5 class="header">{{$thisMonth}}</h5>
                                <div class="row">
                                    <table class="responsive-table" style="margin-bottom: 2em">
                                        <thead>
                                        <tr>
                                            <th>{{__('app.day')}}</th>
                                            <th>{{__('app.fajr')}}</th>
                                            <th>{{__('app.sun')}}</th>
                                            <th>{{__('app.dhuhr')}}</th>
                                            <th>{{__('app.asr')}}</th>
                                            <th>{{__('app.sunset')}}</th>
                                            <th>{{__('app.maghrib')}}</th>
                                            <th>{{__('app.isha')}}</th>
                                        </tr>
                                        </thead>

                                        <tbody>
                                        @foreach($month as $monthDay)
                                            <tr>
                                                <td>{{$monthDay['day']}}</td>
                                                @foreach($monthDay['times'] as $time)
                                                    <td>{{$time}}</td>
                                                @endforeach
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                        </div>

                        <div id="range" class="col s12">
                            <div class="row">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
