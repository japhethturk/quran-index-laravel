<?php

namespace App\Repositories;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;


class PrayRepositories implements PrayRepositoriesInterface
{

//    const coordinates = search.split(',')
//    const latitude = functions.numeric(coordinates[0])
//    const longitude = functions.numeric(coordinates[1])
//    orderBy = `ORDER BY ABS( c.latitude - ${latitude}), ABS( c.longitude - ${longitude}) `
//
//    limit = `LIMIT ${parseInt(query.limit)}`;
//    offset = `OFFSET ${parseInt(query.offset)}`;
//
//    let precision = 1
//    results = await sql_query(`SELECT c.id as city_id, s.id as state_id, co.id as country_id, c.name AS city, s.name AS state, co.name as country FROM cities AS c  ${join} WHERE ROUND(c.latitude, ${precision}) = ROUND(${latitude}, ${precision}) AND ROUND(c.longitude, ${precision}) = ROUND(${longitude}, ${precision}) ${orderBy}  ${limit} ${offset};`);
//    // if dosn't find then round to 0 precision and check again
//    if(results.length === 0) {
//        precision = 0
//        results = await sql_query(`SELECT c.id as city_id, s.id as state_id, co.id as country_id, c.name AS city, s.name AS state, co.name as country FROM cities AS c  ${join} WHERE ROUND(c.latitude, ${precision}) = ROUND(${latitude}, ${precision}) AND ROUND(c.longitude, ${precision}) = ROUND(${longitude}, ${precision}) ${orderBy}  ${limit} ${offset};`);
//    }

    public function mainCites($lang): Collection
    {
        $query = DB::table('cities')->select('cities.id', 'cities.name', 'cities.state_id', 'state_code', 'cities.country_id', 'cities.country_code', 'cities.latitude', 'cities.longitude', 'countries.timezones')
        ->leftJoin('countries', 'countries.id', '=', 'cities.country_id');
        switch ($lang) {
            case "tr":
                $query->where('cities.country_id', '=',225);
                $query->where(function ($query) {
                    $query->orWhere('cities.id', '=', 107851);
                    $query->orWhere('cities.id', '=', 107166);
                    $query->orWhere('cities.id', '=', 108923);
                    $query->orWhere('cities.id', '=', 107414);
                    $query->orWhere('cities.id', '=', 107169);
                    $query->orWhere('cities.id', '=', 107504);
                });
                $query->orderByRaw("FIELD(cities.id, 107504, 107169, 107414, 108923, 107166, 107851) DESC");
                break;
            case "az":
                $query->where(function ($query) {
                    $query->orWhere('cities.country_id', '=', 16);
                    $query->orWhere('cities.country_id', '=', 103);
                });
                $query->where(function ($query) {
                    $query->orWhere('cities.id', '=', 8052);
                    $query->orWhere('cities.id', '=', 8180);
                    $query->orWhere('cities.id', '=', 8081);
                    $query->orWhere('cities.id', '=', 8120);
                    $query->orWhere('cities.id', '=', 135125);
                    $query->orWhere('cities.id', '=', 134568);
                });
                $query->orderByRaw("FIELD(cities.id, 134568, 135125, 8120, 8081, 8180, 8052) DESC");
                break;
            case "en":
                $query->where(function ($query) {
                    $query->orWhere('cities.country_id', '=', 14);
                    $query->orWhere('cities.country_id', '=', 158);
                    $query->orWhere('cities.country_id', '=', 232);
                    $query->orWhere('cities.country_id', '=', 233);
                });
                $query->where(function ($query) {
                    $query->orWhere('cities.id', '=', 122795);
                    $query->orWhere('cities.id', '=', 120784);
                    $query->orWhere('cities.id', '=', 50388);
                    $query->orWhere('cities.id', '=', 6235);
                    $query->orWhere('cities.id', '=', 7408);
                    $query->orWhere('cities.id', '=', 79773);
                });
                $query->orderByRaw("FIELD(cities.id, 79773, 7408, 6235, 50388, 120784, 122795) DESC");
                break;
            case "ru":
                $query->where(function ($query) {
                    $query->orWhere('cities.country_id', '=', 112);
                    $query->orWhere('cities.country_id', '=', 182);
                    $query->orWhere('cities.country_id', '=', 226);
                    $query->orWhere('cities.country_id', '=', 236);
                });
                $query->where(function ($query) {
                    $query->orWhere('cities.id', '=', 99972);
                    $query->orWhere('cities.id', '=', 101074);
                    $query->orWhere('cities.id', '=', 100302);
                    $query->orWhere('cities.id', '=', 65737);
                    $query->orWhere('cities.id', '=', 106884);
                    $query->orWhere('cities.id', '=', 130005);
                });
                $query->orderByRaw("FIELD(cities.id, 130005, 106884, 65737, 100302, 101074, 99972) DESC");
            default:
                $query->orderByRaw("id DESC");
        }

        return $query->get();
    }

    public function searchCities($cityName): Collection
    {
        $likeCity = '%'.$cityName.'%';
        $query = DB::table('cities')->select('cities.id as city_id', 'states.id as state_id', 'countries.id as country_id',
            'cities.name as city_name', 'states.name AS state_name', 'countries.name  as country_name')
            ->leftJoin('states', function ($join) {
                $join->on('states.id', '=', 'cities.state_id');
            })
            ->leftJoin('countries', function ($join) {
                $join->on('countries.id', '=', 'cities.country_id');
            })
        ->where('cities.name', 'LIKE', $likeCity)
        ->where(function($query) use ($cityName, $likeCity){
            $query->whereRaw('MATCH (cities.name) AGAINST (?)', [$cityName]);
            $query->orWhere('states.name','LIKE', $likeCity);
            $query->orWhere('countries.name','LIKE', $likeCity);
            $query->orWhere('cities.name','LIKE', $likeCity);
        })
//        ->whereOr('states.name', 'LIKE', $likeCity)
//        ->whereOr('countries.name', 'LIKE', $likeCity)
        ->orderBy('cities.name')
        ->limit(7);
        return $query->get();
    }

    public function city($country_id, $state_id, $city_id) {
        $query = DB::table('cities')->select('cities.id as city_id', 'states.id as state_id', 'countries.id as country_id',
            'cities.name as city_name', 'states.name AS state_name', 'countries.name  as country_name', 'countries.timezones', 'cities.latitude', 'cities.longitude')
            ->leftJoin('states', function ($join) {
                $join->on('states.id', '=', 'cities.state_id');
            })
            ->leftJoin('countries', function ($join) {
                $join->on('countries.id', '=', 'cities.country_id');
            })
            ->where('cities.id', '=', $city_id)
            ->where('states.id', '=', $state_id)
            ->where('countries.id', '=', $country_id);

        return $query->first();
    }

    public function nearest($longitude, $latitude) {
        $query = $this->nearestQuery(1, $longitude, $latitude);
        $myCity = $query->get();
        if (sizeof($myCity) === 0) {
            $query = $this->nearestQuery(0, $longitude, $latitude);
            $myCity = $query->get();
        }
        return $myCity->last();
    }


    public function nearestQuery($precision, $longitude, $latitude): Builder
    {
        $query = DB::table('cities')->select('cities.id as city_id', 'states.id as state_id', 'countries.id as country_id',
            'cities.name as city_name', 'states.name AS state_name', 'countries.name  as country_name')
            ->leftJoin('states', function ($join) {
                $join->on('states.id', '=', 'cities.state_id');
            })
            ->leftJoin('countries', function ($join) {
                $join->on('countries.id', '=', 'cities.country_id');
            })
            ->whereRaw("ROUND(cities.latitude, ?) = ROUND(?, ?) AND ROUND(cities.longitude, ?) = ROUND(?, ?)",
                [$precision, $latitude, $precision, $precision, $longitude, $precision])
        ->orderByRaw("ABS( cities.latitude - ? ), ABS( cities.longitude - ? )", [$latitude, $latitude]);
        return $query;
    }


}
