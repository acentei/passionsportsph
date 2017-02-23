<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class NavigationController extends Controller
{ 
    
    public function index()
    {
        $league = League::where("active", 1)
            ->where("deleted", 0)
            ->get();
        
        return 
    }
}