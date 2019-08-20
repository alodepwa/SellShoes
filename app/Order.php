<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    // protected $table =['orders'];
    protected $fillable=[
    	'name','address','tel','status','email','user_id'
    ];

    public function products(){
    	return $this->belongsToMany('App\Product','order__products','order_id','product_id')->withPivot('quantity','price','size')->withTimestamps();
    }

    public function users(){
    	return $this->hasMany('App\User');
    }
}
