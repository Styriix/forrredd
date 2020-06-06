<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Trade extends Model
{
    protected $table    = 'trades';
    protected $fillable = ['old_price', 'new_price', 'gain_loss', 'product_id'];

    public function product()
    {
        return $this->belongsTo('App\Product');
    }

    public function userTrades()
    {
        return $this->hasMany('App\UserTradeLog', 'trade_id', 'id');
    }
}
