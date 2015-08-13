<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class commentSentiment extends Model
{
  protected $table    = 'commentSentiment';
  public $primaryKey  = 'id';
  public $timestamps  = false;

  public function comment(){
    return $this->belongsTo('App\comment', 'comment_id');
  }
}
