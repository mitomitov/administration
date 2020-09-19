<?php

namespace Charlotte\Administration\Helpers;

use App\Modules\Countries\Models\Banner;
use App\Modules\Countries\Models\CountryIsoCode;
use Charlotte\Administration\Models\Setting;
use Illuminate\Support\Facades\App;

class Settings
{
    static function endsWith($string, $endString)
    {
        $len = strlen($endString);
        if ($len == 0) {
            return true;
        }
        return (substr($string, -$len) === $endString);
    }

    private static function getBanner($banner_field) {
        $location = geoip()->getLocation();
        $country = CountryIsoCode::query()->where("iso", $location->getAttribute("iso_code"))->first();
        $country_found = true;
        if (empty($country)) {
            $country_found = false;
            $country = CountryIsoCode::query()->where("iso", "NONE")->first();
        }
        $banner = Banner::query()->where("iso_id", $country->id)->first();
        if(empty($banner)) {
            if ($country_found) {
                $country = CountryIsoCode::query()->where("iso", "NONE")->first();
                $banner = Banner::query()->where("iso_id", $country->id)->first();
            }
            if (empty($banner)) {
                return "";
            } else {
                return $banner->$banner_field;
            }
            return "";
        } else {
            return $banner->$banner_field;
        }
    }


    public static function get($field)
    {
        if (self::endsWith($field, "_banner_content")) {
            return self::getBanner($field);
        }
        $data = Setting::where('field', $field)->first();

        if (empty($data)) {
            return null;
        }

        return $data->value;
    }

    public static function getTranslated($field, $locale = null)
    {
        $locale = (empty($locale)) ? App::getLocale() : $locale;

        $data = Setting::where('field', $locale . '.' . $field)->first();

        if (empty($data)) {
            return null;
        }

        return $data->value;
    }

    public static function getFile($field, $collection = '')
    {
        $data = Setting::where('field', $field)->first();

        if (empty($data)) {
            return null;
        }

        return $data->getFirstMedia()->getUrl($collection);
    }
}
