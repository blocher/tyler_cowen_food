<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class term extends Model
{

  protected $fillable = array('title','type','subtype');

  public function restaurants()
  {
      return $this->belongsToMany('App\Restaurant');
  }
}
