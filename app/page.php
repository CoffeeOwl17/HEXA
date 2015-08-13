<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class page extends Model
{
  protected $table    = 'page';
  public $primaryKey  = 'page_id';
  public $timestamps  = false;

  public function posts(){
    return $this->hasMany('App\post', 'page_id');
  }
}
