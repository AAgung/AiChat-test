<?php

namespace App\Models\master;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    const GENDER_MALE = 'male';
    const GENDER_FEMALE = 'female';

    protected $table = 'm_customer';

    protected $fillable = [
        'id',
        'first_name',
        'last_name',
        'gender',
        'date_of_birth',
        'contact_number',
        'email'
    ];

    public function purchase_transaction()
    {
        return $this->hasMany('App\Models\PurchaseTransaction');
    }
}
