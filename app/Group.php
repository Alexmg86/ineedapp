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
        'owner'
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
}
