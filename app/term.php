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

  public function scopeCategories($query)
  {
    return $query->where('type', 'category');
  }

  public function scopeTags($query)
  {
    return $query->where('type', 'tag');
  }

  public function scopeCuisines($query)
  {
    return $query->where('type', 'category')->where('subtype','cuisine');
  }

}
