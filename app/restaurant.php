<?php

namespace App;

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

  //TODO: we need dependency injection to be able to swap out geocoders; also this code is a mess
  public function setRawAddressAttribute($raw_address)
  {
      $address_formatter = new Services\AddressFormatter\GeocodioAddressFormatter($raw_address);
      $this->attributes['raw_address'] = $address_formatter->getRawAddress();
      $this->attributes['formatted_address'] = $address_formatter->getFormattedAddress();
      $this->attributes['street'] = $address_formatter->getCity();
      $this->attributes['city'] = $address_formatter->getCity();
      $this->attributes['state'] = $address_formatter->getState();
      $this->attributes['zip'] = $address_formatter->getZip();
      $this->attributes['latitude'] = $address_formatter->getLatitude();
      $this->attributes['longitude'] = $address_formatter->getLongitude();

  }

}
