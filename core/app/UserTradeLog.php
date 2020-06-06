<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserTradeLog extends Model
{
    protected $table    = 'user_trade_logs';
    protected $fillable = ['purchase_trx_id', 'old_balance', 'new_balance', 'new_price', 'gain_loss', 'user_id', 'trade_id'];

    public function trade()
    {
        return $this->belongsTo('App\Trade');
    }
}
