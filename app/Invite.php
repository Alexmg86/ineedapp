<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invite extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    	'group_id', 'user_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $visible = [
    	'group_id', 'user_id'
    ];
}
