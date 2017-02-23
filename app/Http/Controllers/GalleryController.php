<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

use App\Http\Requests;
use App\Models\Gallery;

use Session;
use Auth;

class GalleryController extends Controller
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
    
    public function index()
    {
        $img = Gallery::where('active', 1)
                        ->where('deleted', 0)
                        ->where("league_id",Session::get("selectedLeague"))
                        ->get();
        
        return view ('pages.gallery.index',['gallery' => $img]);
    }
    
    public function create()
    {
        return view ('pages.gallery.create');
    }
    
    public function store(Request $request)
    {       
        //IMAGE upload
        $url = 'http://www.passionsportsph.com/images/gallery';

        if($request->hasFile('images')) 
        {
            $files = Input::file('images');                      
	    
            foreach($files as $file)
            { 	
                $gallery = new Gallery;    
                
                $name = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $fileExts = array('jpg','jpeg','png','gif','bmp');

                $filePath = public_path().'/images/gallery/'.Session::get("selectedLeague");
                $file->move($filePath, $name);             

                $image = [];
                $image['imagePath'] = $url.'/'.Session::get("selectedLeague").'/'.$name;

                $gallery->photo = $image['imagePath'];
                $gallery->caption = $name;
                $gallery->league_id = Session::get("selectedLeague");
                $gallery->save();
            }
        }
        //flash a notification
        Session::flash('flash_message', 'Images uploaded successfully.');
        
        return redirect()->route('gallery.index');
    }
    
    public function destroy($id)
    {
        $gallery = Gallery::find($id);
        
        $gallery->active = 0;
        $gallery->deleted = 1;
        
        $gallery->save();
        
        //flash a notification
        Session::flash('flash_message', 'Image deleted successfully.');
        
        return redirect()->route('gallery.index');
    }
}
