<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function getUser()
    {
    	return ['1' =>2];
    	return \Auth::user();
    }
}
