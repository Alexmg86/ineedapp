<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Access extends Model
{
    protected $fillable = [
        'role_id',
        'group_id',
        'user_id'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function groups()
    {
        return $this->belongsTo('App\Group');
    }
}
