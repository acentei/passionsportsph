<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\User;
use App\Models\League;

use Auth;
use Session;
    

class ModeratorController extends Controller
{
    /**
     *  Authenticate access
     */
    public function __construct()
	{  
	    $this->middleware('auth');	  
	    $this->middleware('admin');	  
	    
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
        $mod = User::with('league')
                    ->where('account_type', 'Moderator')
                    ->where('deleted',0)
                    ->get();
        
        return view('pages.moderator.index',['moderator' => $mod]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
	$leagues = League::where('deleted',0)
                         ->where('active',1)
                         ->get()        
                         ->lists("display_name",'league_id');
               
        $leagues->prepend('All', '0');

        return view('pages.moderator.create',['league' => $leagues]);
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
            'username' => 'required', 
            'password' => 'required', 
            'first_name' => 'required',  
            'last_name' => 'required', 
        ]);

	$sameusername = User::where('username',$request->name)
		  	    ->where('deleted',0)
			    ->where('active',1)
			    ->get();

	if(count($sameusername) != 0)
	{
	    Session::flash('error_message', 'Username has already been taken.');
	}

        if ($request->password == $request->passconfirm)
        {
            $moderator = new User;

            $moderator->account_type = "Moderator";
            $moderator->username = $request->username;
            $moderator->email = $request->username;
	    $moderator->handled_league = $request->league;
            $moderator->password = bcrypt($request->password);
            $moderator->first_name = $request->first_name;
            $moderator->middle_name = $request->middle_name;
            $moderator->last_name = $request->last_name;

            $moderator->save();

            //flash a notification
            Session::flash('flash_message', 'Moderator successfully created.');

            return redirect()->route('moderator.index');
        }
        else
        {
            Session::flash('error_message', 'Password doesnt match.');
            
            return redirect()->back();
        }
    }
    
    public function activate($id)
    {
        $moderator = User::find($id);
        
        $moderator->active = 1;
        $moderator->save();
        
        return redirect()->route('moderator.index');
    }
    
    public function deactivate($id)
    {
        $moderator = User::find($id);
        
        $moderator->active = 0;
        $moderator->save();
        
        return redirect()->route('moderator.index');
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
        $moderator = User::find($id);

	$leagues = League::where('deleted',0)
                         ->where('active',1)
                         ->get()        
                         ->lists("display_name",'league_id');
               
        $leagues->prepend('All', '0');
        
        return view('pages.moderator.edit',['moderator' => $moderator,'league' => $leagues]);
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
            'username' => 'required', 
            'first_name' => 'required',  
            'last_name' => 'required', 
        ]);

	$sameusername = User::where('username',$request->name)
			    ->where('account_id',$id)
		  	    ->where('deleted',0)
			    ->where('active',1)
			    ->get();

	if(count($sameusername) != 0)
	{
	    Session::flash('error_message', 'Username has already been taken.');
	}
        
        if ($request->password == $request->passconfirm)
        {
            $moderator = User::find($id);

            $moderator->username = $request->username;
            $moderator->email = $request->username;
	    $moderator->handled_league = $request->league;
            if (!empty($request->password) || $request->password != "" || $request->password != null)
            {
                $moderator->password = bcrypt($request->password);
            }
            $moderator->first_name = $request->first_name;
            $moderator->middle_name = $request->middle_name;
            $moderator->last_name = $request->last_name;

            //flash a notification
            Session::flash('flash_message', 'Moderator updated successfully.');

            $moderator->save();

            return redirect()->route('moderator.index');
         }
        else
        {
            Session::flash('error_message', 'Password doesnt match.');
            
            return redirect()->back();
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
        $moderator = User::find($id);
        
        $moderator->active = 0;
                
        //flash a notification
        Session::flash('flash_message', 'Moderator deactivated successfully.');
        
        $moderator->save();
        
        return redirect()->route('moderator.index');
    }
}
