<?php

namespace App\Repositories;

use Illuminate\Support\Collection;

interface PrayRepositoriesInterface
{
    public function mainCites($lang): Collection;

    public function searchCities($cityName): Collection;

    public function nearest($longitude, $latitude);

    public function city($country_id, $state_id, $city_id);

}
