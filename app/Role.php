<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = [
        'name'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'acceses'
    ];

    protected $appends = [
        'can'
    ];

    public function acceses()
    {
        return $this->hasMany('App\Access');
    }

    public function getCanAttribute()
    {
        if ($this->acceses->first()) {
            return true;
        }
        return false;
    }
}
