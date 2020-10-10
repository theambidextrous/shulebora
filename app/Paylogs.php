<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Paylogs extends Model
{
    /**
     * The attributes that are mass assignable .
     *
     * @var array
     */
    protected $fillable = [
        'order', 'buyer', 'payer', 'phone', 'payref', 'amount', 'time', 'method', 'paystring',
    ];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [];
}
