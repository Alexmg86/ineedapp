<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    	'user_id', 'group_id', 'good_id', 'price'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $visible = [
    	'price', 'icon_id', 'name', 'created_at'
    ];

    protected $appends = [
        'icon_id', 'name'
    ];

    protected $with = [
        'goods'
    ];

    public function goods()
    {
        return $this->belongsTo('App\Good', 'good_id', 'id');
    }

    public function getIconIdAttribute()
    {
        return $this->goods->icon_id;
    }

    public function getNameAttribute()
    {
        return $this->goods->name;
    }

    public function getPriceAttribute($value)
    {
        $value = (String)$value;
        return str_replace('.', ',', $value);
    }

    public function getCreatedAtAttribute($value)
    {
        return \Carbon\Carbon::parse($value)->format('d-m-Y');
    }
}
