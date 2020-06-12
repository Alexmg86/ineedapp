<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Group extends Model
{
    use SoftDeletes;
    
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
        'name', 'code', 'is_owner', 'id', 'goods', 'count'
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
