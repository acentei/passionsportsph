<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Models\Game;
use App\Models\Player;
use App\Models\TeamGameStats;
use App\Models\PlayerGameStats;
use App\Models\PlayerCareerStats;
use App\Models\Team;
use App\Models\Venue;
use App\Models\League;
use App\Models\Bracket;

use Validator;
use Auth;
use Session;

class GameController extends Controller
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
        $game = Game::with('hometeam','awayteam','stats','bracket')
                    ->where('active', 1)
                    ->where('deleted', 0)
                    ->orderBy('match_date','ASC')
                    ->orderBy('match_time','ASC')
                    ->where('league_id',Session::get("selectedLeague"))
                    ->get();       
        
        $league = League::find(Session::get("selectedLeague"));            
                
        return view ('pages.game.index', ['game' => $game,'league' => $league]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $league = League::find(Session::get("selectedLeague"));
        
        $team = Team::where('active', 1)
            ->where('deleted', 0)
            ->where('league_id',Session::get("selectedLeague"))
            //->where('league_status','Normal')
            ->get()
            ->lists('team_bracket', 'team_id');
        
        $venue = Venue::where('active', 1)
            ->where('deleted', 0)            
            ->get()
            ->lists('display_name', 'venue_id');
        
       
        return view('pages.game.create', ['team' => $team, 'venue' => $venue, 'league' => $league]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $league = League::find(Session::get("selectedLeague"));
        
        //validate fields
        $this->validate($request, [
            'venue' => 'required',                 
            'm_date' => 'required',
            'm_time' => 'required',

        ]); 
             
        if(!empty($request->hometeam) && !empty($request->awayteam))
        {
            if ($request->hometeam == $request->awayteam)
            {
                //flash a notification
                Session::flash('error_message', 'The match-up are same teams.');

                return redirect()->back();
            }

            else
            {

                $schedule = new Game;

                $hmtm = Team::where('team_id',$request->hometeam)
                            ->get();

                $awtm = Team::where('team_id',$request->awayteam)
                            ->get();


                //create a slug from title
                $toSlug = $hmtm[0]->nickname.'-VS-'.$awtm[0]->nickname.'-'. date("Y-m-d", strtotime($request->m_date));    

                $slug = \Str::slug($toSlug);

                $sameSlugCount = Game::whereRaw("slug RLIKE '^{$slug}(-[0-9]+)?$'")->count();

                if($sameSlugCount == 0)
                {
                    $schedule->slug = \Str::slug($toSlug); 
                }
                //if there is one or more slug with the same
                else
                {
                    $finalSluggable = $toSlug.' '.$sameSlugCount;

                    $schedule->slug = \Str::slug($finalSluggable);  
                }

                //parse time with AM or PM
                $mtime = $request->m_time.' '.$request->period;

                //convert to military time
                $matchTime = date("Y-m-d", strtotime($request->m_time)).' '.date("H:i:s", strtotime($mtime));    

                $schedule->league_id = $request->league;
                $schedule->venue_id = $request->venue;
                $schedule->hometeam_id = $request->hometeam;
                $schedule->awayteam_id = $request->awayteam;
                $schedule->match_date = date("Y-m-d", strtotime($request->m_date));
                $schedule->match_time = $matchTime;
                $schedule->active = 1;
                $schedule->deleted = 0;

                $schedule->save();     

                /** TEST FOR SAVING OR CREATING MULTIPLE VALUES IN A DATABASE TABLE **/

                $homePlayers = Player::where('team_id',$request->hometeam)
                                        ->get();

                $awayPlayers = Player::where('team_id',$request->awayteam)
                                        ->get();


                foreach($homePlayers as $key => $player)
                {
                    $playerGameStats = new PlayerGameStats;

                    $playerGameStats->game_id = $schedule->game_id;
                    $playerGameStats->player_id = $homePlayers[$key]->player_id;
                    $playerGameStats->pts = 0;
                    $playerGameStats->reb = 0;
                    $playerGameStats->ast = 0;
                    $playerGameStats->stl = 0;
                    $playerGameStats->blk = 0;
                    $playerGameStats->fgm = 0;
                    $playerGameStats->fga = 0;
                    $playerGameStats->pm3 = 0;
                    $playerGameStats->pa3 = 0;
                    $playerGameStats->ftm = 0;
                    $playerGameStats->fta = 0;
                    $playerGameStats->reb = 0;
                    $playerGameStats->oreb = 0;
                    $playerGameStats->dreb = 0;
                    $playerGameStats->tov = 0;
                    $playerGameStats->created_date = date("Y-m-d h:i:s");
                    $playerGameStats->active = 1;

                    $playerGameStats->save();
                }


                foreach($awayPlayers as $key => $player)
                {
                    $playerGameStats = new PlayerGameStats;

                    $playerGameStats->game_id = $schedule->game_id;
                    $playerGameStats->player_id = $awayPlayers[$key]->player_id;
                    $playerGameStats->pts = 0;
                    $playerGameStats->reb = 0;
                    $playerGameStats->ast = 0;
                    $playerGameStats->stl = 0;
                    $playerGameStats->blk = 0;
                    $playerGameStats->fgm = 0;
                    $playerGameStats->fga = 0;
                    $playerGameStats->pm3 = 0;
                    $playerGameStats->pa3 = 0;
                    $playerGameStats->ftm = 0;
                    $playerGameStats->fta = 0;
                    $playerGameStats->reb = 0;
                    $playerGameStats->oreb = 0;
                    $playerGameStats->dreb = 0;
                    $playerGameStats->tov = 0;
                    $playerGameStats->created_date = date("Y-m-d h:i:s");
                    $playerGameStats->active = 1;

                    $playerGameStats->save();
                }


                /* SETUP TEAMSTATS */

                $homeGameScore = new TeamGameStats;

                $homeGameScore->game_id = $schedule->game_id;
                $homeGameScore->team_id = $request->hometeam;
                $homeGameScore->pts = 0;
                $homeGameScore->reb = 0;
                $homeGameScore->ast = 0;
                $homeGameScore->stl = 0;
                $homeGameScore->blk = 0;
                $homeGameScore->fgm = 0;
                $homeGameScore->fga = 0;
                $homeGameScore->pm3 = 0;
                $homeGameScore->pa3 = 0;
                $homeGameScore->ftm = 0;
                $homeGameScore->fta = 0;
                $homeGameScore->reb = 0;
                $homeGameScore->oreb = 0;
                $homeGameScore->dreb = 0;
                $homeGameScore->tov = 0;
                $homeGameScore->Q1_score = 0;
                $homeGameScore->Q2_score = 0;
                $homeGameScore->Q3_score = 0;
                $homeGameScore->Q4_score = 0;
                $homeGameScore->OT1_score = 0;
                $homeGameScore->OT2_score = 0;
                $homeGameScore->OT3_score = 0;
                $homeGameScore->OT4_score = 0;
                $homeGameScore->OT5_score = 0;
                $homeGameScore->total_score = 0;
                $homeGameScore->created_date = date("Y-m-d h:i:s");
                $homeGameScore->active = 1;
                $homeGameScore->save();

                $awayGameScore = new TeamGameStats;

                $awayGameScore->game_id = $schedule->game_id;
                $awayGameScore->team_id = $request->awayteam;
                $awayGameScore->pts = 0;
                $awayGameScore->reb = 0;
                $awayGameScore->ast = 0;
                $awayGameScore->stl = 0;
                $awayGameScore->blk = 0;
                $awayGameScore->fgm = 0;
                $awayGameScore->fga = 0;
                $awayGameScore->pm3 = 0;
                $awayGameScore->pa3 = 0;
                $awayGameScore->ftm = 0;
                $awayGameScore->fta = 0;
                $awayGameScore->reb = 0;
                $awayGameScore->oreb = 0;
                $awayGameScore->dreb = 0;
                $awayGameScore->tov = 0;
                $awayGameScore->Q1_score = 0;
                $awayGameScore->Q2_score = 0;
                $awayGameScore->Q3_score = 0;
                $awayGameScore->Q4_score = 0;
                $awayGameScore->OT1_score = 0;
                $awayGameScore->OT2_score = 0;
                $awayGameScore->OT3_score = 0;
                $awayGameScore->OT4_score = 0;
                $awayGameScore->OT5_score = 0;
                $awayGameScore->total_score = 0;
                $awayGameScore->created_date = date("Y-m-d h:i:s");
                $awayGameScore->active = 1;
                $awayGameScore->save();

                //flash a notification
                Session::flash('flash_message', 'A Game Schedule is successfully created.');

                Session::put("activeBracket",'NULL');

                return redirect()->route('game.index');
            }
        }

        //if custom name is used
        elseif(!empty($request->custom_hometeam) && !empty($request->custom_awayteam))
        {
            $schedule = new Game;

            //create a slug from title
            $toSlug = $request->custom_hometeam.'-VS-'.$request->custom_awayteam.'-'. date("Y-m-d", strtotime($request->m_date));    

            $slug = \Str::slug($toSlug);

            $sameSlugCount = Game::whereRaw("slug RLIKE '^{$slug}(-[0-9]+)?$'")->count();

            if($sameSlugCount == 0)
            {
                $schedule->slug = \Str::slug($toSlug); 
            }
            //if there is one or more slug with the same
            else
            {
                $finalSluggable = $toSlug.' '.$sameSlugCount;

                $schedule->slug = \Str::slug($finalSluggable);  
            }

            //parse time with AM or PM
            $mtime = $request->m_time.' '.$request->period;

            //convert to military time
            $matchTime = date("Y-m-d", strtotime($request->m_time)).' '.date("H:i:s", strtotime($mtime));    

            $schedule->league_id = $request->league;
            $schedule->venue_id = $request->venue;
            $schedule->custom_hometeam = $request->custom_hometeam;
            $schedule->custom_awayteam = $request->custom_awayteam;
            $schedule->match_date = date("Y-m-d", strtotime($request->m_date));
            $schedule->match_time = $matchTime;
            $schedule->active = 1;
            $schedule->deleted = 0;

            $schedule->save();     

            //flash a notification
            Session::flash('flash_message', 'A Game Schedule is successfully created.');

            Session::put("activeBracket",'NULL');

            return redirect()->route('game.index');
        }
        
        else
        {
            $schedule = new Game;
            
            if(!empty($request->hometeam) && empty($request->awayteam))
            {
                if(empty($request->awayteam) && !empty($request->custom_awayteam))
                {
                    $hmtm = Team::where('team_id',$request->hometeam)
                            ->get();
                    
                    //create a slug from title
                    $toSlug = $hmtm[0]->nickname.'-VS-'.$request->custom_awayteam.'-'. date("Y-m-d", strtotime($request->m_date));  
                    
                    $schedule->hometeam_id = $request->hometeam;
                    $schedule->custom_awayteam = $request->custom_awayteam;
                }
            }
            elseif(empty($request->hometeam) && !empty($request->awayteam))
            {
                if(!empty($request->awayteam) && empty($request->custom_awayteam))
                {
                    $awtm = Team::where('team_id',$request->awayteam)
                            ->get();
                                        
                    //create a slug from title
                    $toSlug = $request->custom_hometeam.'-VS-'.$awtm[0]->nickname.'-'. date("Y-m-d", strtotime($request->m_date));  
                    
                    $schedule->custom_hometeam = $request->custom_hometeam;
                    $schedule->awayteam_id = $request->awayteam;
                }
            }             

            $slug = \Str::slug($toSlug);

            $sameSlugCount = Game::whereRaw("slug RLIKE '^{$slug}(-[0-9]+)?$'")->count();

            if($sameSlugCount == 0)
            {
                $schedule->slug = \Str::slug($toSlug); 
            }
            //if there is one or more slug with the same
            else
            {
                $finalSluggable = $toSlug.' '.$sameSlugCount;

                $schedule->slug = \Str::slug($finalSluggable);  
            }

            //parse time with AM or PM
            $mtime = $request->m_time.' '.$request->period;

            //convert to military time
            $matchTime = date("Y-m-d", strtotime($request->m_time)).' '.date("H:i:s", strtotime($mtime));    

            $schedule->league_id = $request->league;
            $schedule->venue_id = $request->venue;            
            $schedule->match_date = date("Y-m-d", strtotime($request->m_date));
            $schedule->match_time = $matchTime;
            $schedule->active = 1;
            $schedule->deleted = 0;

            $schedule->save();     

            //flash a notification
            Session::flash('flash_message', 'A Game Schedule is successfully created.');

            Session::put("activeBracket",'NULL');

            return redirect()->route('game.index');
        }
            

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        $league = League::find(Session::get("selectedLeague"));
        
        $now = date('Y-m-d H:i:s');
        
        $game = Game::with('league','bracket')
                    ->where('slug',$slug)
                    ->where('deleted',0)
                    ->where('active',1)
                    ->get();
        
        if(count($game) == 0)
        {
            return view('errors.404');
        }   
        
        if(($game[0]->hometeam_id == NULL) || ($game[0]->awayteam_id == NULL))
        {
            return view('pages.game.show-custom',['game' => $game[0], 'now' => $now , 'league' => $league]);
        }        
        
        //add stats for each players
        $homePlayers = Player::where('team_id',$game[0]['hometeam']->team_id)
                                ->get();

        $awayPlayers = Player::where('team_id',$game[0]['awayteam']->team_id)
                                ->get();    
        
        foreach($homePlayers as $key => $player)
            {
                $eachplaystats = PlayerGameStats::where('game_id',$game[0]->game_id)
                                                ->where('player_id',$homePlayers[$key]->player_id)
                                                ->get();
                if(count($eachplaystats) == 0)
                {
                    $playerGameStats = new PlayerGameStats;

                    $playerGameStats->game_id = $game[0]->game_id;
                    $playerGameStats->player_id = $homePlayers[$key]->player_id;
                    $playerGameStats->pts = 0;
                    $playerGameStats->reb = 0;
                    $playerGameStats->ast = 0;
                    $playerGameStats->stl = 0;
                    $playerGameStats->blk = 0;
                    $playerGameStats->fgm = 0;
                    $playerGameStats->fga = 0;
                    $playerGameStats->pm3 = 0;
                    $playerGameStats->pa3 = 0;
                    $playerGameStats->ftm = 0;
                    $playerGameStats->fta = 0;
                    $playerGameStats->reb = 0;
                    $playerGameStats->oreb = 0;
                    $playerGameStats->dreb = 0;
                    $playerGameStats->tov = 0;
                    $playerGameStats->created_date = date("Y-m-d h:i:s");
                    $playerGameStats->active = 1;

                    $playerGameStats->save();
                }
            }
            

            foreach($awayPlayers as $key => $player)
            {
                $eachplaystats = PlayerGameStats::where('game_id',$game[0]->game_id)
                                                ->where('player_id',$awayPlayers[$key]->player_id)
                                                ->get();
                if(count($eachplaystats) == 0)
                {
                    $playerGameStats = new PlayerGameStats;

                    $playerGameStats->game_id = $game[0]->game_id;
                    $playerGameStats->player_id = $awayPlayers[$key]->player_id;
                    $playerGameStats->pts = 0;
                    $playerGameStats->reb = 0;
                    $playerGameStats->ast = 0;
                    $playerGameStats->stl = 0;
                    $playerGameStats->blk = 0;
                    $playerGameStats->fgm = 0;
                    $playerGameStats->fga = 0;
                    $playerGameStats->pm3 = 0;
                    $playerGameStats->pa3 = 0;
                    $playerGameStats->ftm = 0;
                    $playerGameStats->fta = 0;
                    $playerGameStats->reb = 0;
                    $playerGameStats->oreb = 0;
                    $playerGameStats->dreb = 0;
                    $playerGameStats->tov = 0;
                    $playerGameStats->created_date = date("Y-m-d h:i:s");
                    $playerGameStats->active = 1;

                    $playerGameStats->save();
                }
            }
        
            /* SETUP TEAMSTATS */
        
            $hasHomeTeamStats = TeamGameStats::where('game_id',$game[0]->game_id)
                                                ->where('team_id',$game[0]['hometeam']->team_id)
                                                ->get();
            if(count($hasHomeTeamStats) == 0)
            {   
                $homeGameScore = new TeamGameStats;

                $homeGameScore->game_id = $game[0]->game_id;
                $homeGameScore->team_id = $game[0]['hometeam']->team_id;
                $homeGameScore->pts = 0;
                $homeGameScore->reb = 0;
                $homeGameScore->ast = 0;
                $homeGameScore->stl = 0;
                $homeGameScore->blk = 0;
                $homeGameScore->fgm = 0;
                $homeGameScore->fga = 0;
                $homeGameScore->pm3 = 0;
                $homeGameScore->pa3 = 0;
                $homeGameScore->ftm = 0;
                $homeGameScore->fta = 0;
                $homeGameScore->reb = 0;
                $homeGameScore->oreb = 0;
                $homeGameScore->dreb = 0;
                $homeGameScore->tov = 0;
                $homeGameScore->Q1_score = 0;
                $homeGameScore->Q2_score = 0;
                $homeGameScore->Q3_score = 0;
                $homeGameScore->Q4_score = 0;
                $homeGameScore->OT1_score = 0;
                $homeGameScore->OT2_score = 0;
                $homeGameScore->OT3_score = 0;
                $homeGameScore->OT4_score = 0;
                $homeGameScore->OT5_score = 0;
                $homeGameScore->total_score = 0;
                $homeGameScore->created_date = date("Y-m-d h:i:s");
                $homeGameScore->active = 1;
                $homeGameScore->save();
            }

            $hasAwayTeamStats = TeamGameStats::where('game_id',$game[0]->game_id)
                                                ->where('team_id',$game[0]['awayteam']->team_id)
                                                ->get();
            if(count($hasAwayTeamStats) == 0)
            {   
                $awayGameScore = new TeamGameStats;

                $awayGameScore->game_id = $game[0]->game_id;
                $awayGameScore->team_id = $game[0]['awayteam']->team_id;
                $awayGameScore->pts = 0;
                $awayGameScore->reb = 0;
                $awayGameScore->ast = 0;
                $awayGameScore->stl = 0;
                $awayGameScore->blk = 0;
                $awayGameScore->fgm = 0;
                $awayGameScore->fga = 0;
                $awayGameScore->pm3 = 0;
                $awayGameScore->pa3 = 0;
                $awayGameScore->ftm = 0;
                $awayGameScore->fta = 0;
                $awayGameScore->reb = 0;
                $awayGameScore->oreb = 0;
                $awayGameScore->dreb = 0;
                $awayGameScore->tov = 0;
                $awayGameScore->Q1_score = 0;
                $awayGameScore->Q2_score = 0;
                $awayGameScore->Q3_score = 0;
                $awayGameScore->Q4_score = 0;
                $awayGameScore->OT1_score = 0;
                $awayGameScore->OT2_score = 0;
                $awayGameScore->OT3_score = 0;
                $awayGameScore->OT4_score = 0;
                $awayGameScore->OT5_score = 0;
                $awayGameScore->total_score = 0;
                $awayGameScore->created_date = date("Y-m-d h:i:s");
                $awayGameScore->active = 1;
                $awayGameScore->save();
            }
        
        //game details and player details
        $gameDetails = Game::with('venue','hometeam','hometeam.players.gamestats',
                           'awayteam','awayteam.players.gamestats')
                            ->where('game_id',$game[0]->game_id);
        
        
        //away team stats details
        $awayTeamStats = TeamGameStats::with('team')
                                    ->where('game_id',$game[0]->game_id)
                                    ->where('team_id',$game[0]->awayteam_id)
                                    ->where('deleted', 0)
                                    ->get();
        
        $playerStats = PlayerGameStats::with('player')
                                    ->where('game_id',$game[0]->game_id)
                                    ->get();
        
        $awayPlayerStats = [];
        $homePlayerStats = [];
        
        
        foreach($playerStats as $stats)
        {
            $var = $stats;
        
            if($var->player->team_id == $game[0]->awayteam_id && $var->player->deleted == 0)
            {
                $awayPlayerStats[] = $var; 
            }
            else
            {
                if ($var->player->deleted == 0)
                {
                    $homePlayerStats[] = $var;
                }
            }
        }
        
        //home team stats details
        $homeTeamStats = TeamGameStats::with('team')
                                    ->where('game_id',$game[0]->game_id)
                                    ->where('team_id',$game[0]->hometeam_id)
                                    ->get();
       
        //return $homeTeamStats;
        
        //get point leaders for each team
        $PtsLeader = PlayerGameStats::with('player')
                                    ->where('game_id',$game[0]->game_id)   
                                    ->orderBy('pts','DESC')
                                    ->get();         
        $homePtsLeader = [];
        $awayPtsLeader = [];
        
        foreach($PtsLeader as $lead)
        {
            $var = $lead;
            
            if($var->player->team_id == $game[0]->hometeam_id)
            {
                $homePtsLeader[] = $var;
            }
            elseif($var->player->team_id == $game[0]->awayteam_id)
            {
                $awayPtsLeader[] = $var;  
            } 
        }
        
        
        //get rebounds leaders for each team
        $RebLeader = PlayerGameStats::with('player')
                                    ->where('game_id',$game[0]->game_id)   
                                    ->orderBy('reb','DESC')  
                                    ->get();         
        $homeRebLeader = [];
        $awayRebLeader = [];
        
        foreach($RebLeader as $lead)
        {
            $var = $lead;
            
            if($var->player->team_id == $game[0]->hometeam_id)
            {
                $homeRebLeader[] = $var;
            }
            elseif($var->player->team_id == $game[0]->awayteam_id)
            {
                $awayRebLeader[] = $var;  
            } 
        }
     
        
        ///get assists leaders for each team
        $AstLeader = PlayerGameStats::with('player')
                                    ->where('game_id',$game[0]->game_id)   
                                    ->orderBy('ast','DESC')  
                                    ->get();         
        $homeAstLeader = [];
        $awayAstLeader = [];
        
        foreach($AstLeader as $lead)
        {
            $var = $lead;
            
            if($var->player->team_id == $game[0]->hometeam_id)
            {
                $homeAstLeader[] = $var;
            }
            elseif($var->player->team_id == $game[0]->awayteam_id)
            {
                $awayAstLeader[] = $var;  
            } 
        }
               
        
        $getLeague = "";
        
	    $liga = League::where('league_id',Session::get("selectedLeague"))
			               ->get();	
        
        if(count($liga) == 0)
        {
            $getLeague = $game[0]['league'];
            Session::put("selectedLeague",$getLeague[0]->league_id);
            
        }
        else
        {
            $getLeague = $liga;
        }
	
        return view('pages.game.show', ['game' => $game[0], 'awayPlayerStats' => $awayPlayerStats, 'homePlayerStats' => $homePlayerStats, 
					'homeTeamStats' => $homeTeamStats, 'awayTeamStats' => $awayTeamStats,'homePtsLeader' => $homePtsLeader,
					'awayPtsLeader' => $awayPtsLeader,'homeRebLeader' => $homeRebLeader,'awayRebLeader' => $awayRebLeader,
					'homeAstLeader' => $homeAstLeader,'awayAstLeader' => $awayAstLeader,'now' => $now,'league' => $getLeague[0] ]);
            
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $game = Game::find($id);
        
        $league = League::find(Session::get("selectedLeague"));
        
        $team = Team::where('active', 1)
            ->where('deleted', 0)
            ->where('league_id',Session::get("selectedLeague"))
            ->where('league_status','Normal')
            ->get()
            ->lists('display_name', 'team_id');
        
        $venue = Venue::where('active', 1)
            ->where('deleted', 0)            
            ->get()
            ->lists('display_name', 'venue_id');
        
        $match_time = date('h:i:s', strtotime($game->match_time));       
                
        return view('pages.game.edit', ['team' => $team, 'venue' => $venue, 'game' => $game,'match_time' => $match_time]);
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
            'venue' => 'required',                 
            'm_date' => 'required',
            'm_time' => 'required',

        ]); 
             
        if(!empty($request->hometeam) && !empty($request->awayteam))
        {
            if ($request->hometeam == $request->awayteam)
            {
                //flash a notification
                Session::flash('error_message', 'The match-up are same teams.');

                return redirect()->back();
            }

            else
            {
                $schedule = Game::find($id);
            
                $hmtm = Team::where('team_id',$request->hometeam)
                            ->get();

                $awtm = Team::where('team_id',$request->awayteam)
                            ->get();


                //create a slug from title
                $toSlug = $hmtm[0]->nickname.'-VS-'.$awtm[0]->nickname.'-'. date("Y-m-d", strtotime($request->m_date));    

                $slug = \Str::slug($toSlug);

                $sameSlugCount = Game::where('game_id','!=',$id)
                                     ->whereRaw("slug RLIKE '^{$slug}(-[0-9]+)?$'")->count();

                if($sameSlugCount == 0)
                {
                    $schedule->slug = \Str::slug($toSlug); 
                }
                //if there is one or more slug with the same
                else
                {
                    $finalSluggable = $toSlug.' '.$sameSlugCount;

                    $schedule->slug = \Str::slug($finalSluggable);  
                }


                //parse time with AM or PM
                $mtime = $request->m_time.' '.$request->period;

                //convert to military time
                $matchTime = date("Y-m-d", strtotime($request->m_time)).' '.date("H:i:s", strtotime($mtime));    

                $schedule->league_id = $request->league;
                $schedule->venue_id = $request->venue;
                $schedule->hometeam_id = $request->hometeam;
                $schedule->awayteam_id = $request->awayteam;
                $schedule->match_date = date("Y-m-d", strtotime($request->m_date));
                $schedule->match_time = $matchTime;
                $schedule->active = 1;
                $schedule->deleted = 0;

                $schedule->save();

                //flash a notification
                Session::flash('flash_message', 'A Game Schedule is updated successfully.');

                return redirect()->route('game.index');
            }
        }

        //if custom name is used
        elseif(!empty($request->custom_hometeam) && !empty($request->custom_awayteam))
        {
            $schedule = Game::find($id);
            
            //create a slug from title
            $toSlug = $request->custom_hometeam.'-VS-'.$request->custom_awayteam.'-'. date("Y-m-d", strtotime($request->m_date));    

            $slug = \Str::slug($toSlug);

            $sameSlugCount = Game::whereRaw("slug RLIKE '^{$slug}(-[0-9]+)?$'")->count();

            if($sameSlugCount == 0)
            {
                $schedule->slug = \Str::slug($toSlug); 
            }
            //if there is one or more slug with the same
            else
            {
                $finalSluggable = $toSlug.' '.$sameSlugCount;

                $schedule->slug = \Str::slug($finalSluggable);  
            }

            //parse time with AM or PM
            $mtime = $request->m_time.' '.$request->period;

            //convert to military time
            $matchTime = date("Y-m-d", strtotime($request->m_time)).' '.date("H:i:s", strtotime($mtime));    

            $schedule->league_id = $request->league;
            $schedule->venue_id = $request->venue;
            $schedule->custom_hometeam = $request->custom_hometeam;
            $schedule->custom_awayteam = $request->custom_awayteam;
            $schedule->match_date = date("Y-m-d", strtotime($request->m_date));
            $schedule->match_time = $matchTime;
            $schedule->active = 1;
            $schedule->deleted = 0;

            $schedule->save();     

            //flash a notification
            Session::flash('flash_message', 'A Game Schedule is updated successfully.');

            return redirect()->route('game.index');
        }    
        
        else
        {
            $schedule = Game::find($id);
                                    
            if(!empty($request->hometeam) && empty($request->awayteam))
            {
                if(empty($request->awayteam) && !empty($request->custom_awayteam))
                {
                    $hmtm = Team::where('team_id',$request->hometeam)
                            ->get();
                    
                    //create a slug from title
                    $toSlug = $hmtm[0]->nickname.'-VS-'.$request->custom_awayteam.'-'. date("Y-m-d", strtotime($request->m_date));  
                    
                    $schedule->hometeam_id = $request->hometeam;
                    $schedule->custom_awayteam = $request->custom_awayteam;
                }
            }
            elseif(empty($request->hometeam) && !empty($request->awayteam))
            {
                if(!empty($request->awayteam) && empty($request->custom_awayteam))
                {
                    $awtm = Team::where('team_id',$request->awayteam)
                            ->get();
                                        
                    //create a slug from title
                    $toSlug = $request->custom_hometeam.'-VS-'.$awtm[0]->nickname.'-'. date("Y-m-d", strtotime($request->m_date));  
                    
                    $schedule->custom_hometeam = $request->custom_hometeam;
                    $schedule->awayteam_id = $request->awayteam;
                }
            }             

            $slug = \Str::slug($toSlug);

            $sameSlugCount = Game::whereRaw("slug RLIKE '^{$slug}(-[0-9]+)?$'")->count();

            if($sameSlugCount == 0)
            {
                $schedule->slug = \Str::slug($toSlug); 
            }
            //if there is one or more slug with the same
            else
            {
                $finalSluggable = $toSlug.' '.$sameSlugCount;

                $schedule->slug = \Str::slug($finalSluggable);  
            }

            //parse time with AM or PM
            $mtime = $request->m_time.' '.$request->period;

            //convert to military time
            $matchTime = date("Y-m-d", strtotime($request->m_time)).' '.date("H:i:s", strtotime($mtime));    

            $schedule->league_id = $request->league;
            $schedule->venue_id = $request->venue;            
            $schedule->match_date = date("Y-m-d", strtotime($request->m_date));
            $schedule->match_time = $matchTime;
            $schedule->active = 1;
            $schedule->deleted = 0;

            $schedule->save();     

            //flash a notification
            Session::flash('flash_message', 'A Game Schedule is successfully created.');

            Session::put("activeBracket",'NULL');

            return redirect()->route('game.index');
        }
    }
    
    public function editStats($id)
    {
        $game = Game::find($id);       
   
        $gameHomeScore = TeamGameStats::where('team_id', $game->hometeam_id)
            ->where('game_id', $id)
            ->get();
        
        $gameAwayScore = TeamGameStats::where('team_id', $game->awayteam_id)
            ->where('game_id', $id)
            ->get();
        
        //away team stats details
        $awayTeamStats = TeamGameStats::with('team')
                                    ->where('game_id',$id)
                                    ->where('team_id',$game->awayteam_id)
                                    ->get();
        
        $playerStats = PlayerGameStats::with('player')
                                    ->where('game_id',$id)
                                    ->get();
        
        $awayPlayerStats = [];
        $homePlayerStats = [];
        
        foreach($playerStats as $stats)
        {
            $var = $stats;
            
            if($var->player->team_id == $game->awayteam_id && $var->player->deleted == 0)
            {
                $awayPlayerStats[] = $var;
            }
            else
            {
                if ($var->player->deleted == 0)
                {
                    $homePlayerStats[] = $var;
                }
            }
        }

	   $getLeague = League::where('league_id',Session::get("selectedLeague"))
			   ->get();
        

        return view('pages.game.stats', ['game' => $game, 'homePlayerStats' => $homePlayerStats, 'awayPlayerStats' => $awayPlayerStats, 
					 'gameHomeScore' => $gameHomeScore[0], 'gameAwayScore' => $gameAwayScore[0], 'league' => $getLeague[0] ]);
    }
    
    public function updateStats(Request $request, $id)
    {
        $game = Game::find($id);
        
        $hmtmgmsts = array(
                            'pts' => '0',
                            'reb' => '0',
                            'ast' => '0',
                            'stl' => '0',
                            'blk' => '0',
                            'fgm' => '0',
                            'fga' => '0',
                            'pm3' => '0',
                            'pa3' => '0',
                            'ftm' => '0',
                            'fta' => '0',
                            'fta' => '0',
                            'oreb' => '0',
                            'dreb' => '0',
                            'tov' => '0',                           
                        );
        
         $awtmgmsts = array(
                            'pts' => '0',
                            'reb' => '0',
                            'ast' => '0',
                            'stl' => '0',
                            'blk' => '0',
                            'fgm' => '0',
                            'fga' => '0',
                            'pm3' => '0',
                            'pa3' => '0',
                            'ftm' => '0',
                            'fta' => '0',
                            'fta' => '0',
                            'oreb' => '0',
                            'dreb' => '0',
                            'tov' => '0',                           
                        );
    
        
       if ($request->hometeam == $request->awayteam)
            {
                return response("Same team", 405);
            }
        else
        {
            $homePlayers = Player::where('team_id',$request->hometeam)
                                    ->where('deleted', 0)
                                    ->get();
            
            $awayPlayers = Player::where('team_id',$request->awayteam)
                                    ->where('deleted', 0)
                                    ->get();

            foreach($homePlayers as $key => $player)
            {
                $playerGameStats = PlayerGameStats::find($request->homegamestats[$key]);                

                $playerGameStats->game_id = $id;
                $playerGameStats->player_id = $request->homeplayer[$key];
                $playerGameStats->pts = $request->pts[$key];
                $playerGameStats->reb = $request->reb[$key];
                $playerGameStats->ast = $request->ast[$key];
                $playerGameStats->stl = $request->stl[$key];
                $playerGameStats->blk = $request->blk[$key];
                $playerGameStats->fgm = $request->fgm[$key];
                $playerGameStats->fga = $request->fga[$key];
                $playerGameStats->pm3 = $request->pm3[$key];
                $playerGameStats->pa3 = $request->pa3[$key];
                $playerGameStats->ftm = $request->ftm[$key];
                $playerGameStats->fta = $request->fta[$key];
                $playerGameStats->oreb = $request->oreb[$key];
                $playerGameStats->dreb = $request->dreb[$key];
                $playerGameStats->tov = $request->tov[$key];
                $playerGameStats->modified_date = date("Y-m-d h:i:s");
                $playerGameStats->active = 1;
                
                $playerGameStats->save();
                                
                //add values to home team game stats total                
                $hmtmgmsts['pts'] = $hmtmgmsts['pts'] + $request->pts[$key];
                $hmtmgmsts['fgm'] = $hmtmgmsts['fgm'] + $request->fgm[$key];
                $hmtmgmsts['fga'] = $hmtmgmsts['fga'] + $request->fga[$key];
                $hmtmgmsts['pm3'] = $hmtmgmsts['pm3'] + $request->pm3[$key];
                $hmtmgmsts['pa3'] = $hmtmgmsts['pa3'] + $request->pa3[$key];
                $hmtmgmsts['ftm'] = $hmtmgmsts['ftm'] + $request->ftm[$key];
                $hmtmgmsts['fta'] = $hmtmgmsts['fta'] + $request->fta[$key];
                $hmtmgmsts['oreb'] = $hmtmgmsts['oreb'] + $request->oreb[$key];
                $hmtmgmsts['dreb'] = $hmtmgmsts['dreb'] + $request->dreb[$key];
                $hmtmgmsts['tov'] = $hmtmgmsts['tov'] + $request->tov[$key];
                $hmtmgmsts['reb'] = $hmtmgmsts['reb'] + $request->reb[$key];
                $hmtmgmsts['ast'] = $hmtmgmsts['ast'] + $request->ast[$key];
                $hmtmgmsts['stl'] = $hmtmgmsts['stl']  + $request->stl[$key];
                $hmtmgmsts['blk'] = $hmtmgmsts['blk'] + $request->blk[$key];
                
		$plyrGamesPlayed = [];

                $GamesPlayed = PlayerGameStats::with('game')
					      ->where('player_id', $request->homeplayer[$key])
                    			      ->get();
	
		foreach($GamesPlayed as $gm)
		{
		    $var = $gm;

		    if($var->game->isFinished == 1)
		    {
			$plyrGamesPlayed[] = $var;
		    }
		} 
                
                //player stats
                $plyrStatsSum = array(
                                        'pts' => 0,
                                        'reb' => 0,
                                        'ast' => 0,
                                        'stl' => 0,
                                        'blk' => 0,
                                        'fgm' => 0,
                                        'fga' => 0,
                                        'pm3' => 0,
                                        'pa3' => 0,
                                        'ftm' => 0,
                                        'fta' => 0,
                                        'oreb' => 0,
                                        'dreb' => 0,
                                        'tov' => 0,
                                        
                                    );  
                
                //update total stats
                $plyrSelected = Player::with('team')
                                      ->find($request->homeplayer[$key]);
                
                //total stats           
                for($i = 1;$i <= count($plyrGamesPlayed); $i++)
                {
                    $current = $i-1;
                    
                    //points
                    $plyrStatsSum['pts'] = $plyrStatsSum['pts'] + $plyrGamesPlayed[$current]->pts;
                                        
                    //fgm
                    $plyrStatsSum['fgm'] = $plyrStatsSum['fgm'] + $plyrGamesPlayed[$current]->fgm;
                    
                    //fga
                    $plyrStatsSum['fga'] = $plyrStatsSum['fga'] + $plyrGamesPlayed[$current]->fga;
                    
                    //pm3
                    $plyrStatsSum['pm3'] = $plyrStatsSum['pm3'] + $plyrGamesPlayed[$current]->pm3;
                    
                    //pa3
                    $plyrStatsSum['pa3'] = $plyrStatsSum['pa3'] + $plyrGamesPlayed[$current]->pa3;
                    
                    //ftm
                    $plyrStatsSum['ftm'] = $plyrStatsSum['ftm'] + $plyrGamesPlayed[$current]->ftm;
                    
                    //fta
                    $plyrStatsSum['fta'] = $plyrStatsSum['fta'] + $plyrGamesPlayed[$current]->fta;
                    
                    //oreb
                    $plyrStatsSum['oreb'] = $plyrStatsSum['oreb'] + $plyrGamesPlayed[$current]->oreb;
                    
                    //dreb
                    $plyrStatsSum['dreb'] = $plyrStatsSum['dreb'] + $plyrGamesPlayed[$current]->dreb;
                    
                    //tov
                    $plyrStatsSum['tov'] = $plyrStatsSum['tov'] + $plyrGamesPlayed[$current]->tov;
                    
                    //rebounds
                    $plyrStatsSum['reb'] = $plyrStatsSum['reb'] + $plyrGamesPlayed[$current]->reb;
                                        
                    //assists
                    $plyrStatsSum['ast'] = $plyrStatsSum['ast'] + $plyrGamesPlayed[$current]->ast;
                    
                    //steals
                    $plyrStatsSum['stl'] = $plyrStatsSum['stl'] + $plyrGamesPlayed[$current]->stl;
                    
                    //blocks
                    $plyrStatsSum['blk'] = $plyrStatsSum['blk'] + $plyrGamesPlayed[$current]->blk;
                    
                }
                
                $plyrSelected->pts = $plyrStatsSum['pts'];
                $plyrSelected->reb = $plyrStatsSum['reb'];
                $plyrSelected->ast = $plyrStatsSum['ast'];
                $plyrSelected->stl = $plyrStatsSum['stl'];
                $plyrSelected->blk = $plyrStatsSum['blk'];
                $plyrSelected->fgm = $plyrStatsSum['fgm'];
                $plyrSelected->fga = $plyrStatsSum['fga'];
                $plyrSelected->pm3 = $plyrStatsSum['pm3'];
                $plyrSelected->pa3 = $plyrStatsSum['pa3'];
                $plyrSelected->ftm = $plyrStatsSum['ftm'];
                $plyrSelected->fta = $plyrStatsSum['fta'];
                $plyrSelected->oreb = $plyrStatsSum['oreb'];
                $plyrSelected->dreb = $plyrStatsSum['dreb'];
                
                /*statistical and defensive statistical points*/
                $SP = ($plyrStatsSum['pts']) + ($plyrStatsSum['reb']*1.5) + ($plyrStatsSum['ast']*1.5) + ($plyrStatsSum['stl']*1.5) + ($plyrStatsSum['blk']*1.5) + ($plyrSelected['team']->wins * 10);
                $DSP = ($plyrStatsSum['dreb']) + ($plyrStatsSum['stl']*2) +  ($plyrStatsSum['blk']*2) + ($plyrSelected['team']->wins * 5);
                    
                $plyrSelected->statistical_points = number_format($SP,1);
                $plyrSelected->defensive_statistical_points = number_format($DSP,1);
                    
                $plyrSelected->save();
               
                                
                //update player career stats
                $plyr = PlayerCareerStats::where('player_id', $request->homeplayer[$key])
                                        ->get();
                
                $totalPlayerStats = array(
                                            'pts' => 0,
                                            'reb' => 0,
                                            'ast' => 0,
                                            'stl' => 0,
                                            'blk' => 0,
                                            'fgp' => 0,
                                            'ftp' => 0,
                                            'pm3' => 0,
                                            'ftm' => 0,
                                        );          
               
                
                //career stats           
                for($i = 1;$i <= count($plyrGamesPlayed); $i++)
                {
                    $current = $i-1;
                    
                    //points
                    $totalPlayerStats['pts'] = $totalPlayerStats['pts'] + $plyrGamesPlayed[$current]->pts;                                
                    
                    //rebounds
                    $totalPlayerStats['reb'] = $totalPlayerStats['reb'] + $plyrGamesPlayed[$current]->reb;
                    
                    //assists
                    $totalPlayerStats['ast'] = $totalPlayerStats['ast'] + $plyrGamesPlayed[$current]->ast;
                    
                    //assists
                    $totalPlayerStats['stl'] = $totalPlayerStats['stl'] + $plyrGamesPlayed[$current]->stl;
                    
                    //blocks
                    $totalPlayerStats['blk'] = $totalPlayerStats['blk'] + $plyrGamesPlayed[$current]->blk; 
                    
                    //3-pts made
                    $totalPlayerStats['pm3'] = $totalPlayerStats['pm3'] + $plyrGamesPlayed[$current]->pm3;
                    
                    //free throws made
                    $totalPlayerStats['ftm'] = $totalPlayerStats['ftm'] + $plyrGamesPlayed[$current]->ftm;
                    
                    //free throws %
                    if ($plyrGamesPlayed[$current]->ftm != 0 && $plyrGamesPlayed[$current]->fta != 0)
                    {                        
                        $totalPlayerStats['ftp'] = $totalPlayerStats['ftp'] + number_format(($plyrGamesPlayed[$current]->ftm / $plyrGamesPlayed[$current]->fta) * 100, 1);
                    }
                    else
                    {
                        $totalPlayerStats['ftp'] = $totalPlayerStats['ftp'] + 0;
                    }
                    
                    //field goals %
                    if ($plyrGamesPlayed[$current]->fgm != 0 && $plyrGamesPlayed[$current]->fga != 0)
                    { 
                        $totalPlayerStats['fgp'] = $totalPlayerStats['fgp'] + number_format(($plyrGamesPlayed[$current]->fgm / $plyrGamesPlayed[$current]->fga) * 100, 1);
                    }
                    else
                    {
                        $totalPlayerStats['fgp'] = $totalPlayerStats['fgp'] + 0;
                    }
                    
                    
                }
                
                if(count($plyrGamesPlayed) > 0)
                {
                    $plyr[0]->pts = number_format(($totalPlayerStats['pts']/count($plyrGamesPlayed)),1);
                    $plyr[0]->reb = number_format(($totalPlayerStats['reb']/count($plyrGamesPlayed)),1);  
                    $plyr[0]->ast = number_format(($totalPlayerStats['ast']/count($plyrGamesPlayed)),1);
                    $plyr[0]->stl = number_format(($totalPlayerStats['stl']/count($plyrGamesPlayed)),1);
                    $plyr[0]->blk = number_format(($totalPlayerStats['blk']/count($plyrGamesPlayed)),1);
                    $plyr[0]->fgp = number_format(($totalPlayerStats['fgp']/count($plyrGamesPlayed)),1);
                    $plyr[0]->ftp = number_format(($totalPlayerStats['ftp']/count($plyrGamesPlayed)),1);
                    $plyr[0]->pm3 = $totalPlayerStats['pm3'];
                    $plyr[0]->ftm = $totalPlayerStats['ftm'];
                    $plyr[0]->save();
                }
                else
                {
                    $plyr[0]->pts = number_format($totalPlayerStats['pts'],1);
                    $plyr[0]->reb = number_format($totalPlayerStats['reb'],1);  
                    $plyr[0]->ast = number_format($totalPlayerStats['ast'],1);
                    $plyr[0]->stl = number_format($totalPlayerStats['stl'],1);
                    $plyr[0]->blk = number_format($totalPlayerStats['blk'],1);
                    $plyr[0]->fgp = number_format($totalPlayerStats['fgp'],1);
                    $plyr[0]->ftp = number_format($totalPlayerStats['ftp'],1);
                    $plyr[0]->pm3 = $totalPlayerStats['pm3'];
                    $plyr[0]->ftm = $totalPlayerStats['ftm'];
                    $plyr[0]->save();
                }
                    
            }
            

            foreach($awayPlayers as $key => $player)
            {
                $playerGameStats = PlayerGameStats::find($request->awaygamestats[$key]);
                
                $playerGameStats->game_id = $id;
                $playerGameStats->player_id = $request->awayplayer[$key];
                $playerGameStats->pts = $request->awaypts[$key];
                $playerGameStats->reb = $request->awayreb[$key];
                $playerGameStats->ast = $request->awayast[$key];
                $playerGameStats->stl = $request->awaystl[$key];
                $playerGameStats->blk = $request->awayblk[$key];
                $playerGameStats->fgm = $request->awayfgm[$key];
                $playerGameStats->fga = $request->awayfga[$key];
                $playerGameStats->pm3 = $request->awaypm3[$key];
                $playerGameStats->pa3 = $request->awaypa3[$key];
                $playerGameStats->ftm = $request->awayftm[$key];
                $playerGameStats->fta = $request->awayfta[$key];
                $playerGameStats->oreb = $request->awayoreb[$key];
                $playerGameStats->dreb = $request->awaydreb[$key];
                $playerGameStats->tov = $request->awaytov[$key];
                $playerGameStats->modified_date = date("Y-m-d h:i:s");
                $playerGameStats->active = 1;
                
                $playerGameStats->save();
                
                //add to total team game stats
                $awtmgmsts['pts'] = $awtmgmsts['pts'] + $request->awaypts[$key];
                $awtmgmsts['fgm'] = $awtmgmsts['fgm'] + $request->awayfgm[$key];
                $awtmgmsts['fga'] = $awtmgmsts['fga'] + $request->awayfga[$key];
                $awtmgmsts['pm3'] = $awtmgmsts['pm3'] + $request->awaypm3[$key];
                $awtmgmsts['pa3'] = $awtmgmsts['pa3'] + $request->awaypa3[$key];
                $awtmgmsts['ftm'] = $awtmgmsts['ftm'] + $request->awayftm[$key];
                $awtmgmsts['fta'] = $awtmgmsts['fta'] + $request->awayfta[$key];
                $awtmgmsts['oreb'] = $awtmgmsts['oreb'] + $request->awayoreb[$key];
                $awtmgmsts['dreb'] = $awtmgmsts['dreb'] + $request->awaydreb[$key];
                $awtmgmsts['tov'] = $awtmgmsts['tov'] + $request->awaytov[$key];
                $awtmgmsts['reb'] = $awtmgmsts['reb'] + $request->awayreb[$key];
                $awtmgmsts['ast'] = $awtmgmsts['ast'] + $request->awayast[$key];
                $awtmgmsts['stl'] = $awtmgmsts['stl'] + $request->awaystl[$key];
                $awtmgmsts['blk'] = $awtmgmsts['blk'] + $request->awayblk[$key];
                
                
                $plyrGamesPlayed = [];

                $GamesPlayed = PlayerGameStats::with('game')
					      ->where('player_id', $request->awayplayer[$key])
                    			      ->get();
	
		foreach($GamesPlayed as $gm)
		{
		    $var = $gm;

		    if($var->game->isFinished == 1)
		    {
			$plyrGamesPlayed[] = $var;
		    }
		}
                                                                       
                
                //player stats
                $plyrStatsSum = array(
                                        'pts' => 0,
                                        'reb' => 0,
                                        'ast' => 0,
                                        'stl' => 0,
                                        'blk' => 0,
                                        'fgm' => 0,
                                        'fga' => 0,
                                        'pm3' => 0,
                                        'pa3' => 0,
                                        'ftm' => 0,
                                        'fta' => 0,
                                        'oreb' => 0,
                                        'dreb' => 0,
                                        'tov' => 0,
                                        
                                    );  
                
                //update total stats
                $plyrSelected = Player::with('team')
                                      ->find($request->awayplayer[$key]);
                
                //total stats           
                for($i = 1;$i <= count($plyrGamesPlayed); $i++)
                {
                    $current = $i-1;
                    
                    //points
                    $plyrStatsSum['pts'] = $plyrStatsSum['pts'] + $plyrGamesPlayed[$current]->pts;
                                        
                    //fgm
                    $plyrStatsSum['fgm'] = $plyrStatsSum['fgm'] + $plyrGamesPlayed[$current]->fgm;
                    
                    //fga
                    $plyrStatsSum['fga'] = $plyrStatsSum['fga'] + $plyrGamesPlayed[$current]->fga;
                    
                    //pm3
                    $plyrStatsSum['pm3'] = $plyrStatsSum['pm3'] + $plyrGamesPlayed[$current]->pm3;
                    
                    //pa3
                    $plyrStatsSum['pa3'] = $plyrStatsSum['pa3'] + $plyrGamesPlayed[$current]->pa3;
                    
                    //ftm
                    $plyrStatsSum['ftm'] = $plyrStatsSum['ftm'] + $plyrGamesPlayed[$current]->ftm;
                    
                    //fta
                    $plyrStatsSum['fta'] = $plyrStatsSum['fta'] + $plyrGamesPlayed[$current]->fta;
                    
                    //oreb
                    $plyrStatsSum['oreb'] = $plyrStatsSum['oreb'] + $plyrGamesPlayed[$current]->oreb;
                    
                    //dreb
                    $plyrStatsSum['dreb'] = $plyrStatsSum['dreb'] + $plyrGamesPlayed[$current]->dreb;
                    
                    //tov
                    $plyrStatsSum['tov'] = $plyrStatsSum['tov'] + $plyrGamesPlayed[$current]->tov;
                    
                    //rebounds
                    $plyrStatsSum['reb'] = $plyrStatsSum['reb'] + $plyrGamesPlayed[$current]->reb;
                                        
                    //assists
                    $plyrStatsSum['ast'] = $plyrStatsSum['ast'] + $plyrGamesPlayed[$current]->ast;
                    
                    //steals
                    $plyrStatsSum['stl'] = $plyrStatsSum['stl'] + $plyrGamesPlayed[$current]->stl;
                    
                    //blocks
                    $plyrStatsSum['blk'] = $plyrStatsSum['blk'] + $plyrGamesPlayed[$current]->blk;
                    
                }
                
                $plyrSelected->pts = $plyrStatsSum['pts'];
                $plyrSelected->reb = $plyrStatsSum['reb'];
                $plyrSelected->ast = $plyrStatsSum['ast'];
                $plyrSelected->stl = $plyrStatsSum['stl'];
                $plyrSelected->blk = $plyrStatsSum['blk'];
                $plyrSelected->fgm = $plyrStatsSum['fgm'];
                $plyrSelected->fga = $plyrStatsSum['fga'];
                $plyrSelected->pm3 = $plyrStatsSum['pm3'];
                $plyrSelected->pa3 = $plyrStatsSum['pa3'];
                $plyrSelected->ftm = $plyrStatsSum['ftm'];
                $plyrSelected->fta = $plyrStatsSum['fta'];
                $plyrSelected->oreb = $plyrStatsSum['oreb'];
                $plyrSelected->dreb = $plyrStatsSum['dreb'];
                
                /*statistical and defensive statistical points*/
                $SP = ($plyrStatsSum['pts']) + ($plyrStatsSum['reb']*1.5) + ($plyrStatsSum['ast']*1.5) + ($plyrStatsSum['stl']*1.5) + ($plyrStatsSum['blk']*1.5) + ($plyrSelected['team']->wins * 10);
                $DSP = ($plyrStatsSum['dreb']) + ($plyrStatsSum['stl']*2) +  ($plyrStatsSum['blk']*2) + ($plyrSelected['team']->wins * 5);
                    
                $plyrSelected->statistical_points = number_format($SP,1);
                $plyrSelected->defensive_statistical_points = number_format($DSP,1);
                    
                $plyrSelected->save();
                
                $plyrSelected->save();
                
                //update player career stats
                $plyr = PlayerCareerStats::where('player_id', $request->awayplayer[$key])
                                     ->get();
                
                $totalPlayerStats = array(
                                            'pts' => 0,
                                            'reb' => 0,
                                            'ast' => 0,
                                            'stl' => 0,
                                            'blk' => 0,
                                            'fgp' => 0,
                                            'ftp' => 0,
                                            'pm3' => 0,
                                            'ftm' => 0,
                                        );          
                
                //career stats           
                for($i = 1;$i <= count($plyrGamesPlayed); $i++)
                {
                    $current = $i-1;
                    
                    //points
                    $totalPlayerStats['pts'] = $totalPlayerStats['pts'] + $plyrGamesPlayed[$current]->pts;                                
                    
                    //rebounds
                    $totalPlayerStats['reb'] = $totalPlayerStats['reb'] + $plyrGamesPlayed[$current]->reb;
                    
                    //assists
                    $totalPlayerStats['ast'] = $totalPlayerStats['ast'] + $plyrGamesPlayed[$current]->ast;
                    
                    //assists
                    $totalPlayerStats['stl'] = $totalPlayerStats['stl'] + $plyrGamesPlayed[$current]->stl;
                    
                    //block
                    $totalPlayerStats['blk'] = $totalPlayerStats['blk'] + $plyrGamesPlayed[$current]->blk; 
                    
                    //3-pts made
                    $totalPlayerStats['pm3'] = $totalPlayerStats['pm3'] + $plyrGamesPlayed[$current]->pm3;
                    
                    //free throws made
                    $totalPlayerStats['ftm'] = $totalPlayerStats['ftm'] + $plyrGamesPlayed[$current]->ftm;
                    
                    //free throws %
                    if ($plyrGamesPlayed[$current]->ftm != 0 && $plyrGamesPlayed[$current]->fta != 0)
                    {                        
                        $totalPlayerStats['ftp'] = $totalPlayerStats['ftp'] + number_format(($plyrGamesPlayed[$current]->ftm / $plyrGamesPlayed[$current]->fta) * 100, 1);
                    }
                    else
                    {
                        $totalPlayerStats['ftp'] = $totalPlayerStats['ftp'] + 0;
                    }
                    
                    //field goals %
                    if ($plyrGamesPlayed[$current]->fgm != 0 && $plyrGamesPlayed[$current]->fga != 0)
                    { 
                        $totalPlayerStats['fgp'] = $totalPlayerStats['fgp'] + number_format(($plyrGamesPlayed[$current]->fgm / $plyrGamesPlayed[$current]->fga) * 100, 1);
                    }
                    else
                    {
                        $totalPlayerStats['fgp'] = $totalPlayerStats['fgp'] + 0;
                    }                    
                    
                }
                
                if(count($plyrGamesPlayed) > 0)
                {
                    $plyr[0]->pts = number_format(($totalPlayerStats['pts']/count($plyrGamesPlayed)),1);
                    $plyr[0]->reb = number_format(($totalPlayerStats['reb']/count($plyrGamesPlayed)),1);  
                    $plyr[0]->ast = number_format(($totalPlayerStats['ast']/count($plyrGamesPlayed)),1);
                    $plyr[0]->stl = number_format(($totalPlayerStats['stl']/count($plyrGamesPlayed)),1);
                    $plyr[0]->blk = number_format(($totalPlayerStats['blk']/count($plyrGamesPlayed)),1);
                    $plyr[0]->fgp = number_format(($totalPlayerStats['fgp']/count($plyrGamesPlayed)),1);
                    $plyr[0]->ftp = number_format(($totalPlayerStats['ftp']/count($plyrGamesPlayed)),1);
                    $plyr[0]->pm3 = $totalPlayerStats['pm3'];
                    $plyr[0]->ftm = $totalPlayerStats['ftm'];
                    $plyr[0]->save();
                }
                else
                {
                    $plyr[0]->pts = number_format($totalPlayerStats['pts'],1);
                    $plyr[0]->reb = number_format($totalPlayerStats['reb'],1);  
                    $plyr[0]->ast = number_format($totalPlayerStats['ast'],1);
                    $plyr[0]->stl = number_format($totalPlayerStats['stl'],1);
                    $plyr[0]->blk = number_format($totalPlayerStats['blk'],1);
                    $plyr[0]->fgp = number_format($totalPlayerStats['fgp'],1);
                    $plyr[0]->ftp = number_format($totalPlayerStats['ftp'],1);
                    $plyr[0]->pm3 = $totalPlayerStats['pm3'];
                    $plyr[0]->ftm = $totalPlayerStats['ftm'];
                    $plyr[0]->save();
                }
            }
            
            $game->isFinished = 1;
            $game->save();
            
            //save here team game stats
                //hometeam
                
                //awayteam
            
            $homeGameScore = TeamGameStats::where('team_id', $request->hometeam)
                ->where('game_id', $id)
                ->first();
            
            $homeGameScore->pts = $hmtmgmsts['pts'];
            $homeGameScore->reb = $hmtmgmsts['reb'];
            $homeGameScore->ast = $hmtmgmsts['ast'];
            $homeGameScore->stl = $hmtmgmsts['stl'];
            $homeGameScore->blk = $hmtmgmsts['blk'];
            $homeGameScore->tov = $hmtmgmsts['tov'];
            $homeGameScore->fgm = $hmtmgmsts['fgm'];
            $homeGameScore->fga = $hmtmgmsts['fga'];
            $homeGameScore->pm3 = $hmtmgmsts['pm3'];
            $homeGameScore->pa3 = $hmtmgmsts['pa3'];
            $homeGameScore->fta = $hmtmgmsts['fta'];
            $homeGameScore->ftm = $hmtmgmsts['ftm'];
            $homeGameScore->oreb = $hmtmgmsts['oreb'];
            $homeGameScore->dreb = $hmtmgmsts['dreb'];
            $homeGameScore->Q1_score = $request->hq_1;
            $homeGameScore->Q2_score = $request->hq_2;
            $homeGameScore->Q3_score = $request->hq_3;
            $homeGameScore->Q4_score = $request->hq_4;
            $homeGameScore->OT1_score = $request->hot_1;
            $homeGameScore->OT2_score = $request->hot_2;
            $homeGameScore->OT3_score = $request->hot_3;
            $homeGameScore->OT4_score = $request->hot_4;
            $homeGameScore->OT5_score = $request->hot_5;
            $homeGameScore->total_score = $request->hq_1 + $request->hq_2 + $request->hq_3 + $request->hq_4 + $request->hot_1 + $request->hot_2 + $request->hot_3 + $request->hot_4 + $request->hot_5;
            
            $homeGameScore->save();
            
            
            $awayGameScore = TeamGameStats::where('team_id', $request->awayteam)
                ->where('game_id', $id)
                ->first();
            
            $awayGameScore->pts = $awtmgmsts['pts'];
            $awayGameScore->reb = $awtmgmsts['reb'];
            $awayGameScore->ast = $awtmgmsts['ast'];
            $awayGameScore->stl = $awtmgmsts['stl'];
            $awayGameScore->blk = $awtmgmsts['blk'];
            $awayGameScore->tov = $awtmgmsts['tov'];
            $awayGameScore->fgm = $awtmgmsts['fgm'];
            $awayGameScore->fga = $awtmgmsts['fga'];
            $awayGameScore->pm3 = $awtmgmsts['pm3'];
            $awayGameScore->pa3 = $awtmgmsts['pa3'];
            $awayGameScore->fta = $awtmgmsts['fta'];
            $awayGameScore->ftm = $awtmgmsts['ftm'];
            $awayGameScore->oreb = $awtmgmsts['oreb'];
            $awayGameScore->dreb = $awtmgmsts['dreb'];    
            $awayGameScore->Q1_score = $request->aq_1;
            $awayGameScore->Q2_score = $request->aq_2;
            $awayGameScore->Q3_score = $request->aq_3;
            $awayGameScore->Q4_score = $request->aq_4;
            $awayGameScore->OT1_score = $request->aot_1;
            $awayGameScore->OT2_score = $request->aot_2;
            $awayGameScore->OT3_score = $request->aot_3;
            $awayGameScore->OT4_score = $request->aot_4;
            $awayGameScore->OT5_score = $request->aot_5;
            $awayGameScore->total_score = $request->aq_1 + $request->aq_2 + $request->aq_3 + $request->aq_4 + $request->aot_1 + $request->aot_2 + $request->aot_3 + $request->aot_4 + $request->aot_5;
            $awayGameScore->save();
            
            //flash a notification
            Session::flash('flash_message', 'Game Stats is successfully updated.');
            
            return redirect()->route('game.show', $game->slug);
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
        $game = Game::find($id);
        $game->active = 0;
        $game->deleted = 1;

        $game->save();
        
        //flash a notification
        Session::flash('flash_message', 'Game Schedule is deleted successfully.');
        
        return redirect()->route('game.index');
    }
}
