<?php
namespace EuroMillions\web\services;

use antonienko\MledozeCountriesPhpWrapper\CountriesWrapper;

class GeoService
{
    public function countryList()
    {
        $country_data_service = new CountriesWrapper(file_get_contents(APP_PATH.'/../vendor/mledoze/countries/dist/countries.json'));
        return $country_data_service->countryList();
    }
}