<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Team;
use App\Models\League;
use App\Models\Player;

use App\Http\Requests;

use Session;
use Auth;

class LeagueStandingController extends Controller
{
    /**
     *  Authenticate access
     */
    public function __construct()
	{  
	    $this->middleware('auth',['except' => ['index','show']]);	  
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
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $leagueStanding = Team::where('team_id', $id)
                        ->get();
        
        return view ('pages.standing.edit', ['standing' => $leagueStanding[0]]);
	
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $standing = Team::find($id);
        
        $standing->wins = $request->win;
        $standing->losses = $request->loss;
        $standing->modified_date = date("y-m-d h:i:s");
        
        $standing->save();
                    

	   $getLeague = League::where('league_id',Session::get('selectedLeague'))
        		   ->get();

        return redirect()->route('league.show', $getLeague[0]->slug);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
