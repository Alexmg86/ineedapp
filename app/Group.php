<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Group extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'code',
        'owner'
    ];

    protected $visible = [
        'name',
        'code',
        'is_owner',
        'id',
        'goods',
        'count',
        'users'
    ];

    protected $appends = [
        'is_owner'
    ];

    public function goods()
    {
        return $this->hasMany('App\Good');
    }

    public function users()
    {
        return $this->belongsToMany('App\User');
    }

    public function usersActive()
    {
        return $this->belongsToMany('App\User')->wherePivot('active', 1);
    }

    public function getIsOwnerAttribute()
    {
        return $this->owner == \Auth::id();
    }

    public function scopeAuth($query)
    {
        return $query->whereHas('users', function (Builder $query) {
            $query->where('id', \Auth::id());
        });
    }
}
