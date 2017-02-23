<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Venue;
use App\Models\Game;
use App\Models\TeamGameStats;

use Validator;
use Auth;

class ScheduleController extends Controller
{    
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //game details and player details
        $gameDetails = Game::with('hometeam','hometeam.teamstats','awayteam','awayteam.teamstats')
                            ->where('deleted',0)
                            ->get();               
        
        
        return $gameDetails[0]["hometeam"]["teamstats"]->pts;
        //for the dynamic carousel
    $schedule = App\Models\TeamGameStats::with('game','team')
                            ->get();
                
    $final = [];
    $away = [];
    $home = [];

    foreach($schedule as $key => $sched)    
    {
        $var = $sched;

        if($var->game->deleted == 0 )
        {
            if($var->game->hometeam_id == $var->team->team_id)
            {
                $home[] = $var;
            }
            elseif($var->game->awayteam_id == $var->team->team_id)
            {
                $away[] = $var;
            }

            $final[] = $var;                
        }
    }
        
        //return view ('pages.schedule.index', ['schedule' => $schedule]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $team = Team::where('active', 1)
            ->where('deleted', 0)
            ->get()
            ->lists('display_name', 'team_id');
        
        $venue = Venue::where('active', 1)
            ->where('deleted', 0)
            ->get()
            ->lists('display_name', 'venue_id');
        
        return view('pages.schedule.create', ['team' => $team, 'venue' => $venue]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'venue' => 'required',
            'hometeam' => 'required',
            'awayteam' => 'required',
            'm_date' => 'required',
            'm_time' => 'required'
        ]);        
        
        if ($validator->fails() || $request->hometeam == $request->awayteam)
        {
            if ($request->hometeam == $request->awayteam)
            {
                return response("Same team", 405);
            }
            else
            {
                return response($validator->errors(), 500);
            }  
        }
        else
        {
            $schedule = new Schedule;

            //parse time with AM or PM
            $mtime = $request->m_time.' '.$request->period;

            //convert to military time
            $matchTime = date("Y-m-d", strtotime($request->m_time)).' '.date("H:i:s", strtotime($mtime));    


            $schedule->venue_id = $request->venue;
            $schedule->hometeam_id = $request->hometeam;
            $schedule->awayteam_id = $request->awayteam;
            $schedule->match_date = date("Y-m-d", strtotime($request->m_date));
            $schedule->match_time = $matchTime;
            $schedule->active = 1;
            $schedule->deleted = 0;

            $schedule->save();
            
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
        $schedule = Schedule::find($id);
        $team = Team::where('active', 1)
            ->where('deleted', 0)
            ->get()
            ->lists('display_name', 'team_id');
        
        $venue = Venue::where('active', 1)
            ->where('deleted', 0)
            ->get()
            ->lists('display_name', 'venue_id');
        
        return view('pages.schedule.edit', ['team' => $team, 'venue' => $venue, 'schedule' => $schedule]);
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
        $validator = Validator::make($request->all(), [
            'venue' => 'required',
            'hometeam' => 'required',
            'awayteam' => 'required',
            'm_date' => 'required',
            'm_time' => 'required'
        ]);
        
         if ($validator->fails() || $request->hometeam == $request->awayteam)
        {
            if ($request->hometeam == $request->awayteam)
            {
                return response("Same team", 405);
            }
            else
            {
                return response($validator->errors(), 500);
            }  
        }
        else
        {
            $schedule = Schedule::find($id);

            //parse time with AM or PM
            $mtime = $request->m_time.' '.$request->period;

            //convert to military time
            $matchTime = date("Y-m-d", strtotime($request->m_time)).' '.date("H:i:s", strtotime($mtime));    


            $schedule->venue_id = $request->venue;
            $schedule->hometeam_id = $request->hometeam;
            $schedule->awayteam_id = $request->awayteam;
            $schedule->match_date = date("Y-m-d", strtotime($request->m_date));
            $schedule->match_time = $matchTime;
            $schedule->active = 1;
            $schedule->deleted = 0;

            $schedule->save();
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
        $schedule = Schedule::find($id);
        $schedule->active = 0;
        $schedule->deleted = 1;

        $schedule->save();
        
        return redirect()->route('schedule.index');
    }
}
