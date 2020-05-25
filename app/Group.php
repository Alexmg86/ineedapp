<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $fillable = ['name', 'code', 'owner'];

    protected $visible = ['name', 'code', 'owner'];
}
