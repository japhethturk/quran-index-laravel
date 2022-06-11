<?php

namespace App\Http\Controllers;

use App\Helpers\AppData;
use App\Helpers\PrayTime;
use App\Repositories\PrayRepositoriesInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\App;

class PrayController extends Controller
{
    private $prayRepository;
    private $prayTime;

    public function __construct(PrayRepositoriesInterface $prayRepository)
    {
        $this->prayRepository = $prayRepository;
        $this->prayTime = new PrayTime();
    }

    public function pray(Request $request)
    {
        $appLocale = App::currentLocale();
        $langId = AppData::getLangIdByCode($appLocale);

        $languageList = AppData::getLanguages([
            'tr' => trans('slug.prayer-time', [], 'tr'),
            'az' => trans('slug.prayer-time', [], 'az'),
            'ru' => trans('slug.prayer-time', [], 'ru'),
            'en' => trans('slug.prayer-time', [], 'en')
        ]);

        $slugName = config('app.url').$appLocale.'/'.trans('slug.prayer-time').'/';

        $cites = $this->prayRepository->mainCites($appLocale);
        foreach ($cites as $city) {
            $jsonDecode = json_decode($city->timezones)[0];
            $gmt = intval(substr($jsonDecode->gmtOffsetName, 4, 2));
            $city->gmt = $gmt;
            $city->zoneName = $jsonDecode->zoneName;
            date_default_timezone_set($jsonDecode->zoneName);

            $times = $this->prayTime->getPrayerTimes(time(), $city->latitude, $city->longitude, $city->gmt);
            $city->times = $times;
        }
//        dd($this->prayRepository->searchCities('sisli'));

        $properties = [
            'languageList' => $languageList,
            'lang' => $appLocale,
            'activeMenuSlug' => trans('slug.prayer-time'),
            'slugName' => $slugName,
            'cites' => $cites
        ];
        return view('pray.praytime', array_merge(AppData::getMainData(), $properties));
    }

    public function location($country_slug, $country_id, $state_slug, $state_id, $city_slug, $city_id)
    {
        $appLocale = App::currentLocale();
        $langId = AppData::getLangIdByCode($appLocale);

        $languageList = AppData::getLanguages([
            'tr' => trans('slug.prayer-time', [], 'tr'),
            'az' => trans('slug.prayer-time', [], 'az'),
            'ru' => trans('slug.prayer-time', [], 'ru'),
            'en' => trans('slug.prayer-time', [], 'en')
        ]);




        $city = $this->prayRepository->city($country_id, $state_id, $city_id);

        $jsonDecode = json_decode($city->timezones)[0];

//        $martinDateFactory = new Factory([
//            'locale' =>  $appLocale,
//            'timezone' => 'Europe/Paris',
//        ]);

//        Carbon::setLocale($appLocale);
//        $now = Carbon::now();
        $carbonSetting = [
            'locale' => $appLocale,
            'timezone' => $jsonDecode->zoneName,
        ];

        $now = Carbon::now()->settings($carbonSetting);


        $gmt = intval(substr($jsonDecode->gmtOffsetName, 4, 2));
        $city->gmt = $gmt;
        $city->zoneName = $jsonDecode->zoneName;
        date_default_timezone_set($jsonDecode->zoneName);
        $day = $this->prayTime->getPrayerTimes($now->getTimestamp(), $city->latitude, $city->longitude, $city->gmt);

        $startWeekDayTimestamp = $now->startOfWeek()->getTimestamp();
        $endWeekDayTimestamp = $now->endOfWeek()->getTimestamp();
//        dd($now->startOfWeek()->getTimestamp());
        $date = $startWeekDayTimestamp;
        $week = collect([]);
        while ($date < $endWeekDayTimestamp)
        {
            $times = $this->prayTime->getPrayerTimes($date, $city->latitude, $city->longitude, $city->gmt);
            $week->push([
                'times'=>$times,
                'day'=>$now->timestamp($date)->isoFormat('Do')
            ]);
            $date += 24* 60* 60;  // next day
        }

//        $now = Carbon::now()->settings($carbonSetting);
        $month = collect([]);
//        $startMonthDayTimestamp = $now->startOfMonth()->subMonthsNoOverflow()->getTimestamp();
//        $endMonthDayTimestamp = $now->subMonthsNoOverflow()->endOfMonth()->getTimestamp();

//        $startMonthDayTimestamp = Carbon::now()->startOfMonth()->subMonth()->getTimestamp();
//        $endMonthDayTimestamp = Carbon::now()->endOfMonth()->subMonth()->getTimestamp();

        $start = new Carbon('first day of last month');
        $end = new Carbon('last day of last month');

        $startMonthDayTimestamp = $start->getTimestamp();
        $endMonthDayTimestamp = $end->getTimestamp();

        $date = $startMonthDayTimestamp;
        while ($date <= $endMonthDayTimestamp)
        {
            $times = $this->prayTime->getPrayerTimes($date, $city->latitude, $city->longitude, $city->gmt);
            $month->push([
                'times'=>$times,
                'day'=>$now->timestamp($date)->isoFormat('Do')
            ]);
            $date += 24* 60* 60;  // next day
        }
//        dd($now->timestamp($startMonthDayTimestamp)->isoFormat('Do MM YYYY').' - '.$now->timestamp($endMonthDayTimestamp)->isoFormat('Do MM YYYY'));

        $properties = [
            'languageList' => $languageList,
            'lang' => $appLocale,
            'now'=>$now->isoFormat('dddd, Do MMMM YYYY, hh:mm'),
            'startWeek' => $now->timestamp($startWeekDayTimestamp)->isoFormat('Do MMMM YYYY'),
            'endWeek' => $now->timestamp($endWeekDayTimestamp)->isoFormat('Do MMMM YYYY'),
            'thisMonth'=>$now->isoFormat('MMMM YYYY'),
            'city' => $city,
            'activeMenuSlug' => trans('slug.prayer-time'),
            'url' => config('app.url'),
            'day' => $day,
            'week' => $week,
            'month' => $month
        ];
        return view('pray.location', array_merge(AppData::getMainData(), $properties));
    }

}
