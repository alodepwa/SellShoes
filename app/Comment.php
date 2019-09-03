<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
	// protected $table="comments";
	protected $fillable=['rate','content','status','user_id','product_id'];

	public function user(){
		return $this->belongsTo('App\User');
	}
	public function product(){
		return $this->belongsTo('App\Product');
	}
}
