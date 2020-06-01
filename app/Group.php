<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
	/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    	'name', 'code', 'owner'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $visible = [
    	'name', 'code', 'owner', 'id', 'goods'
    ];

    public function goods()
    {
        return $this->hasMany('App\Good');
    }

    public function users()
    {
        return $this->belongsToMany('App\User');
    }

    public function scopeAuth($query)
    {
        return $query->whereHas('users', function (Builder $query) {
            $query->where('id', \Auth::id());
        });
    }
}
