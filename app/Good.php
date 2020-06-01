<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Good extends Model
{
    use SoftDeletes;
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

    public function getIconIdAttribute($value)
    {
        return (String)$value;
    }

    public function getPriceAttribute($value)
    {
        $value = (String)$value;
        return str_replace('.', ',', $value);
    }
}
