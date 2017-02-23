<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Input;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Player;
use App\Models\PlayerGameStats;
use App\Models\PlayerCareerStats;
use App\Models\Team;
use App\Models\League;

use Validator;
use Auth;
use Session;

class PlayerController extends Controller
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
    
    //Get all information from 'Player' table and load all in player.index page
    public function index()
    {
        $isSearch = "";
        
        $search = \Request::get('search');
        
        if(!empty($search))
        {
            $isSearch = "true";
        }
         
        $player = Player::with('team')
                         ->where('active', 1)
                         ->where('deleted', 0)
                         ->where('league_id',Session::get("selectedLeague"))
                         ->where(function ($query) {
                                $query->where('first_name','LIKE','%'.\Request::get('search').'%')
                                      ->orWhere('last_name','LIKE','%'.\Request::get('search').'%');
                        })  
                         ->orderBy('last_name', 'ASC')                
                         ->get();
        
        $finalPlayer = [];
        $letterList= [];
         
        foreach($player as $key => $letter)
        {
            $var = $player[$key]->last_name[0];
            $var2 = $letter;
            
            if($var2->team->deleted == 0 && $var2->team->active == 1)
            {
                if(ctype_alpha($var))
                {
                    $letterList[] = $var;
                }
            }
        }
                 
        foreach($player as $plyr)
        {
            $var = $plyr;
                        
            if($var->team->deleted == 0 && $var->team->active == 1)
            {
                $finalPlayer[] = $var;
            }           
        }   
        
        $activeLetters = array_values(array_unique($letterList));
        
         
        return view ('pages.player.index', ['player' => $finalPlayer, 'activeLetters' => $activeLetters,'searchValue' => $search, 'isSearch' => $isSearch]);
    }
    
    //Go to the create page to create new player
    public function create()
    {
        $team = Team::find(Session::get("selectedTeam"));
        
        $league = League::find(Session::get("selectedLeague"));
        
        return view ('pages.player.create', ['team' => $team,'league' => $league]);
    }
    
    //store new player in database
    public function store(Request $request)
    {      
        $league = League::find(Session::get("selectedLeague"));
        
        if($league->hasPhotos == 1)
        {
            //validate fields
            $this->validate($request, [
                'firstname' => 'required', 
                'lastname' => 'required',            
                'position' => 'required',
            ]);

            $player = new Player();

            $slug = \Str::slug($request->number.' '.$request->firstname.' '.$request->lastname);

            $sameSlugCount = Player::whereRaw("slug RLIKE '^{$slug}(-[0-9]+)?$'")->count();

            if($sameSlugCount == 0)
            {
                $player->slug = \Str::slug($request->number.' '.$request->firstname.' '.$request->lastname); 
            }
            //if there is one or more slug with the same
            else
            {
                $finalSluggable = $request->number.' '.$request->firstname.' '.$request->lastname.' '.$sameSlugCount;

                $player->slug = \Str::slug($finalSluggable);  
            }

            $player->league_id = $request->league;
            $player->team_id = Session::get("selectedTeam");
            $player->first_name = $request->firstname;
            $player->middle_name = $request->middle;
            $player->last_name = $request->lastname;
            $player->jersey_number = $request->number;
            $player->position = $request->position;
            $player->age = $request->age;
            $player->height = $request->height;
            $player->weight = $request->weight;
            $player->pts = 0;
            $player->reb = 0;
            $player->ast = 0;
            $player->stl = 0;
            $player->blk = 0;
            $player->fgm = 0;
            $player->fga = 0;
            $player->pm3 = 0;
            $player->pa3 = 0;
            $player->ftm = 0;
            $player->fta = 0;
            $player->oreb = 0;
            $player->dreb = 0;
            $player->created_date = date("y-m-d h:i:s");
            $player->active = 1;
            $player->deleted = 0;

            $player->save();

            //player career stats
            $plyrCareerStats = new PlayerCareerStats();

            $plyrCareerStats->player_id = $player->player_id;       

            $plyrCareerStats->save();

            //get team
            $getTeam = Team::where('team_id',Session::get("selectedTeam"))
                            ->get();

            //IMAGE upload
            $url = 'http://www.passionsportsph.com/images/player';        

            if($request->hasFile('images')) 
            {
                $files = Input::file('images');
                $name = $files->getClientOriginalName();
                $extension = Input::file('images')->getClientOriginalExtension();
                $size = getImageSize($files);
                $fileExts = array('jpg','jpeg','png','gif','bmp');

                $filePath = public_path().'/images/player/'.$player->player_id;
                $files->move($filePath, $name);             

                $image = [];
                $image['imagePath'] = $url.'/'.$player->player_id.'/'.$name;

                $player->photo = $image['imagePath'];
                $player->save();
            }
            else
            {
                $player->photo = 'http://www.passionsportsph.com/images/player-default.jpg';
                    $player->save();

            }

            //flash a notification
            Session::flash('flash_message', 'Player: "'.$request->firstname.' '.$request->middle.' '.$request->lastname.'" is added successfully.');

            return redirect()->route('team.show', $getTeam[0]->slug);
        }
        
        //if league has no photos to show
        else
        {         
            
            foreach($request->firstname as $key => $manlalaro)
            {
                $player = new Player();

                $slug = \Str::slug($request->number[$key].' '.$request->firstname[$key].' '.$request->lastname[$key]);

                $sameSlugCount = Player::whereRaw("slug RLIKE '^{$slug}(-[0-9]+)?$'")->count();

                if($sameSlugCount == 0)
                {
                    $player->slug = \Str::slug($request->number[$key].' '.$request->firstname[$key].' '.$request->lastname[$key]); 
                }
                //if there is one or more slug with the same
                else
                {
                    $finalSluggable = $request->number[$key].' '.$request->firstname[$key].' '.$request->lastname[$key].' '.$sameSlugCount;

                    $player->slug = \Str::slug($finalSluggable);  
                }

                $player->league_id = $request->league;
                $player->team_id = Session::get("selectedTeam");
                $player->first_name = $request->firstname[$key];
                $player->middle_name = $request->middle[$key];
                $player->last_name = $request->lastname[$key];
                $player->jersey_number = $request->number[$key];
                $player->position = $request->position[$key];
                $player->age = $request->age[$key];
                $player->height = $request->height[$key];
                $player->weight = $request->weight[$key];
                $player->pts = 0;
                $player->reb = 0;
                $player->ast = 0;
                $player->stl = 0;
                $player->blk = 0;
                $player->fgm = 0;
                $player->fga = 0;
                $player->pm3 = 0;
                $player->pa3 = 0;
                $player->ftm = 0;
                $player->fta = 0;
                $player->oreb = 0;
                $player->dreb = 0;
                $player->created_date = date("y-m-d h:i:s");
                $player->active = 1;
                $player->deleted = 0;

                $player->save();

                //player career stats
                $plyrCareerStats = new PlayerCareerStats();

                $plyrCareerStats->player_id = $player->player_id;       

                $plyrCareerStats->save();
                
            }
            
            //get team
            $getTeam = Team::where('team_id',Session::get("selectedTeam"))
                            ->get();

            //flash a notification
            Session::flash('flash_message', 'Players are added successfully.');

            return redirect()->route('team.show', $getTeam[0]->slug);
        }
    } 
    
    public function show($slug)
    {
        //$player = Player::find($id);

        //player details and stats with designated team
        $player = Player::with('team')
                        ->where('slug',$slug)
			->where('league_id',Session::get("selectedLeague"))
			->where('deleted',0)
			->where('active',1)
                        ->get();
        
        
	$getLeague = League::where('league_id',Session::get("selectedLeague"))
			   ->get();

	if(count($player ) == 0)
        {
            return view('errors.404');
        }


        $finalPlayer = [];
        
        foreach($player as $plyr)
        {
            $var = $plyr;
                        
            if($var->team->deleted == 0 && $var->team->active == 1)
            {
                $finalPlayer[] = $var;
            }           
        }    
        
        $playerGameStats = PlayerGameStats::with('game','game.hometeam','game.awayteam')
                                        ->where('player_id',$player[0]->player_id)
                                        ->get();
        
        $finalPlayerGameStats = [];
        
        foreach($playerGameStats as $finalStats)
        {
            $var = $finalStats;    
            if($var->game->league_id == Session::get("selectedLeague"))
            {	       
		if($var->game->isFinished == 1)
            	{
                	$finalPlayerGameStats[] = $var;
            	} 
	    }          
        }    
                
        //player career stats
        $plyrCareerStats = PlayerCareerStats::where('player_id',$player[0]->player_id)
                                            ->get();

       // return $plyrCareerStats;
        if(count($finalPlayer) > 0)
        {
            return view ('pages.player.view', [ 'player' => $finalPlayer[0], 'playerGameStats' => $finalPlayerGameStats,
                                                'careerStats' => $plyrCareerStats[0] ,'league' => $getLeague[0] ]);
        }
        else
        {
            return view ('errors.404');
        }
        
    }
    
    //go to edit page gets the id of the choosen data
    public function edit($id)
    {
        $player = Player::find($id);
        
        $league = League::find(Session::get("selectedLeague"));
        
        $team =  Team::where('active', 1)
                    ->where('deleted', 0)
                    //->where('league_id',Session::get("selectedLeague"))
                    ->get()
                    ->lists('display_name', 'team_id');
        
        return view('pages.player.edit', ['player' => $player, 'team' => $team,'league' => $league]);
    }
    
    //update the edited data
    
    public function update(Request $request, $id)
    {
        
        $league = League::find(Session::get("selectedLeague"));
        
        //validate fields
        $this->validate($request, [
            'firstname' => 'required', 
            'lastname' => 'required',
            'position' => 'required',
        ]);
        
        $player = Player::find($id);
        
        $slug = \Str::slug($request->number.' '.$request->firstname.' '.$request->lastname);

        $sameSlugCount = Player::where('player_id','!=',$id)
                                ->whereRaw("slug RLIKE '^{$slug}(-[0-9]+)?$'")->count();

        if($sameSlugCount == 0)
        {
            $player->slug = \Str::slug($request->number.' '.$request->firstname.' '.$request->lastname); 
        }
        //if there is one or more slug with the same
        else
        {
            $finalSluggable = $request->number.' '.$request->firstname.' '.$request->lastname.' '.$sameSlugCount;

            $player->slug = \Str::slug($finalSluggable);  
        }
        
        $player->league_id = $request->league;
        $player->first_name = $request->firstname;
        $player->middle_name = $request->middle;
        $player->last_name = $request->lastname;
        $player->jersey_number = $request->number;
        $player->position = $request->position;
        $player->age = $request->age;
        $player->height = $request->height;
        $player->weight = $request->weight;
        $player->pts = 0;
        $player->reb = 0;
        $player->ast = 0;
        $player->stl = 0;
        $player->blk = 0;
        $player->fgm = 0;
        $player->fga = 0;
        $player->pm3 = 0;
        $player->pa3 = 0;
        $player->ftm = 0;
        $player->fta = 0;
        $player->oreb = 0;
        $player->dreb = 0;
        $player->modified_date = date("y-m-d h:i:s");

        $player->save();
        
        //get team
        $getTeam = Team::where('team_id',Session::get("selectedTeam"))
                        ->get();
        
        if($league->hasPhotos == 1)
        {            
            //IMAGE upload
            $url = 'http://www.passionsportsph.com/images/player'; 	

            if($request->hasFile('images')) 
            {
                $files = Input::file('images');
                $name = $files->getClientOriginalName();
                $extension = Input::file('images')->getClientOriginalExtension();
                $size = getImageSize($files);
                $fileExts = array('jpg','jpeg','png','gif','bmp');

                $filePath = public_path().'/images/player/'.$player->player_id;
                $files->move($filePath, $name);             

                $image = [];
                $image['imagePath'] = $url.'/'.$player->player_id.'/'.$name;

                $player->photo = $image['imagePath'];
                $player->save();
            }        
        }
        
        //flash a notification
        Session::flash('flash_message', 'Player: "'.$request->firstname.' '.$request->middle.' '.$request->lastname.'" is updated successfully.');

        return redirect()->route('player.show', $player->slug);
        
    }
    
    //doesn't complete delete in database but doesn't show in the index page either
    public function destroy($id)
    {
        $player = Player::find($id);
        
        $team = Player::with('team')
            ->where('player_id', $id)
            ->get();
        
        $player->active = 0;
        $player->deleted = 1;
        
        $player->save();
        
        //flash a notification
        Session::flash('flash_message', 'Player: "'.$player->firstname.' '.$player->middle.' '.$player->lastname.'" is deleted successfully.');
        
        return redirect()->route('team.show', $team[0]['team']->slug);
    }
}
