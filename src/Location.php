<?php

namespace Jiejunf\LaravelLocations;

use Illuminate\Support\Str;
use Symfony\Component\String\UnicodeString;

/**
 * country{iso:string,name:string,native:string,currency:string,phone:string,timezone:string,languages:array<string>,continent:string,capital:string}
 *
 * @method static array getTimeZones(?string $lang = null)
 * @method static array getContinents(?string $lang = null)
 * @method static null|string getContinent(string $code, ?string $lang = null)
 * @method static array getCountries(?string $lang = null)
 * @method static array getCountriesNames(?string $lang = null)
 * @method static null|array getCountry(string $code, ?string $lang = null)
 * @method static null|array getStates(?string $country = null)
 * @method static null|string getState(string $code, ?string $country = null)
 */
class Location
{
    public static function __callStatic(string $name, array $arguments)
    {
        return static::getData(substr($name, 3), $arguments);
    }

    protected static function getData(string $name, array $arguments)
    {
        switch ($name) {
            case 'TimeZones':
            case 'Continents':
            case 'Countries':
            case 'CountriesNames':
                $lang = static::lang(...$arguments);
                if ($name == 'CountriesNames') {
                    return self::requireLang($lang, 'country.php');
                }
                if ($name == 'TimeZones' or $name == 'Continents') {
                    $filename = substr(strtolower($name), 0, -1) . '.php';
                    return static::requireLang($lang, $filename);
                }
                return self::loadCountries($lang);
            case 'States':
                $states = static::requireBasic('states.php');
                return $states[$arguments[0] ?? null] ?? $states;
            case 'Country':
            case 'Continent':
            case 'State':
                return static::getData($name == 'Country' ? 'Countries' : ($name . 's'), [$arguments[1] ?? null])[$arguments[0]] ?? null;
            default:
                return null;
        }
    }

    private static function lang(?string $lang = null): string
    {
        $lang = strtolower($lang ?? substr(app()->getLocale(), 0, 2));

//        if (is_dir(static::vendorPath($lang))) return $lang;

        if (!is_dir(static::langPath($lang))) $lang = 'en';
        return $lang;
    }

    private static function langPath(string $lang): string
    {
        return self::basePath() . DIRECTORY_SEPARATOR . 'lang' . DIRECTORY_SEPARATOR . $lang;
    }

//    protected static function vendorPath(string $lang): string
//    {
//        return lang_path('vendor' . DIRECTORY_SEPARATOR . 'locations' . DIRECTORY_SEPARATOR . $lang);
//    }

    private static function basePath(): string
    {
        return base_path('vendor' . DIRECTORY_SEPARATOR . 'pharaonic' . DIRECTORY_SEPARATOR . 'laravel-locations' . DIRECTORY_SEPARATOR . 'src');
    }

    protected static function requireLang(string $lang, $filename): mixed
    {
//        $vendorLang = static::vendorPath($lang) . DIRECTORY_SEPARATOR . $filename;
//        if (file_exists($vendorLang)) {
//            return require $vendorLang;
//        }
        $defaultLang = static::langPath($lang) . DIRECTORY_SEPARATOR . $filename;
        if (file_exists($defaultLang)) {
            return require $defaultLang;
        }
        return static::requireBasic($filename);
    }

    protected static function requireBasic(string $filename)
    {
        return require static::dataPath() . DIRECTORY_SEPARATOR . $filename;
    }

    private static function dataPath(): string
    {
        return self::basePath() . DIRECTORY_SEPARATOR . 'data';
    }

    protected static function loadCountries(string $lang): array
    {
        $countries = static::requireBasic('country.php');
        if ($lang != 'en') {
            $countriesNames = static::requireLang($lang, 'country.php');
        }
        foreach ($countries as $code => &$info) {
            $info['name'] = $countriesNames[$code] ?? $info['name'];
        }
        foreach (config('locations.fix_countries', []) as $code => $fix) {
            $countries[$code] = array_replace($countries[$code], $fix);
        }
        $countries = static::extCountries($countries);
        return static::topping($countries);
    }

    protected static function extCountries(array $countries): array
    {
        $enable_flags = config('locations.enable_flags');
        $flags_dir = rtrim(config('locations.flags_link'), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        $enable_emoji = config('locations.enable_emoji');
        foreach ($countries as $iso2 => &$country) {
            $country['iso2'] = $iso2;
            if ($enable_emoji) {
                $country['emoji'] = Location::getEmoji($iso2);
            }
            if ($enable_flags) {
                $iso2Lower = strtolower($iso2);
                $country['flag'] = asset($flags_dir . '4x3' . DIRECTORY_SEPARATOR . $iso2Lower . '.svg');
                $country['flag_1x1'] = asset($flags_dir . '1x1' . DIRECTORY_SEPARATOR . $iso2Lower . '.svg');
            }
        }
        return $countries;
    }

    public static function getEmoji(string $iso2): UnicodeString
    {
        $ords = [];
        foreach (str_split($iso2) as $chr) {
            $ords[] = ord($chr) + 127397;
        }
        return UnicodeString::fromCodePoints(...$ords);
    }

    public static function topping($countries): array
    {
        $topping = config('locations.topping', []);
        $countries = collect($countries);
        $topCountries = collect();
        foreach ($topping as $top) {
            $topCountries->put($top, $countries->pull($top));
        }
        return $topCountries->concat($countries)->all();
    }
}