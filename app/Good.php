<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Good extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    	'icon_id', 'group_id', 'name', 'price'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $visible = [
    	'icon_id', 'group_id', 'name', 'price'
    ];
}
