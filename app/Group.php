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
        'users',
        'owner',
        'access'
    ];

    public function goods()
    {
        return $this->hasMany('App\Good');
    }

    public function access()
    {
        return $this->hasMany('App\Access', 'group_id', 'id');
    }

    public function users()
    {
        return $this->belongsToMany('App\User');
    }

    public function usersActive()
    {
        return $this->belongsToMany('App\User')->wherePivot('active', 1);
    }

    public function scopeAuth($query, $hash = null)
    {
        return $query->whereHas('users', function (Builder $query) use ($hash) {
            $query->when($hash, function ($query, $hash) {
                return $query->where('hash', $hash);
            }, function ($query) {
                return $query->where('id', \Auth::id());
            });
        });
    }

    public function scopeShop($query, $hash = null)
    {
        $userId = \Auth::id();
        return $query->whereHas('users', function (Builder $query) use ($userId) {
            $query->where('id', $userId);
        })->where(function ($query) use ($userId) {
            $query->where('owner', $userId)->orWhereHas('access', function (Builder $query) use ($userId) {
                $query->where([['user_id', $userId], ['role_id', 1]]);
            });
        });
    }
}
