<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Paper extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'created_by', 'subject', 'title', 'file_content', 'is_paid', 'is_active',
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
