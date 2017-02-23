<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Models\Bracket;
use App\Models\League;

use Session;
use Auth;

class TeamBracketController extends Controller
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
        
        return view('pages.bracket.index',['bracket' => $bracket]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       return view('pages.bracket.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //validate fields
        $this->validate($request, [
            'name' => 'required',             
        ]);
           
        $samename = Bracket::where('deleted',0)
                         ->where('active',1)
                         ->where('display_name',$request->name)                        
                         ->where('league_id',Session::get("selectedLeague"))
                         ->get();
        
        if(count($samename) != 0)
        {
            //flash a notification
            Session::flash('error_message', 'Bracket already exists.');
            
            return redirect()->back();
        }
        else
        {
            $bracket = new Bracket;

            $bracket->display_name = $request->name;            
            $bracket->league_id = Session::get("selectedLeague");            
            $bracket->active = 1;
            $bracket->deleted = 0;

            $bracket->save();

            //flash a notification
            Session::flash('flash_message', 'Bracket: "'.$request->name.'" is successfully created.');

            return redirect()->route('bracket.index');   
        }
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
        $bracket = Bracket::find($id);
        
        return view('pages.bracket.edit',['bracket' => $bracket]);
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
        //validate fields
        $this->validate($request, [
            'name' => 'required',             
        ]);
           
        $samename = Bracket::where('deleted',0)
                         ->where('active',1)
                         ->where('display_name',$request->name)                        
                         ->where('league_id',Session::get("selectedLeague"))
                         ->where('bracket_id','!=',$id)
                         ->get();
        
        if(count($samename) != 0)
        {
            //flash a notification
            Session::flash('error_message', 'Bracket already exists.');
            
            return redirect()->back();
        }
        else
        {
            $bracket = Bracket::find($id);

            $bracket->display_name = $request->name;                  
            
            $bracket->save();

            //flash a notification
            Session::flash('flash_message', 'Bracket: "'.$request->name.'" is updated successfully.');

            return redirect()->route('bracket.index');   
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $bracket = Bracket::find($id);
        
        $bracket->active = 0;
        $bracket->deleted =  1;
        
        $bracket->save();
        
        //flash a notification
        Session::flash('flash_message', 'Bracket: "'.$bracket->display_name.'"  deleted successfully.');
        
        return redirect()->route('bracket.index');
    }
}
