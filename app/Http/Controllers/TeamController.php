<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Input;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\League;
use App\Models\Team;
use App\Models\Game;
use App\Models\Player;
use App\Models\PlayerCareerStats;
use App\Models\TeamGameStats;
use App\Models\Bracket;


use Validator;
use Auth;
use Session;

class TeamController extends Controller
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
    
    //Get information from the team table and display to the page
    public function index()
    {
        $league = League::find(Session::get("selectedLeague"));
         
        $team = Team::where('active', 1)
                    ->where('deleted', 0)
                    ->where('league_id',Session::get("selectedLeague"))                    
                    ->get(); 
        
        if(count($league) != 0)
        {    
            if($league->hasBracket == 1)
            {
                //get bracket and teams under that bracket
                $teamBracket = Bracket::with('team')
                                  ->where('league_id',$league->league_id)
                                  ->where('active',1)
                                  ->where('deleted',0)
                                  ->get();       
            }
            else
                $teamBracket = [];
        }
        else
            $teamBracket = [];
        
                 
        Session::put("selectedTeam",'');
                 
        return view ('pages.team.index', ['team' => $team,'league' => $league,'bracket' => $teamBracket]);
    }
    
    //Create new Team
    public function create()
    {       
        $league = League::find(Session::get("selectedLeague"));
        
        $bracket = Bracket::where('league_id',Session::get("selectedLeague"))
                          ->where('active',1)
                          ->where('deleted',0)
                          ->get()
                          ->lists("display_name",'bracket_id');
        
        return view ('pages.team.create', ['league' => $league, 'bracket' => $bracket]);
    }
    
    //Store new Team
    public function store(Request $request)
    {
        $league = League::find(Session::get("selectedLeague"));
        
        if($league->hasPhotos == 1)
        {
            //validate fields
            $this->validate($request, [
                'name' => 'required', 
                'nickname' => 'required|min:3|max:3',
                'images' => 'required|image'
            ]);

            //sameteam and same nickname
            $samename = Team::where('league_id',Session::get("selectedLeague"))    
                                ->where('active',1)
                                ->where('deleted',0)
                                ->where('display_name',$request->name)
                                ->get();

            $samenickname = Team::where('league_id',Session::get("selectedLeague"))    
                                ->where('active',1)
                                ->where('deleted',0)
                                ->where('nickname',$request->nickname)
                                ->get();

            if(count($samename) != 0)
            {
                //flash a notification
                Session::flash('error_message', 'Team name already exists.');

                return redirect()->back();
            }
            elseif(count($samenickname) != 0)
            {
                //flash a notification
                Session::flash('error_message', 'Team nickname already exists.');

                return redirect()->back();
            }
            else
            {
                $team = new Team;

                $teamLeague = League::where('league_id',Session::get("selectedLeague"))
                                    ->get();

                //create a slug from title
                $slug = \Str::slug($request->name);

                $sameSlugCount = Team::whereRaw("slug RLIKE '^{$slug}(-[0-9]+)?$'")->count();

                if($sameSlugCount == 0)
                {
                    $team->slug = \Str::slug($request->name); 
                }
                //if there is one or more slug with the same
                else
                {
                    $finalSluggable = $request->name.' '.$sameSlugCount;

                    $team->slug = \Str::slug($finalSluggable);  
                }

                $team->league_id = Session::get("selectedLeague");
                $team->display_name = $request->name;
                $team->nickname = $request->nickname;
                $team->created_by = "admin";
                $team->active =  1;
                $team->deleted = 0;

                if($request->bracket != 0)
                {
                    $team->bracket_id = $request->bracket;
                }

                $team->save();

                //IMAGE upload
                $url = 'http://www.passionsportsph.com/images/team';

                if($request->hasFile('images')) 
                {
                    $files = Input::file('images');
                    $name = $files->getClientOriginalName();
                    $extension = Input::file('images')->getClientOriginalExtension();
                    $size = getImageSize($files);
                    $fileExts = array('jpg','jpeg','png','gif','bmp');

                    $filePath = public_path().'/images/team/'.$team->team_id;
                    $files->move($filePath, $name);             

                    $image = [];
                    $image['imagePath'] = $url.'/'.$team->team_id.'/'.$name;

                    $team->photo = $image['imagePath'];
                    $team->save();
                }

                //flash a notification
                Session::flash('flash_message', 'Team: "'.$request->name.'" is successfully created.');

                return redirect()->route('league.show', $teamLeague[0]->slug);
            }
        }
        //if league has no photos to show
        else
        {
            foreach($request->name as $key => $teams)
            {
                $team = new Team;

                $teamLeague = League::where('league_id',Session::get("selectedLeague"))
                                    ->get();

                //create a slug from title
                $slug = \Str::slug($request->name[$key]);

                $sameSlugCount = Team::whereRaw("slug RLIKE '^{$slug}(-[0-9]+)?$'")->count();

                if($sameSlugCount == 0)
                {
                    $team->slug = \Str::slug($request->name[$key]); 
                }
                //if there is one or more slug with the same
                else
                {
                    $finalSluggable = $request->name[$key].' '.$sameSlugCount;

                    $team->slug = \Str::slug($finalSluggable);  
                }

                $team->league_id = Session::get("selectedLeague");
                $team->display_name = $request->name[$key];
                $team->nickname = $request->nickname[$key];
                $team->created_by = "admin";
                $team->active =  1;
                $team->deleted = 0;

                if($request->bracket != 0)
                {
                    $team->bracket_id = $request->bracket[$key];
                }

                $team->save();
            }
            
            //flash a notification
            Session::flash('flash_message', 'Teams are successfully created.');

            return redirect()->route('league.show', $league->slug);
        }
    }
    
    //View page where all player under this team
    public function show($slug)
    {        
        $getTeam = Team::where('slug',$slug)
			->where('league_id',Session::get("selectedLeague"))
			->where('deleted',0)
			->where('active',1)
                    	->get();
        
        if(count($getTeam) == 0)
        {
            return view('errors.404');
        }

        Session::put("selectedTeam",$getTeam[0]->team_id);
        
        //team details and its players
        $rawTeam = Team::with('players','bracket')
                    ->where('team_id',$getTeam[0]->team_id)
                    ->get();
        
        $team = [];
        
        foreach($rawTeam as $raw)
        {
            $var = $raw;
            
            foreach($var->players as $player)
            {
                $var = $player;
                
                if($var->deleted == 0)
                {
                    $team[] = $var;
                }
            }  
            
        }
        
       //return $team;
    
        
        //team schedule details
        $teamSchedule = Game::with('venue','hometeam','awayteam')
                            ->where('hometeam_id',$getTeam[0]->team_id)
                            ->orWhere('awayteam_id',$getTeam[0]->team_id)
                            ->get();
        
        //team game stats
        $teamGameStats = TeamGameStats::with('game')
                                    ->where('team_id',$getTeam[0]->team_id)
                                    ->get();        
        
	   $finalTeamGameStats = [];
        
        foreach($teamGameStats as $finalStats)
        {
            $var = $finalStats;    
            if($var->game->league_id == Session::get("selectedLeague"))
            {	       
		if($var->game->isFinished == 1)
            	{
                	$finalTeamGameStats [] = $var;
            	} 
	    }          
        }  

        //team point leader
        $pointLeader = PlayerCareerStats::with('player','player.team')
                                        ->orderBy('pts','DESC')            
                                        ->get();
        
        $finalPtsLeader = [];
        foreach($pointLeader as $pts)
        {
            $var = $pts;
            
            if($var->player->team->team_id == $getTeam[0]->team_id)
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
        
        //team rebound leader
        $rebLeader = PlayerCareerStats::with('player','player.team')
                                    ->orderBy('reb','DESC')            
                                    ->get();
        
        $finalRebLeader = [];
        foreach($rebLeader as $reb)
        {
            $var = $reb;
            
            if($var->player->team->team_id == $getTeam[0]->team_id)
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
        
        //team assist leader
        $astLeader = PlayerCareerStats::with('player','player.team')
                                    ->orderBy('ast','DESC')            
                                    ->get();
        
        $finalAstLeader = [];
        foreach($astLeader as $ast)
        {
            $var = $ast;
            
            if($var->player->team->team_id == $getTeam[0]->team_id)
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
        
        //team steal leader
        $stlLeader = PlayerCareerStats::with('player','player.team')
                            ->orderBy('stl','DESC')            
                            ->get();
        
        $finalStlLeader = [];
        foreach($stlLeader as $stl)
        {
            $var = $stl;
            
            if($var->player->team->team_id == $getTeam[0]->team_id)
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
        
        //team block leader
        $blkLeader = PlayerCareerStats::with('player','player.team')
                            ->orderBy('blk','DESC')            
                            ->get();
        
        $finalBlkLeader = [];
        foreach($blkLeader as $blk)
        {
            $var = $blk;
            
            if($var->player->team->team_id == $getTeam[0]->team_id)
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
        
        $teamStanding = Team::where('slug', $slug)
            ->get();
    
        //return $pointLeader;
        /*
        $team = Team::find($id);
        
        
        
        $player = Player::where('deleted', 0)
            ->where('team_id', $id)
            ->get(); */
    
       //return $team;        

       $getLeague = League::where('league_id',Session::get("selectedLeague"))
			  ->get();
        
       return view('pages.team.view', ['team' => $team, 'rawTeam' => $rawTeam[0], 'pointLeader' => $finalPtsLeader, 
                                       'rebLeader' => $finalRebLeader, 'astLeader' => $finalAstLeader, 
                                       'stlLeader' => $finalStlLeader, 'blkLeader' => $finalBlkLeader,
                                       'teamGameStats' => $finalTeamGameStats, 'teamStanding' => $teamStanding[0],
				       'league' => $getLeague[0] ]);
       
    }
    
    //Edit the choosen Team
    public function edit($id)
    {
        $team = Team::find($id);      
                
        $league = League::find(Session::get("selectedLeague"));
        
        $bracket = Bracket::where('league_id',Session::get("selectedLeague"))
                          ->where('active',1)
                          ->where('deleted',0)
                          ->get()
                          ->lists("display_name",'bracket_id');
        
        return view('pages.team.edit', ['team' => $team, 'league' => $league,'bracket' => $bracket]);
    }
    
    //Update the edited Team
    public function update(Request $request, $id)
    {
         $league = League::find(Session::get("selectedLeague"));
        
        //validate fields
        $this->validate($request, [
            'name' => 'required', 
            'nickname' => 'required|min:3|max:3',
        ]);
        
        //sameteam and same nickname
        $samename = Team::where('league_id',Session::get("selectedLeague"))    
                            ->where('active',1)
                            ->where('deleted',0)                            
                            ->where('display_name',$request->name)
                            ->where('team_id','!=',$id)
                            ->get();
        
        $samenickname = Team::where('league_id',Session::get("selectedLeague"))    
                            ->where('active',1)
                            ->where('deleted',0)
                            ->where('nickname',$request->nickname)
                            ->where('team_id','!=',$id)
                            ->get();
        
        if(count($samename) != 0)
        {
            //flash a notification
            Session::flash('error_message', 'Team name already exists.');
            
            return redirect()->back();
        }
        elseif(count($samenickname) != 0)
        {
            //flash a notification
            Session::flash('error_message', 'Team nickname already exists.');
            
            return redirect()->back();
        }
        else
        {                    
            $team = Team::find($id);
            
            $slug = \Str::slug($request->name);

            $sameSlugCount = Team::where('team_id','!=',$id)
                                    ->whereRaw("slug RLIKE '^{$slug}(-[0-9]+)?$'")->count();

            if($sameSlugCount == 0)
            {
                $team->slug = \Str::slug($request->name); 
            }
            //if there is one or more slug with the same
            else
            {
                $finalSluggable = $request->name.' '.$sameSlugCount;

                $team->slug = \Str::slug($finalSluggable);  
            }
            
            $team->league_id = $request->league;
            $team->display_name = $request->name;
            $team->nickname = $request->nickname;
            $team->league_status = $request->status;
            $team->active =  1;
            $team->deleted = 0;
            
            if($request->bracket != 0)
            {
                $team->bracket_id = $request->bracket;
            }

            $team->save();
            
            $teamLeague = League::where('league_id',$request->league)
                                ->get();
            
            if($league->hasPhotos == 1)
            {            
                //IMAGE upload
                $url = 'http://www.passionsportsph.com/images/team';

                if($request->hasFile('images')) 
                {
                    $files = Input::file('images');
                    $name = $files->getClientOriginalName();
                    $extension = Input::file('images')->getClientOriginalExtension();
                    $size = getImageSize($files);
                    $fileExts = array('jpg','jpeg','png','gif','bmp');

                    $filePath = public_path().'/images/team/'.$team->team_id;
                    $files->move($filePath, $name);             

                    $image = [];
                    $image['imagePath'] = $url.'/'.$team->team_id.'/'.$name;

                    $team->photo = $image['imagePath'];
                    $team->save();
                }
            }

            //flash a notification
            Session::flash('flash_message', 'Team: "'.$request->name.'" is updated successfully.');

            return redirect()->route('team.show', $team->slug);        
        }
    }
    
    //Doesn't completely delete in the database but rather doesn't show on the website, but is still in the database
    public function destroy($id)
    {
        $team = Team::find($id);
        
        $team->active = 0;
        $team->deleted =  1;
        
        $team->save();
        
        $teamLeague = League::where('league_id',Session::get("selectedLeague"))
                            ->get();
        
        //flash a notification
        Session::flash('flash_message', 'A Team is deleted successfully.');
        
        return redirect()->route('league.show', $teamLeague[0]->slug);
    }
}
