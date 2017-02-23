<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Models\PlayerCareerStats;
use App\Models\League;
use App\Models\Bracket;
use App\Models\Team;

use Session;

class StatLeaderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $league = League::find(Session::get("selectedLeague"));         
        
        $brackets = Bracket::where('league_id',$league->league_id)
                          ->where('active',1)
                          ->where('deleted',0)
                          ->get();    
        
        $teams = Team::where('league_id',$league->league_id)
                          ->where('active',1)
                          ->where('deleted',0)
                          ->get();                
        
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
            
            if(count($league) != 0)
            {
                if($var->player->team->league->league_id == $league->league_id)
                {
                    if ($var->player->team->deleted == 0)
                    {
                        if ($var->player->deleted == 0)
                        {                            
                            $finalPtsLeader[] = $var;                            
                        }
                    }
                }
            }//if count
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
            
            if(count($league) != 0)
            {
                if($var->player->team->league->league_id == $league->league_id)
                {
                     if ($var->player->team->deleted == 0)
                    {
                        if ($var->player->deleted == 0)
                        {                            
                            $finalRebLeader[] = $var;                            
                        }
                    }
                }
            }//if count
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
            
            if(count($league) != 0)
            {
                if($var->player->team->league->league_id == $league->league_id)
                {
                    if ($var->player->team->deleted == 0)
                    {
                        if ($var->player->deleted == 0)
                        {	
                            $finalAstLeader[] = $var;                            
                        }
                    }
                }
            }//count
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
            
            if(count($league) != 0)
            {
                if($var->player->team->league->league_id == $league->league_id)
                {
                    if ($var->player->team->deleted == 0)
                    {
                        if ($var->player->deleted == 0)
                        {
                            $finalStlLeader[] = $var;
                        }
                    }
                }
            }//count
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
            
            if(count($league) != 0)
            {
                if($var->player->team->league->league_id == $league->league_id)
                {
                    if ($var->player->team->deleted == 0)
                    {
                        if ($var->player->deleted == 0)
                        {
                            $finalBlkLeader[] = $var;
                        }
                    }
                }
            }//count
        }
        
        
        //field goal % leader
        $fgpLeader = PlayerCareerStats::with('player','player.team.league')
                            ->where('deleted',0)
                            ->where('active',1)
                            ->orderBy('fgp','DESC')
                            ->get();
        
        $finalFgpLeader = [];
        foreach($fgpLeader as $fgp)
        {
            $var = $fgp;
            
            if(count($league) != 0)
            {
                if($var->player->team->league->league_id == $league->league_id)
                {
                    if ($var->player->team->deleted == 0)
                    {
                        if ($var->player->deleted == 0)
                        {
                            $finalFgpLeader[] = $var;
                        }
                    }
                }
            }//count
        }
        
        //3-points made(total) leader
        $pm3Leader = PlayerCareerStats::with('player','player.team.league')
                            ->where('deleted',0)
                            ->where('active',1)
                            ->orderBy('pm3','DESC')
                            ->get();
        
        $finalPm3Leader = [];
        foreach($pm3Leader as $pm3)
        {
            $var = $pm3;
            
            if(count($league) != 0)
            {            
                if($var->player->team->league->league_id == $league->league_id)
                {
                    if ($var->player->team->deleted == 0)
                    {
                        if ($var->player->deleted == 0)
                        {
                            $finalPm3Leader[] = $var;
                        }
                    }
                }
            }//count
        }
        
        //free throws made(total) leader
        $ftmLeader = PlayerCareerStats::with('player','player.team.league')
                            ->where('deleted',0)
                            ->where('active',1)
                            ->orderBy('ftm','DESC')
                            ->get();
        
        $finalFtmLeader = [];
        foreach($ftmLeader as $ftm)
        {
            $var = $ftm;
            
            if(count($league) != 0)
            {            
                if($var->player->team->league->league_id == $league->league_id)
                {
                    if ($var->player->team->deleted == 0)
                    {
                        if ($var->player->deleted == 0)
                        {
                            $finalFtmLeader[] = $var;
                        }
                    }
                }
            }//count
        }
        
        //free throws % leader
        $ftpLeader = PlayerCareerStats::with('player','player.team.league')
                            ->where('deleted',0)
                            ->where('active',1)
                            ->orderBy('ftp','DESC')
                            ->get();
        
        $finalFtpLeader = [];
        foreach($ftpLeader as $ftp)
        {
            $var = $ftp;
            
            if(count($league) != 0)
            {            
                if($var->player->team->league->league_id == $league->league_id)
                {
                    if ($var->player->team->deleted == 0)
                    {
                        if ($var->player->deleted == 0)
                        {
                            $finalFtpLeader[] = $var;
                        }
                    }
                }
            }//count
        }
        
        return \View::make('pages.statleader.index',['ptsLeader' => $finalPtsLeader,'rebLeader' => $finalRebLeader,'astLeader' => $finalAstLeader,
                                              'stlLeader' => $finalStlLeader,'blkLeader' => $finalBlkLeader,'fgpLeader' => $finalFgpLeader,
                                              'pm3Leader' => $finalPm3Leader,'ftmLeader' => $finalFtmLeader,'ftpLeader' => $finalFtpLeader,
                                              'league' => $league,'bracket' => $brackets,'teamCount' => count($teams)]);
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
        //
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
        //
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
