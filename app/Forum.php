<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Forum extends Model
{
     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'asked_by', 'answered_by', 'subject', 'topic', 'question', 'answer', 'q_image', 'a_image',
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
