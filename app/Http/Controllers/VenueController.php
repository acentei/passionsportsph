<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Venue;

use Validator;
use Auth;
use Session;

class VenueController extends Controller
{
    /**
     *  Authenticate access
     */
    public function __construct()
	{  
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
        $venue = Venue::where('deleted', 0)
            ->where('active', 1)
            ->get();
        
        return view ('pages.venue.index', ['venue' => $venue]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.venue.create');
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
            'name' => 'required|regex:/^[A-Za-z\s\.\'\!\-]+$/', 
            'address' => 'required|regex:/^[A-Za-z\s\.\0-9\']+$/',
        ]);
           
        $samename = Venue::where('deleted',0)
                         ->where('active',1)
                         ->where('display_name',$request->name)
                         ->get();
        
        if(count($samename) != 0)
        {
            //flash a notification
            Session::flash('error_message', 'Venue already exists.');
            
            return redirect()->back();
        }
        else
        {
            $venue = new Venue;

            $venue->display_name = $request->name;
            $venue->address = $request->address;
            $venue->active = 1;
            $venue->deleted = 0;

            $venue->save();

            //flash a notification
            Session::flash('flash_message', 'Venue: "'.$request->name.'" is successfully created.');

            return redirect()->route('venue.index');   
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
        $venue = Venue::find($id);
        
        return view('pages.venue.edit', ['venue' => $venue]);
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
            'name' => 'required|regex:/^[A-Za-z\s\.\'\!\-]+$/', 
            'address' => 'required|regex:/^[A-Za-z\s\.\0-9\']+$/',
        ]);
        
        $samename = Venue::where('deleted',0)
                         ->where('active',1)
                         ->where('display_name',$request->name)
                         ->where('venue_id','!=',$id)                        
                         ->get();        
        
        if(count($samename) != 0)
        {
            //flash a notification
            Session::flash('error_message', 'Venue already exists.');
            
            return redirect()->back();
        }
        else
        {        
            $venue = Venue::find($id);

            $venue->display_name = $request->name;
            $venue->address = $request->address;

            $venue->save();

            //flash a notification
            Session::flash('flash_message', 'Venue: "'.$request->name.'" is updated successfully.');  
            
            return redirect()->route('venue.index');
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
        $venue = Venue::find($id);
        
        $venue->active = 0;
        $venue->deleted = 1;

        $venue->save();
        
        //flash a notification
        Session::flash('flash_message', 'Venue is deleted successfully.');

        return redirect()->route('venue.index');
    }
}
