<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Country extends Model
{
    protected $fillable = [
        'name', 'code', 'continent_id'
    ];
    
    
    /***************************************************************************/
    /**
     * Return Start and End dates of the first repetition of an event - By Event ID
     *
     * @param  none
     * @return \App\Country the collection containing all the countries
     */    
    public static function getCountries(){
        $minutes = 15;
        $ret = Cache::remember('countries_list', $minutes, function () {
            return Country::orderBy('name')->pluck('name', 'id');
        });
        
        return $ret;
    }
    
    /***************************************************************************/
    /**
     * Return active Continent and Countries Json Tree (for hp select filters, vue component)
     *
     * @param  none
     * @return JSON
     */    
    public static function getActiveCountriesByContinent(){
        $minutes = 15;
        $ret = Cache::remember('active_continent_countries_json_tree', $minutes, function () {
            return Country::orderBy('name')->pluck('name', 'id');
        });
    
        return $ret;
    }
}
