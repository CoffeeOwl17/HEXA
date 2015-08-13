<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class user extends Model
{
    protected $table    = 'user';
    public $primaryKey  = 'user_id';
    public $timestamps  = false;

    public function posts(){
      return $this->belongsToMany('App\post', 'post_user', 'user_id', 'post_id')->withTimestamps();
    }
}
