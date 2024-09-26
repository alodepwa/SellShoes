<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Promotion extends Model
{
    protected $fillable=[
    	'name','unit','start','end','product_id'
    ];

    protected $promotions=[
    	"deleted_at"
    ];
    public function product(){
    	return $this->belongsTo('App\Product');
    }
}
