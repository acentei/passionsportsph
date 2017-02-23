<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Input;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\League;
use App\Models\Team;
use App\Models\Player;
use App\Models\PlayerCareerStats;
use App\Models\Regulation;
use App\Models\Bracket;

use Validator;
use Auth;
use Session;

class LeagueController extends Controller
{
    /**
     *  Authenticate access
     */
    public function __construct()
	{  
	    $this->middleware('auth',['except' => ['index','show']]);	  
	    
	    if (Auth::check()){
			parent::__construct();
		}
	} 
    
    //Get all information from 'league' table and open the league.index page
    public function index()
    {
        //check user access level
        if(Auth::user())        
        {   
            if((Auth::user()->account_type == "Moderator") && (Auth::user()->handled_league != 0))
            {
                $league = League::where('league_id',Auth::user()->handled_league)
                                ->where("active", 1)
                                ->where("deleted", 0)
                                ->get();                
                           
                return redirect()->action('LeagueController@show',$league[0]->slug);
                               
            }
            else
            {            
                $league = League::where("active", 1)
                                ->where("deleted", 0)
                                ->get();

                Session::put("selectedLeague",'');

                return view('pages.league.index',['league' => $league]);
            }
        }
        else
        {            
            $league = League::where("active", 1)
                            ->where("deleted", 0)
                            ->get();
            
            Session::put("selectedLeague",'');
            
            return view('pages.league.index',['league' => $league]);
        }
    }
    
    //Create new League
    public function create()
    {
       //check user access level
        if(Auth::user())        
        {   
            if((Auth::user()->account_type == "Moderator") && (Auth::user()->handled_league != 0))
            {
                return view('errors.403');
            }
            else
            {
                return view ('pages.league.create');
            }
        }
        else
        {
            return view ('pages.league.create');
        }         
    }
    
    //Store new League
    public function store(Request $request)
    {
        //validate fields
        $this->validate($request, [
            'name' => 'required', 
            'images' => 'required|image'            
        ]);
        
        $samename = League::where('deleted',0)
                          ->where('active',1)
                          ->where('display_name',$request->name)
                          ->get();
        
        if(count($samename) != 0 )
        {
            //flash a notification
            Session::flash('error_message', 'League name already exists.');
            
            return redirect()->back();            
        }
        else
        {
            $league = new League;
            
            $slug = \Str::slug($request->name);

            $sameSlugCount = League::whereRaw("slug RLIKE '^{$slug}(-[0-9]+)?$'")->count();

            if($sameSlugCount == 0)
            {
                $league->slug = \Str::slug($request->name); 
            }
            //if there is one or more slug with the same
            else
            {
                $finalSluggable = $request->name.' '.$sameSlugCount;

                $league->slug = \Str::slug($finalSluggable);  
            }

            $league->display_name = $request->name;
            $league->created_by = "admin";
		
            if($request->hasBracket == 1)
            {		
                $league->hasBracket = 1; 
            }
            else
                $league->hasBracket = 0;
            
             if($request->hasPhoto == 1)
            {		
                $league->hasPhotos = 1; 
            }
            else
                $league->hasPhotos = 0;
            
	    //what stats to show
            
            //fgm
	    if($request->c_fgm == 1)
	    {		
		$league->isShowFgm = 1; 
	    }
	    else
	    	$league->isShowFgm = 0;

	    //fga
	    if($request->c_fga == 1)
	    {		
		$league->isShowFga = 1; 
	    }
	    else
	    	$league->isShowFga = 0;

	    //fgp
	    if($request->c_fgp == 1)
	    {		
		$league->isShowFgp = 1; 
	    }
	    else
	    	$league->isShowFgp = 0;

	    //3pm
	    if($request->c_pm3 == 1)
	    {		
		$league->isShow3pm = 1; 
	    }
	    else
	    	$league->isShow3pm = 0;
	    
	    //3pa
	    if($request->c_pa3 == 1)
	    {		
		$league->isShow3pa = 1; 
	    }
	    else
	    	$league->isShow3pa = 0;

	    //3pp
	    if($request->c_pp3 == 1)
	    {		
		$league->isShow3pp = 1; 
	    }
	    else
	    	$league->isShow3pp = 0;
	   
 	    //ftm
	    if($request->c_ftm == 1)
	    {		
		$league->isShowFtm = 1; 
	    }
	    else
	    	$league->isShowFtm = 0;

    	    //fta
	    if($request->c_fta == 1)
	    {		
		$league->isShowFta = 1; 
	    }
	    else
	    	$league->isShowFta = 0;

	
	    //ftp
	    if($request->c_ftp == 1)
	    {		
		$league->isShowFtp = 1; 
	    }
	    else
	    	$league->isShowFtp = 0;

	    //reb
	    if($request->c_reb == 1)
	    {		
		$league->isShowReb = 1; 
	    }
	    else
	    	$league->isShowReb = 0;

	    //oreb
	    if($request->c_oreb == 1)
	    {		
		$league->isShowOreb = 1; 
	    }
	    else
	    	$league->isShowOreb = 0;
	
	    //dreb
	    if($request->c_dreb == 1)
	    {		
		$league->isShowDreb = 1; 
	    }
	    else
	    	$league->isShowDreb = 0;

	    //stl
	    if($request->c_stl == 1)
	    {		
		$league->isShowStl = 1; 
	    }
	    else
	    	$league->isShowStl = 0;

	    //blk
	    if($request->c_blk == 1)
	    {		
		$league->isShowBlk = 1; 
	    }
	    else
	    	$league->isShowBlk = 0;

	    //ast
	    if($request->c_ast == 1)
	    {		
		$league->isShowAst = 1; 
	    }
	    else
	    	$league->isShowAst = 0;

	    //tov
	    if($request->c_tov == 1)
	    {		
		$league->isShowTov = 1; 
	    }
	    else
	    	$league->isShowTov = 0;

	
            $league->active =  1;
            $league->deleted = 0;

            $league->save();

            //IMAGE upload
	    $url = 'http://www.passionsportsph.com/images/league';

            if($request->hasFile('images')) 
            {
                $files = Input::file('images');
                $name = $files->getClientOriginalName();
                $extension = Input::file('images')->getClientOriginalExtension();
                $size = getImageSize($files);
                $fileExts = array('jpg','jpeg','png','gif','bmp');

                $filePath = public_path().'/images/league/'.$league->league_id;
                $files->move($filePath, $name);             

                $image = [];
                $image['imagePath'] = $url.'/'.$league->league_id.'/'.$name;

                $league->photo = $image['imagePath'];
                $league->save();
            }
            
            //create regulation for the league
            $regulation = new Regulation;
            
            $regulation->league_id = $league->league_id;
            $regulation->details = $request->details;
            
            $regulation->save();

            //flash a notification
            Session::flash('flash_message', '"'.$request->name.'" League successfully created.');
            
            return redirect()->route('league.index');
        }
        
    }
    
    //View all teams under this league
    public function show($slug)
    {        

        $league = League::where('slug',$slug)
                        ->where('deleted',0)
                        ->where('active',1)
                        ->get();
        
        $allBrackets = Bracket::where('league_id',$league[0]->league_id)
                          ->where('active',1)
                          ->where('deleted',0)
                          ->get();

	    if(count($league) == 0)
        {
            return view('errors.404');
        }
        
        //get bracket and teams under that bracket
        $teamBracket = Bracket::with('team')
                              ->where('league_id',$league[0]->league_id)
                              ->where('active',1)
                              ->where('deleted',0)
                              ->get();        
        
        //compute additionals for 
        
        //test computation
        $SPLeaders = Player::with('team')
                            ->where('league_id',$league[0]->league_id)
                            ->where('active',1)
                            ->where('deleted',0)
                            ->orderBy('statistical_points','DESC')                            
                            ->get();
        
        $finalSPLeaders = [];
        
        foreach($SPLeaders as $sp)
        {
            $var = $sp;
            
            if($var->team->deleted == 0 && $var->team->active == 1 )
            {
                if(count($finalSPLeaders) != 5)
                {
                    $finalSPLeaders[] = $var;
                }                
            }            
        }
        
        $DSPLeaders = Player::with('team')
                            ->where('league_id',$league[0]->league_id)
                            ->where('active',1)
                            ->where('deleted',0)
                            ->orderBy('defensive_statistical_points','DESC')                            
                            ->get();
        
        
        $finalDSPLeaders = [];
        
        foreach($DSPLeaders as $dsp)
        {
            $var = $dsp;
            
            if($var->team->deleted == 0 && $var->team->active == 1 )
            {
                if(count($finalDSPLeaders) != 5)
                {
                    $finalDSPLeaders[] = $var;
                }                
            }            
        }
        //end test
        
	//check user access level
        if(Auth::user())        
        {   
            if((Auth::user()->account_type == "Moderator") && (Auth::user()->handled_league != 0))
            {
                if(Auth::user()->handled_league != $league[0]->league_id)
                {
                    return view('errors.403');
                }
            }            
        }
                
        Session::put("selectedLeague",$league[0]->league_id);        
        
        $team = Team::where('league_id',$league[0]->league_id)
                    ->where('deleted',0)
                    ->where('active',1)
                    ->get();
        
        $leagueStanding = Team::where('league_id',$league[0]->league_id)
                            ->where('deleted',0)
                            ->where('active',1)
                            ->orderBy('wins','DESC')
                            ->orderBy('losses','ASC')
                            ->get();
    
        
        //highest value per stats
            //points
        $highestPts = PlayerCareerStats::where('deleted',0)
                            ->where('active',1)
                            //->where('league_id',$id)
                            ->orderBy('pts','DESC')
                            ->take(1)                            
                            ->get(array('pts'));  
        
            //rebounds
        $highestReb = Player::where('deleted',0)
                            ->where('active',1)
                            ->orderBy('reb','DESC')
                            ->take(1)
                            ->get(array('reb'));
        
            //assist
        $highestAst = Player::where('deleted',0)
                            ->where('active',1)
                            ->orderBy('ast','DESC')
                            ->take(1)
                            ->get(array('ast')); 
        
            //steal
        $highestStl = Player::where('deleted',0)
                            ->where('active',1)
                            ->orderBy('stl','DESC')
                            ->take(1)
                            ->get(array('stl'));
        
            //block
        $highestBlk = Player::where('deleted',0)
                            ->where('active',1)
                            ->orderBy('blk','DESC')
                            ->take(1)
                            ->get(array('blk')); 
        
        
        
        // -- GET LEADERS -- //
        //points leader
        $ptsLeader = PlayerCareerStats::with('player','player.team.league')
                            ->where('deleted',0)
                            ->where('active',1)             
                            ->orderBy('pts','DESC')                            
                            ->get();
        
        $finalPtsLeader = [];
        foreach($ptsLeader as $pts)
        {
            $var = $pts;
            
            if($var->player->team->league->league_id == $league[0]->league_id)
            {
                if ($var->player->team->deleted == 0)
                {
                    if ($var->player->deleted == 0)
                    {
                        $finalPtsLeader[] = $var;			
                    }
                }
            }
        }       
        
        //rebounds leader
        $rebLeader = PlayerCareerStats::with('player','player.team.league')
                            ->where('deleted',0)
                            ->where('active',1)
                            ->orderBy('reb','DESC')
                            ->get();
        
        $finalRebLeader = [];
        foreach($rebLeader as $reb)
        {
            $var = $reb;
            
            if($var->player->team->league->league_id == $league[0]->league_id)
            {
                 if ($var->player->team->deleted == 0)
                {
                    if ($var->player->deleted == 0)
                    {
	                    $finalRebLeader[] = $var;
                    }
                }
            }
        }
        
        //assist leader
        $astLeader = PlayerCareerStats::with('player','player.team.league')
                            ->where('deleted',0)
                            ->where('active',1)
                            ->orderBy('ast','DESC')
                            ->get();
        
        $finalAstLeader = [];
        foreach($astLeader as $ast)
        {
            $var = $ast;
            
            if($var->player->team->league->league_id == $league[0]->league_id)
            {
                if ($var->player->team->deleted == 0)
                {
                    if ($var->player->deleted == 0)
                    {
                        $finalAstLeader[] = $var;
                    }
                }
            }
        }
        
        //steal leader
        $stlLeader = PlayerCareerStats::with('player','player.team.league')
                            ->where('deleted',0)
                            ->where('active',1)
                            ->orderBy('stl','DESC')
                            ->get();
        
        $finalStlLeader = [];
	
        foreach($stlLeader as $stl)
        {
            $var = $stl;
            
            if($var->player->team->league->league_id == $league[0]->league_id)
            {
                if ($var->player->team->deleted == 0)
                {
                    if ($var->player->deleted == 0)
                    {
                        $finalStlLeader[] = $var;
                    }
                }
            }
        }
        
        //block leader
        $blkLeader = PlayerCareerStats::with('player','player.team.league')
                            ->where('deleted',0)
                            ->where('active',1)
                            ->orderBy('blk','DESC')
                            ->get();
        
        $finalBlkLeader = [];
        foreach($blkLeader as $blk)
        {
            $var = $blk;
            
            if($var->player->team->league->league_id == $league[0]->league_id)
            {
                if ($var->player->team->deleted == 0)
                {
                    if ($var->player->deleted == 0)
                    {
                        $finalBlkLeader[] = $var;
                    }
                }
            }
        }
        
        
        // -- LEAGUE REGULATION -- //
        
        $regulation = Regulation::where('league_id',$league[0]->league_id)
                                ->get();
    
        //return $finalBlkLeader;
        
        //return $leagueStanding;
        //return $regulation;
        
        //return $team;
        
        //return $finalRebLeader;
        //return $finalAstLeader;
                
        
        return view('pages.league.view', ['team' => $team,'league' => $league[0], 'leagueStanding' => $leagueStanding, 'regulation' => $regulation, 'finalPtsLeader' => $finalPtsLeader, 
                                        'finalRebLeader' => $finalRebLeader,'finalAstLeader' => $finalAstLeader,'finalStlLeader' => $finalStlLeader, 
                                        'finalBlkLeader' => $finalBlkLeader,'spLeader' => $finalSPLeaders,'dspLeader' => $finalDSPLeaders,
                                         'bracket' => $teamBracket,'allbracket' => $allBrackets]);        
       
    }
    
    //Edit the choosen League
    public function edit($id)
    {
        $league = League::find($id);
        
        $regulation = Regulation::where('league_id',$id)
                                    ->get();
    
        if(empty($league))
        {
            return view('errors.404');
        }
        
        //check user access level
        if(Auth::user())        
        {   
            if((Auth::user()->account_type == "Moderator") && (Auth::user()->handled_league != 0))
            {
                if(Auth::user()->handled_league != $league->league_id)
                {
                    return view('errors.403');
                }
                else
                {
                    return view('pages.league.edit', ['league' => $league, 'regulation' => $regulation]);
                }
            }
            else
            {
                return view('pages.league.edit', ['league' => $league, 'regulation' => $regulation]);
            }
        }   
    }
    
    //Update the edited League
    public function update(Request $request, $id)
    {       
        //validate fields
        $this->validate($request, [
            'name' => 'required', 
        ]);
        
        $samename = League::where('deleted',0)
                          ->where('active',1)
                          ->where('display_name',$request->name)
                          ->where('league_id','!=',$id)
                          ->get();
        
        if(count($samename) != 0 )
        {
            //flash a notification
            Session::flash('error_message', 'League name already exists.');
            
            return redirect()->back();            
        }
        else
        {       
            $league = League::find($id);
            
            $slug = \Str::slug($request->name);

            $sameSlugCount = League::where('league_id','!=',$id)
                                    ->whereRaw("slug RLIKE '^{$slug}(-[0-9]+)?$'")->count();

            if($sameSlugCount == 0)
            {
                $league->slug = \Str::slug($request->name); 
            }
            //if there is one or more slug with the same
            else
            {
                $finalSluggable = $request->name.' '.$sameSlugCount;

                $league->slug = \Str::slug($finalSluggable);  
            }
            
            $league->display_name = $request->name;
            $league->modified_date = date("y-m-d h:i:s");

	    //what stats to show
            
            //fgm
	    if($request->c_fgm == 1)
	    {		
		$league->isShowFgm = 1; 
	    }
	    else
	    	$league->isShowFgm = 0;

	    //fga
	    if($request->c_fga == 1)
	    {		
		$league->isShowFga = 1; 
	    }
	    else
	    	$league->isShowFga = 0;

	    //fgp
	    if($request->c_fgp == 1)
	    {		
		$league->isShowFgp = 1; 
	    }
	    else
	    	$league->isShowFgp = 0;

	    //3pm
	    if($request->c_pm3 == 1)
	    {		
		$league->isShow3pm = 1; 
	    }
	    else
	    	$league->isShow3pm = 0;
	    
	    //3pa
	    if($request->c_pa3 == 1)
	    {		
		$league->isShow3pa = 1; 
	    }
	    else
	    	$league->isShow3pa = 0;

	    //3pp
	    if($request->c_pp3 == 1)
	    {		
		$league->isShow3pp = 1; 
	    }
	    else
	    	$league->isShow3pp = 0;
	   
 	    //ftm
	    if($request->c_ftm == 1)
	    {		
		$league->isShowFtm = 1; 
	    }
	    else
	    	$league->isShowFtm = 0;

    	    //fta
	    if($request->c_fta == 1)
	    {		
		$league->isShowFta = 1; 
	    }
	    else
	    	$league->isShowFta = 0;

	
	    //ftp
	    if($request->c_ftp == 1)
	    {		
		$league->isShowFtp = 1; 
	    }
	    else
	    	$league->isShowFtp = 0;

	    //reb
	    if($request->c_reb == 1)
	    {		
		$league->isShowReb = 1; 
	    }
	    else
	    	$league->isShowReb = 0;

	    //oreb
	    if($request->c_oreb == 1)
	    {		
		$league->isShowOreb = 1; 
	    }
	    else
	    	$league->isShowOreb = 0;
	
	    //dreb
	    if($request->c_dreb == 1)
	    {		
		$league->isShowDreb = 1; 
	    }
	    else
	    	$league->isShowDreb = 0;

	    //stl
	    if($request->c_stl == 1)
	    {		
		$league->isShowStl = 1; 
	    }
	    else
	    	$league->isShowStl = 0;

	    //blk
	    if($request->c_blk == 1)
	    {		
		$league->isShowBlk = 1; 
	    }
	    else
	    	$league->isShowBlk = 0;

	    //ast
	    if($request->c_ast == 1)
	    {		
		$league->isShowAst = 1; 
	    }
	    else
	    	$league->isShowAst = 0;

	    //tov
	    if($request->c_tov == 1)
	    {		
		$league->isShowTov = 1; 
	    }
	    else
	    	$league->isShowTov = 0;

            $league->active =  1;
            $league->deleted = 0;

            $league->save();

            //IMAGE upload
            $url = 'http://www.passionsportsph.com/images/league';

            if($request->hasFile('images')) 
            {
                $files = Input::file('images');
                $name = $files->getClientOriginalName();
                $extension = Input::file('images')->getClientOriginalExtension();
                $size = getImageSize($files);
                $fileExts = array('jpg','jpeg','png','gif','bmp');

                $filePath = public_path().'/images/league/'.$league->league_id;
                $files->move($filePath, $name);             

                $image = [];
                $image['imagePath'] = $url.'/'.$league->league_id.'/'.$name;

                $league->photo = $image['imagePath'];
                $league->save();
            }
        
            //update regulation for the league
            $regulation = Regulation::where('league_id',$id)
                                    ->first();
            
            if(empty($regulation))
            {
                $reg = new Regulation;
                
                $reg->league_id = $id;
                $reg->details = $request->details;
            
                $reg->save();
            }
            else
            {
                $regulation->details = $request->details;
            
                $regulation->save();
            }
            
            //flash a notification
            Session::flash('flash_message', 'League: "'.$request->name.'"  updated successfully.');

            return redirect()->route('league.index');
        }
        
    }
    
    //Doesn't completely delete in the database but rather doesn't show on the website, but is still in the database
    public function destroy($id)
    {
        $league = League::find($id);
        
        $league->active = 0;
        $league->deleted =  1;
        
        $league->save();
        
        //flash a notification
        Session::flash('flash_message', 'League: "'.$league->display_name.'"  deleted successfully.');
        
        return redirect()->route('league.index');
    }
    
}
