<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class post extends Model
{
  protected $table    = 'post';
  public $primaryKey  = 'post_id';
  public $timestamps  = false;

  public function users(){
    return $this->belongsToMany('App\user', 'post_user', 'post_id', 'user_id')->withTimestamps();;
  }

  public function page(){
    return $this->belongsTo('App\page', 'page_id');
  }

  public function comments(){
    return $this->hasMany('App\comment', 'post_id');
  }
}
