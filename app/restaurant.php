<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use SoftDeletes;

class Restaurant extends Model
{

	protected $fillable = ['name', 'permalink', 'raw_address', 'street', 'city', 'state', 'zip', 'city', 'latitude', 'longitude', 'phone', 'description', 'description_plaintext', 'date_added'];

  protected $data = ['date_added'];

  public function terms()
  {
      return $this->belongsToMany('App\Term');
  }
}
