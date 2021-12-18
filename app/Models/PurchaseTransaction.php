<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseTransaction extends Model
{
    public $timestamps = false;
    protected $table = 't_purchase_transaction';

    protected $fillable = [
        'id',
        'customer_id',
        'total_spent',
        'total_saving',
        'transaction_at'
    ];

    public function customer()
    {
        return $this->belongsTo('App\Models\master\Customer');
    }
}
