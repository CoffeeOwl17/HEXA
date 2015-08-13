<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class comment extends Model
{
  protected $table    = 'comment';
  public $primaryKey  = 'comment_id';
  public $timestamps  = false;
  protected $fillable = ['comment', 'commenter', 'commenter_id', 'post_id'];

  public function post(){
    return $this->belongsTo('App\post', 'post_id');
  }

  public function sentiment(){
    return $this->hasOne('App\commentSentiment', 'comment_id');
  }
}
