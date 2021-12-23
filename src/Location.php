<?php

namespace Jiejunf\LaravelLocations;

use Symfony\Component\String\UnicodeString;

class Location
{
    public static function getTimeZones(?string $lang = null): object
    {
        return getTimeZones(...func_get_args());
    }

    public static function getContinents(?string $lang = null): object
    {
        return getContinents(...func_get_args());
    }

    public static function getContinent(string $code, ?string $lang = null): ?string
    {
        return getContinent(...func_get_args());
    }


    public static function getCountries(?string $lang = null): object
    {
        return getCountries(...func_get_args());
    }

    public static function getCountriesNames(?string $lang = null): object
    {
        return getCountriesNames(...func_get_args());
    }

    /**
     * @param string $code
     * @param ?string $lang
     *
     * @return ?object{iso:string,name:string,native:string,currency:string,phone:string,timezone:string,languages:array<string>,continent:string,capital:string}
     */
    public static function getCountry(string $code, ?string $lang = null): ?object
    {
        return getCountry(...func_get_args());
    }

    public static function getStates(?string $country = null): ?object
    {
        return getStates(...func_get_args());
    }

    public static function getState(string $code, ?string $country = null): ?string
    {
        return getState(...func_get_args());
    }

    public static function getEmoji(string $iso2): UnicodeString
    {
        $ords = [];
        foreach (str_split($iso2) as $chr) {
            $ords[] = ord($chr) + 127397;
        }
        return UnicodeString::fromCodePoints(...$ords);
    }
}