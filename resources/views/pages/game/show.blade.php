@extends('layout.header')

@section('title')
    Show: Game
@stop

@include('layout.navi')

@section('content')

@if(Auth::user())
    <div id="sched-btn">
            <table cellpadding="3" cellspacing="0" border="0">
            <tr class="transparent">
                <td>
                    
                    @if ($game->match_date >= date('Y-m-d',strtotime($now)))
                        <a href="{{ route('game.edit', $game->game_id) }}" class="button">Edit Schedule</a> <br>

                        <a href="{{ route('editStats', $game->game_id) }}" class="button">Update Game Stats</a>  
                    @else
                        <a href="{{ route('editStats', $game->game_id) }}" class="button">Update Game Stats</a>                      
                    @endif
                </td>
                </tr>
                
             <tr class="transparent">    
                <td>
                    {!! Form::open([
                    'method' => 'DELETE',
                    'route' => ['game.destroy', $game->game_id]
                    ]) !!}
                    {!! Form::button('Delete', 
                    array('type' => 'submit', 
                    'class' => 'btn-flat')) !!}

                    {!! Form::close() !!}
                </td>
            </tr>
        </table>
    </div>
@endif


@if ($game->isFinished == 0)
<table class="pending-game" cellpadding="0" cellspacing="0" border="0">
    <tr class="pending-game-top">
        <td align="right" width="45%">
            <div class="game-team-logo" align="right">
                @if($league->hasPhotos == 1)
                    <img src="{{$game['hometeam']->photo}}">
                @endif
            </div>
        </td>
        
        <td>
            <ul>
                <li></li>
                
                <li>
                    @if($league->hasBracket == 1)
                        @if($game['hometeam']['bracket']->display_name == $game['awayteam']['bracket']->display_name)
                            <b style="font-size: 10pt;">Bracket {{$game['hometeam']['bracket']->display_name}}</b>  <br>     
                        @endif
                    @endif
                </li>                
                <li class="date">{{ date('F d, Y', strtotime($game->match_date)) }}</li>
                <li class="time">{{ date('h:i A', strtotime($game->match_time)) }}</li>
                <li class="venue">{{ $game["venue"]->display_name }}</li>
            </ul>
        </td>
        
        <td align="left" width="45%">
            <div class="game-team-logo" align="left">
                @if($league->hasPhotos == 1)
                    <img src="{{$game['awayteam']->photo}}">
                @endif
            </div>
        </td>
    </tr>

 <div class="pending-triangle">VS</div>

    
    <tr class="pending-game-space">
        <td colspan="5"> </td>
    </tr>

    <tr class="pending-game-bottom">
        <td class="game-home">
            
            <div class="pending-game-team-name">
                <a href="{{ route('team.show', $game['hometeam']->slug) }}">
                    {{$game["hometeam"]->display_name}} 
                </a>
            </div>
            
            <div class="pending-game-home">
                @foreach($homePlayerStats as $home)
                    <a href="{{ route('player.show', $home['player']->slug) }}">
                        <b>#{{$home["player"]->jersey_number}}</b>&nbsp;&nbsp;
                        {{$home["player"]->last_name}},
                        {{$home["player"]->first_name}} {{$home["player"]->middle_name}} 
                    </a>
                @endforeach
            </div>
            
        </td>
        
        <td><li class="venue-lower">HELLO</li></td>   
        
        <td class="game-away">
	    <div class="pending-game-team-name">
                <a href="{{ route('team.show', $game['awayteam']->slug) }}">
                    {{$game["awayteam"]->display_name}}
                </a>
             </div>

            <div class="pending-game-away">
                @foreach($awayPlayerStats as $away)
                    <a href="{{ route('player.show', $away['player']->slug) }}">
                        {{$away["player"]->last_name}},
                        {{$away["player"]->first_name}} {{$away["player"]->middle_name}} 
                        &nbsp;&nbsp;<b>#{{$away["player"]->jersey_number}}</b>
                    </a>
                @endforeach
            </div>
                
        </td>
    </tr>
</table>


@else

<table class="finished-game" cellpadding="0" cellspacing="0" border="0">
    <tr class="pending-game-top">
        <td align="right" width="40%">
            <div class="game-team-logo" style="float:right;">
                @if($league->hasPhotos == 1)
                    <img src="{{$game['hometeam']->photo}}">
                @endif
            </div>
        </td>
        
        <td>
            <ul>
                <li class="date">{{ date('F d, Y', strtotime($game->match_date)) }}</li>
                <li class="time">{{ date('h:i A', strtotime($game->match_time)) }}</li>
                <li class="venue">{{ $game["venue"]->display_name }}</li>
            </ul>
        </td>
        
        <td align="left" width="40%">
            <div class="game-team-logo" style="float:left;">
                @if($league->hasPhotos == 1)
                    <img src="{{$game['awayteam']->photo}}">
                @endif
            </div>
        </td>
    </tr>
</table>

<div class="triangle">Final</div>

<table class="finished-game-score" cellpadding="0" cellspacing="0" border="0">
    
    <tr class="pending-game-space">
        <td colspan="5"> </td>
    </tr>
    
    <tr class="pending-game-bottom">
        <td class="game-home">
            <div class="pending-game-team-name">
                <a href="{{ route('team.show', $game['hometeam']->slug) }}">
                    {{$game["hometeam"]->display_name}}  
                </a>
            </div>
            <div class="finished-game-home">
                @if(count($homeTeamStats) > 0)
                    {{$homeTeamStats[0]->pts}}
                @else
                    0
                @endif  
            </div>
        </td>
        
        <td><li class="venue-lower">{{ $game["venue"]->display_name }}</li></td>
        
        <td class="game-away">
            <div class="finished-game-away">
                @if(count($awayTeamStats) > 0)
                    {{$awayTeamStats[0]->pts}}
                @else
                    0
                @endif
            </div>
            <div class="pending-game-team-name game-top">
                <a href="{{ route('team.show', $game['awayteam']->slug) }}">
                    {{$game["awayteam"]->display_name}}  
                </a>
            </div>
        </td>
    </tr>
</table>

<div class="scroll-table">    
    <table class="game-final-score" cellpadding="0" cellspacing="0" border="0">
        <thead>
            <tr>
                <th class="game-player-name">Team Name</th>
                <th>1st Quarter</th>
                <th>2nd Quarter</th>
                <th>3rd Quarter</th>
                <th>4th Quarter</th>
                @if ($homeTeamStats[0]->OT1_score > 0 || $awayTeamStats[0]->OT1_score > 0)
                <th>1st OT</th>
                @endif
                @if ($homeTeamStats[0]->OT2_score > 0 || $awayTeamStats[0]->OT2_score > 0)
                <th>2nd OT</th>
                @endif
                @if ($homeTeamStats[0]->OT3_score > 0 || $awayTeamStats[0]->OT3_score > 0)
                <th>3rd OT</th>
                @endif
                @if ($homeTeamStats[0]->OT4_score > 0 || $awayTeamStats[0]->OT4_score > 0)
                <th>4th OT</th>
                @endif
                @if ($homeTeamStats[0]->OT5_score > 0 || $awayTeamStats[0]->OT5_score > 0)
                <th>5th OT</th>
                @endif
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{$game["hometeam"]->display_name}}</td>
                <td>{{$homeTeamStats[0]->Q1_score}}</td>
                <td>{{$homeTeamStats[0]->Q2_score}}</td>
                <td>{{$homeTeamStats[0]->Q3_score}}</td>
                <td>{{$homeTeamStats[0]->Q4_score}}</td>
                @if ($homeTeamStats[0]->OT1_score > 0)
                <td>{{$homeTeamStats[0]->OT1_score}}</td>
                @endif
                @if ($homeTeamStats[0]->OT2_score > 0)
                <td>{{$homeTeamStats[0]->OT2_score}}</td>
                @endif
                @if ($homeTeamStats[0]->OT3_score > 0)
                <td>{{$homeTeamStats[0]->OT3_score}}</td>
                @endif
                @if ($homeTeamStats[0]->OT4_score > 0)
                <td>{{$homeTeamStats[0]->OT4_score}}</td>
                @endif
                @if ($homeTeamStats[0]->OT5_score > 0)
                <td>{{$homeTeamStats[0]->OT5_score}}</td>
                @endif
            </tr>

            <tr>
                <td>{{$game["awayteam"]->display_name}}</td>
                <td>{{$awayTeamStats[0]->Q1_score}}</td>
                <td>{{$awayTeamStats[0]->Q2_score}}</td>
                <td>{{$awayTeamStats[0]->Q3_score}}</td>
                <td>{{$awayTeamStats[0]->Q4_score}}</td>
                @if ($awayTeamStats[0]->OT1_score > 0)
                <td>{{$awayTeamStats[0]->OT1_score}}</td>
                @endif
                @if ($awayTeamStats[0]->OT2_score > 0)
                <td>{{$awayTeamStats[0]->OT2_score}}</td>
                @endif
                @if ($awayTeamStats[0]->OT3_score > 0)
                <td>{{$awayTeamStats[0]->OT3_score}}</td>
                @endif
                @if ($awayTeamStats[0]->OT4_score > 0)
                <td>{{$awayTeamStats[0]->OT4_score}}</td>
                @endif
                @if ($awayTeamStats[0]->OT5_score > 0)
                <td>{{$awayTeamStats[0]->OT5_score}}</td>
                @endif
            </tr>
        </tbody>
    </table>
</div>

    <div class="blank-space">
        <br>
        <hr>
        <br>
    </div>

    <div class="game-leader-container">
            <div class="header-h1">Game Leaders</div>
                <div class="game-leader" align="right">
                    <h3>{{$game["hometeam"]->display_name}}</h3>
                    <table cellpadding="0" cellspacing="0" border="0">
                        <tr>
                            <td>Points</td>
                            <td>{{$homePtsLeader[0]->pts}}</td>
                            <td>
                                <center>
                                    @if($league->hasPhotos == 1)
                                        <div class="game-player-container">                                       
                                            <img src="{{$homePtsLeader[0]['player']->photo}}">                                                                              
                                        </div>
                                    @endif                                     
                                </center>
                                {{$homePtsLeader[0]['player']->last_name}}
                            </td>
                        </tr>
                        <tr>
                            <td>Rebounds</td>
                            <td>{{$homeRebLeader[0]->reb}}</td>
                            <td>
                                <center>
                                    @if($league->hasPhotos == 1)
                                        <div class="game-player-container">
                                            <img src="{{$homeRebLeader[0]['player']->photo}}">                                            
                                        </div>
                                    @endif
                                </center>
                                {{$homeRebLeader[0]['player']->last_name}}
                            </td>
                        </tr>
                        <tr>
                            <td>Assists</td>
                            <td>{{$homeAstLeader[0]->ast}}</td>
                            <td>
                                <center>
                                    @if($league->hasPhotos == 1)
                                        <div class="game-player-container">
                                            <img src="{{$homeAstLeader[0]['player']->photo}}">
                                        </div>
                                     @endif
                                </center>
                                {{$homeAstLeader[0]['player']->last_name}}
                            </td>
                        </tr>
                    </table>
                </div>
                
                <div class="game-leader" align="left">
                    <h3>{{$game["awayteam"]->display_name}}</h3>
                    <table cellpadding="0" cellspacing="0" border="0">
                        <tr>
                            <td>
                                <center>
                                    @if($league->hasPhotos == 1)
                                        <div class="game-player-container">                                       
                                            <img src="{{$awayPtsLeader[0]['player']->photo}}">                                        
                                        </div>
                                    @endif
                                </center>
                                {{$awayPtsLeader[0]['player']->last_name}}
                            </td>
                            <td>Points</td>
                            <td>{{$awayPtsLeader[0]->pts}}</td>
                        </tr>
                        <tr>
                            <td>
                                <center>
                                    @if($league->hasPhotos == 1)
                                        <div class="game-player-container">
                                            <img src="{{$awayRebLeader[0]['player']->photo}}">
                                        </div>
                                    @endif
                                </center>
                                {{$awayRebLeader[0]['player']->last_name}}
                            </td>
                            <td>Rebounds</td>
                            <td>{{$awayRebLeader[0]->reb}}</td>
                        </tr>
                        <tr>
                            <td>
                                <center>
                                    @if($league->hasPhotos == 1)
                                        <div class="game-player-container">                                        
                                            <img src="{{$awayAstLeader[0]['player']->photo}}">                                        
                                        </div>
                                    @endif
                                </center>
                                {{$awayAstLeader[0]['player']->last_name}}
                            </td>
                            <td>Assists</td>
                            <td>{{$awayAstLeader[0]->ast}}</td>
                        </tr>
                    </table>
                </div>
            </div>

    <div class="blank-space">
        <br>
        <center>
            <div class="header-h1">Game Statistics</div>
        </center>
        <hr>        
        <br>
    </div>

<div class="scroll-table">
    
    <table class="game-stats" cellpadding="0" cellspacing="0" border="0">
        
        <thead>        
            <tr>
                <th colspan="20" class="game-team">
                    {{$game["hometeam"]->display_name}}
                </th>
            </tr>
            <tr>
                <th class="game-player-name">Player Name</th>
                <th title="Points: Game points of the player">Pts</th>
                @if($league->isShowFgm == 1)
                    <th title="Field Goal Made">FGM</th>
                @endif

                @if($league->isShowFga == 1)
                    <th title="Field Goal Attempted">FGA</th>
                @endif

                @if($league->isShowFgp == 1)
                    <th title="Field Goal Average">FG%</th>
                @endif

                @if($league->isShow3pm == 1)
                    <th title="3-Points Made">3PM</th>
                @endif

                @if($league->isShow3pa == 1)
                    <th title="3-Points Attempted">3PA</th>
                @endif

                @if($league->isShow3pp == 1)
                    <th title="3-Points Average">3P%</th>
                @endif

                @if($league->isShowFtm == 1)
                    <th title="Free Throw Made">FTM</th>
                @endif

                @if($league->isShowFta == 1)
                    <th title="Free Throw Attempted">FTA</th>
                @endif

                @if($league->isShowFtp == 1)
                    <th title="Free Throw Average">FT%</th>
                @endif

                @if($league->isShowReb == 1)
                        <th title="Rebound">Reb</th>
                @endif

                @if($league->isShowOreb == 1)
                        <th title="Offensive Rebound">Oreb</th>
                @endif	

                @if($league->isShowDreb == 1)
                        <th title="Defensive Rebound">Dreb</th>
                @endif

                @if($league->isShowAst == 1)
                        <th title="Assist">Ast</th>
                @endif

                @if($league->isShowStl == 1)
                        <th title="Steal">Stl</th>
                @endif

                @if($league->isShowBlk == 1)
                    <th title="Block">Blk</th>
                @endif

                @if($league->isShowTov == 1)
                    <th title="Turnover">Tov</th>
                @endif
            </tr>
        </thead>
    
        <tbody>
			@foreach($homePlayerStats as $home)
            <tr>
                <td>
                    <a href="{{ route('player.show', $home['player']->slug) }}">
                        {{$home["player"]->first_name}} {{$home["player"]->middle_name}} {{$home["player"]->last_name}}
                    </a>
                </td>
                <td>{{ $home->pts }}</td>

                @if($league->isShowFgm == 1)
                 <td>{{ $home->fgm }}</td>
                @endif

                @if($league->isShowFga == 1)
                    <td>{{ $home->fga }}</td>
                @endif

                @if($league->isShowFgp == 1)
                    @if ($home->fgm != 0 && $home->fga != 0)
                        <td>{{ number_format(($home->fgm / $home->fga) * 100, 1) }}%</td>
                    @else
                        <td>0%</td>
                    @endif
                @endif

                @if($league->isShow3pm == 1)
                    <td>{{ $home->pm3 }}</td>
                @endif

                @if($league->isShow3pa == 1)
                    <td>{{ $home->pa3 }}</td>
                @endif

                @if($league->isShow3pp == 1)
                    @if ($home->pm3 != 0 && $home->pa3 != 0)
                        <td>{{ number_format(($home->pm3 / $home->pa3) * 100, 1) }}%</td>
                    @else
                        <td>0%</td>
                    @endif
                @endif

                @if($league->isShowFtm == 1)
                    <td>{{ $home->ftm }}</td>
                @endif

                @if($league->isShowFta == 1)
                    <td>{{ $home->fta }}</td>
                @endif

                @if($league->isShowFtp == 1)
                    @if ($home->ftm != 0 && $home->fta != 0)
                        <td>{{ number_format(($home->ftm / $home->fta) * 100, 1) }}%</td>
                    @else
                        <td>0%</td>
                    @endif
                @endif

                @if($league->isShowReb == 1)
                    <td>{{ $home->reb }}</td>
                @endif

                @if($league->isShowOreb == 1)
                    <td>{{ $home->oreb }}</td>
                @endif

                @if($league->isShowDreb == 1)
                    <td>{{ $home->dreb }}</td>
                @endif

                @if($league->isShowAst == 1)
                    <td>{{ $home->ast }}</td>
                @endif

                @if($league->isShowStl == 1)
                    <td>{{ $home->stl }}</td>
                @endif

                @if($league->isShowBlk == 1)
                    <td>{{ $home->blk }}</td>
                @endif

                @if($league->isShowTov == 1)
                    <td>{{ $home->tov }}</td>
                @endif
            </tr>
            @endforeach
    </tbody>
    </table>
</div>

<div class="blank-space">
    <br>
     <hr>
    <br>
        </div>
<div class="scroll-table">
    <table class="game-stats" cellpadding="0" cellspacing="0" border="0">
        <thead>
            <tr>
                <th colspan="20" class="game-team">
                    {{$game["awayteam"]->display_name}}
                </th>
            </tr>
            <tr>
                <th class="game-player-name">Player Name</th>
                <th title="Points: Game points of the player">Pts</th>

                @if($league->isShowFgm == 1)
                    <th title="Field Goal Made">FGM</th>
                @endif

                @if($league->isShowFga == 1)
                    <th title="Field Goal Attempted">FGA</th>
                @endif

                @if($league->isShowFgp == 1)
                    <th title="Field Goal Average">FG%</th>
                @endif

                @if($league->isShow3pm == 1)
                    <th title="3-Points Made">3PM</th>
                @endif

                @if($league->isShow3pa == 1)
                    <th title="3-Points Attempted">3PA</th>
                @endif

                @if($league->isShow3pp == 1)
                    <th title="3-Points Average">3P%</th>
                @endif

                @if($league->isShowFtm == 1)
                    <th title="Free Throw Made">FTM</th>
                @endif

                @if($league->isShowFta == 1)
                    <th title="Free Throw Attempted">FTA</th>
                @endif

                @if($league->isShowFtp == 1)
                    <th title="Free Throw Average">FT%</th>
                @endif

                @if($league->isShowReb == 1)
                    <th title="Rebound">Reb</th>
                @endif

                @if($league->isShowOreb == 1)
                    <th title="Offensive Rebound">Oreb</th>
                @endif	

                @if($league->isShowDreb == 1)
                    <th title="Defensive Rebound">Dreb</th>
                @endif

                @if($league->isShowAst == 1)
                    <th title="Assist">Ast</th>
                @endif

                @if($league->isShowStl == 1)
                    <th title="Steal">Stl</th>
                @endif

                @if($league->isShowBlk == 1)
                    <th title="Block">Blk</th>
                @endif

                @if($league->isShowTov == 1)
                    <th title="Turnover">Tov</th>
                @endif

            </tr>
        </thead>
    
        <tbody>
            @foreach($awayPlayerStats as $away)
            <tr>
                <td>
                    <a href="{{ route('player.show', $away['player']->slug) }}">
                        {{$away["player"]->first_name}} {{$away["player"]->middle_name}} {{$away["player"]->last_name}}
                    </a>
                </td>
                <td>{{ $away->pts }}</td>

                @if($league->isShowFgm == 1)
                 <td>{{ $away->fgm }}</td>
                @endif

                @if($league->isShowFga == 1)
                    <td>{{ $away->fga }}</td>
                @endif

                @if($league->isShowFgp == 1)
                    @if ($away->fgm != 0 && $away->fga != 0)
                        <td>{{ number_format(($away->fgm / $away->fga) * 100, 1) }}%</td>
                    @else
                        <td>0%</td>
                    @endif
                @endif

                @if($league->isShow3pm == 1)
                    <td>{{ $away->pm3 }}</td>
                @endif

                @if($league->isShow3pa == 1)
                    <td>{{ $away->pa3 }}</td>
                @endif

                @if($league->isShow3pp == 1)
                    @if ($away->pm3 != 0 && $away->pa3 != 0)
                        <td>{{ number_format(($away->pm3 / $away->pa3) * 100, 1) }}%</td>
                    @else
                        <td>0%</td>
                    @endif
                @endif

                @if($league->isShowFtm == 1)
                    <td>{{ $away->ftm }}</td>
                @endif

                @if($league->isShowFta == 1)
                    <td>{{ $away->fta }}</td>
                @endif

                @if($league->isShowFtp == 1)
                    @if ($away->ftm != 0 && $away->fta != 0)
                        <td>{{ number_format(($away->ftm / $away->fta) * 100, 1) }}%</td>
                    @else
                        <td>0%</td>
                    @endif
                @endif

                @if($league->isShowReb == 1)
                    <td>{{ $away->reb }}</td>
                @endif

                @if($league->isShowOreb == 1)
                    <td>{{ $away->oreb }}</td>
                @endif

                @if($league->isShowDreb == 1)
                    <td>{{ $away->dreb }}</td>
                @endif

                @if($league->isShowAst == 1)
                    <td>{{ $away->ast }}</td>
                @endif

                @if($league->isShowStl == 1)
                    <td>{{ $away->stl }}</td>
                @endif

                @if($league->isShowBlk == 1)
                    <td>{{ $away->blk }}</td>
                @endif

                @if($league->isShowTov == 1)
                    <td>{{ $away->tov }}</td>
                @endif
            </tr>
            @endforeach
        </tbody>
    </table>
    
</div>
  
@endif

@endsection