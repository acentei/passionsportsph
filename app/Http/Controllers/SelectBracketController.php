<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Models\Bracket;
use App\Models\League;

use Session;

class SelectBracketController extends Controller
{
    /**
     *  Authenticate access
     */
    public function __construct()
	{  
	    $this->middleware('auth');	  
	    $this->middleware('moderator');

	    if (Auth::check()){
			parent::__construct();
		}
	}

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $league = League::where('league_id',Session::get("selectedLeague"))
                        ->get();
        
        $bracket = Bracket::where('active',1)
                          ->where('deleted',0)
                          ->where('league_id',Session::get("selectedLeague"))
                          ->get();      
                
        if($league[0]->hasBracket == 0)
        {
            return view('errors.404');
        }
        
        return view('pages.game.selectbracket',['bracket' => $bracket]);
    }
        
    public function show($id)
    {
        Session::put("activeBracket",$id);
        
        return redirect()->route('game.create');
    }
}
