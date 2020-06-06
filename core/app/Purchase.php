<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    protected $table    = "purchases";
    protected $fillable = ['transaction_id', 'amount', 'user_id', 'product_id'];

    public function product()
    {
        return $this->belongsTo('App\Product');
    }
}
