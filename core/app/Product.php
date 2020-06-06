<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table    = 'products';
    protected $fillable = ['name', 'image', 'status', 'trade_status', 'price', 'description', 'category_id'];

    public function category()
    {
        return $this->belongsTo('App\ProductCategory');
    }

    public function purchases()
    {
        return $this->hasMany('App\Purchase');
    }

    public function purchasesActive()
    {
        return $this->hasMany('App\Purchase')->where('status', 1);
    }
}
