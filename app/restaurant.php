<?php

namespace App;


use DB;
use Illuminate\Database\Eloquent\Model;
use SoftDeletes;
use Services\AddressFormatter;

class Restaurant extends Model
{

	protected $fillable = ['name', 'permalink', 'raw_address', 'street', 'city', 'state', 'zip', 'city', 'latitude', 'longitude', 'phone', 'description', 'description_plaintext', 'date_added'];

  protected $data = ['date_added'];

  public function terms()
  {
      return $this->belongsToMany('App\Term');
  }

  public function setRawAddressAttribute($raw_address)
  {
      $address_formatter = \App::make('AddressFormatter');
      $address_formatter->init($raw_address);
      $this->attributes['raw_address'] = $address_formatter->getRawAddress();
      $this->attributes['formatted_address'] = $address_formatter->getFormattedAddress();
      $this->attributes['street'] = $address_formatter->getStreet();
      $this->attributes['city'] = $address_formatter->getCity();
      $this->attributes['state'] = $address_formatter->getState();
      $this->attributes['zip'] = $address_formatter->getZip();
      $this->attributes['latitude'] = $address_formatter->getLatitude();
      $this->attributes['longitude'] = $address_formatter->getLongitude();

  }

  public function scopeActual($query)
  {
      return $query->where('not_actual_restaurant', 0);
  }

  public function getExcerptAttribute() {

    $description = $this->description;
    $description = strip_tags($description);
    preg_match('/(])(.*)(Related Posts)/',$description,$matches);

    if (!count($matches)) {
      return $description;
    }

    $description = $matches[2];
    $description = html_entity_decode(trim($description));
    return $description;

  }

  public function getFormattedAddressAttribute() {

    return str_replace(', USA','',$this->attributes['formatted_address']);

  }

  public function scopeNearby($query, $latitude=38.813832399999995,$longitude=-77.1096706) {

     $latitude = floatval($latitude);
     $longitude = floatval($longitude);

     $distance_unit = 69; //miles
     $radius = 30000;

     $query = $query
       ->select(DB::raw('*, round(
               ' . $distance_unit . '
               * DEGREES(ACOS(COS(RADIANS(' . $latitude . '))
               * COS(RADIANS(latitude))
               * COS(RADIANS(' . $longitude . ') - RADIANS(longitude))
               + SIN(RADIANS(' . $latitude . '))
               * SIN(RADIANS(latitude))))
               ,2) AS distance_in_miles'))
        ->where('latitude','>',DB::raw($latitude . "  - (" . $radius . " / " . $distance_unit . ")"))
        ->where('latitude','<',DB::raw($latitude . "  + (" . $radius . " / " . $distance_unit . ")"))

        ->where('longitude','>',DB::raw($longitude . " - (" . $radius . " / (" . $distance_unit . " * COS(RADIANS(" . $latitude . "))))"))
        ->where('longitude','<',DB::raw($longitude . " + (" . $radius . " / (" . $distance_unit . " * COS(RADIANS(" . $latitude . "))))"))
        ->orderBy('distance_in_miles','ASC');

        return $query;

  }

  public static function getNearby($latitude=38.813832399999995,$longitude=-77.1096706, $count=400) {

    $latitude = floatval($latitude);
    $longitude = floatval($longitude);
    $count = intval($count);

    $distance_unit = 69;
    $radius = 30000;

    $restaurants =
    \App\Restaurant::select(DB::raw('*, round(
        ' . $distance_unit . "
             * DEGREES(ACOS(COS(RADIANS(" . $latitude . "))
             * COS(RADIANS(latitude))
             * COS(RADIANS(" . $longitude . ") - RADIANS(longitude))
             + SIN(RADIANS(" . $latitude . "))
             * SIN(RADIANS(latitude))))
             ,2) AS distance_in_miles"))

      ->where('latitude','>',DB::raw($latitude . "  - (" . $radius . " / " . $distance_unit . ")"))
      ->where('latitude','<',DB::raw($latitude . "  + (" . $radius . " / " . $distance_unit . ")"))

      ->where('longitude','>',DB::raw($longitude . " - (" . $radius . " / (" . $distance_unit . " * COS(RADIANS(" . $latitude . "))))"))
      ->where('longitude','<',DB::raw($longitude . " + (" . $radius . " / (" . $distance_unit . " * COS(RADIANS(" . $latitude . "))))"))

      ->orderBy('distance_in_miles','ASC')
      ->take($count)
      ->get();
    ;

    return $restaurants;

  }

}
