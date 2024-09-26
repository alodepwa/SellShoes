<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Size extends Model
{
    protected $fillable=[
    	'name'
    ];
    protected $sizes=[
    	"deleted_at"
    ];

    public function products(){
    	return $this->belongsToMany('App\Product','product_sizes','size_id','product_id')->withPivot('quantity')->withTimestamps();;
    }
}
