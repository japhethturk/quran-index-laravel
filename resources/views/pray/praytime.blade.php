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
    <x-search-small
        placeholder="{{__('app.search_city')}}"
        name="pray"
        lang="{{$lang}}"></x-search-small>
@endsection

@section('right-button')
    <li>
        <a id="find-location" style="max-height: 64px;" class="waves-effect waves-block waves-light tooltipped right-button" data-position="left" data-tooltip="{{__('app.find_my_location')}}" href="#">
            <i class="material-icons">location_on</i>
        </a>
    </li>
@endsection

@section('content')

    <h4 class="show-mobile">{{__('app.prayer_time')}}</h4>
    <div class="card">
        <div class="card-content-l">
            <div class="row">
                @foreach ($cites as $city)
                    <div class="col s12 m6 l4">
                        <x-city-pray-time
                            location="{{$city->name}}"
                            fajr="{{$city->times[0]}}"
                            sun="{{$city->times[1]}}"
                            zuhr="{{$city->times[2]}}"
                            asr="{{$city->times[3]}}"
                            sunset="{{$city->times[4]}}"
                            maghrib="{{$city->times[5]}}"
                            isha="{{$city->times[6]}}"/>
                    </div>
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


    function initGeolocation()
    {
        if( navigator.geolocation )
        {
            // Call getCurrentPosition with success and failure callbacks
            navigator.geolocation.getCurrentPosition( success, fail, {enableHighAccuracy: true, maximumAge: 10000} );
        }
        else
        {
            alert("Sorry, your browser does not support geolocation services.");
        }
    }

    function success(position)
    {
        const longitude = position.coords.longitude;
        const latitude = position.coords.latitude;

        $.get( `{{$url}}api/v1/nearest/longitude/${longitude}/latitude/${latitude}`, function( {city} ) {
            const url = `{{$url . $lang . '/' . __('slug.location')}}/${city.country_slug}/${city.country_id}/${city.state_slug}/${city.state_id}/${city.city_slug}/${city.city_id}`;
            // console.log(url);
            window.location.href = url;
        });
    }

    function fail()
    {
        // console.log("fail")
        M.toast({html: '{{__('app.allow_location_message')}}'})
    }


    $(document).ready(function(){
        // initGeolocation();

        $("#find-location").click(function(){
            initGeolocation()
        });
    });

</script>
@endsection
