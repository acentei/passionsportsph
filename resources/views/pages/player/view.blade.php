@extends('layout.master')

@section('title')
    Player : View
@stop

@include('layout.navi')

@section('content')


@if($league->hasPhotos == 1)
    <div class="player-img">
        <img src="{{$player->photo}}">
    </div>
@endif
<div class="player-profile-top">
    @if($league->hasPhotos == 1)
        <div class="player-profile-top-container">
            <img src="{{$player['team']->photo}}" align="left" width="125px" height="125px">        
        </div>
    @endif
    
    <table cellspacing="0" cellpadding="0" border="0">
        <tr> 
            <td rowspan="2" class="jersey-num">{{$player->jersey_number}}|</td>
            <td class="name">{{$player->first_name}} {{$player->middle_name}} {{$player->last_name}}</td>
        </tr>
        <tr>
            <td class="pos">{{$player->position}}</td>
        </tr>
        
         <tr>
            <td colspan="3" class="team-name">{{$player["team"]->display_name}}</td>
        </tr>
       

    </table>
</div>

<div class="player-stats">
    <ul>
        <li>PPG<br><div class="player-stats-bg">{{$careerStats->pts}}</div></li> 
        <li>RPG<br><div class="player-stats-bg">{{$careerStats->reb}}</div></li> 
        <li>APG<br><div class="player-stats-bg">{{$careerStats->ast}}</div></li> 
    </ul>
  @if(Auth::user()) 
        <table class="profile-button" cellpadding="0" cellspacing="0" border="0">
            <tr>
                <td>
                     <a href="{{ route('player.edit', $player->player_id) }}">[ Edit </a>
                </td>

                <td> | </td>

                <td>
                    {!! Form::open([
                    'method' => 'DELETE',
                    'route' => ['player.destroy', $player->player_id]
                    ]) !!}
                    {!! Form::button('Delete ]', 
                    array('type' => 'submit', 
                    'class' => 'btn-player-profile')) !!}

                    {!! Form::close() !!}
                </td>
            </tr> 
        </table>
     @endif
</div>


@if($league->hasPhotos == 1)
<div class="player-profile">
@else
<div class="player-profile" style="padding-left: 0px !important;">
@endif
<ul>
    <li>{{$player->height}}<div class="sub"> CM</div></li>  
    <li>{{$player->weight}}<div class="sub"> LBS</div></li>  
    <li>{{$player->age}}<div class="sub"> YRS</div></li> 
</ul> 
    
</div>
    
<div class="scroll-table">
    <table class="stats" cellpadding="0" cellspacing="0" border="0">
        <thead>
            <tr>
                <th class="game-date">Game</th>
                <th>Opponent</th>

                <th title="Points">Pts</th>
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
            @foreach ($playerGameStats as $stats)
            <tr>
                <td>{{$stats["game"]->match_date}}</td>
                @if ($player["team"]->team_id == $stats["game"]->hometeam_id)
                    <td>{{$stats["game"]["awayteam"]->nickname}}</td>
                @else
                    <td>{{$stats["game"]["hometeam"]->nickname}}</td>
                @endif

                <td>{{ $stats->pts }}</td>

                @if($league->isShowFgm == 1)
                    <td>{{ $stats->fgm }}</td>
                @endif

                @if($league->isShowFga == 1)
                 <td>{{ $stats->fga }}</td>
                @endif

                @if($league->isShowFgp == 1)
                    @if ($stats->fgm != 0 && $stats->fga != 0)
                        <td>{{ number_format(($stats->fgm / $stats->fga) * 100, 1) }}%</td>
                    @else
                        <td>0%</td>
                    @endif
                @endif

                @if($league->isShow3pm == 1)
                    <td>{{ $stats->pm3 }}</td>
                @endif

                @if($league->isShow3pa == 1)
                    <td>{{ $stats->pa3 }}</td>
                @endif

                @if($league->isShow3pp == 1)
                    @if ($stats->pm3 != 0 && $stats->pa3 != 0)
                        <td>{{ number_format(($stats->pm3 / $stats->pa3) * 100, 1) }}%</td>
                    @else
                        <td>0%</td>
                    @endif
                @endif

                @if($league->isShowFtm == 1)
                    <td>{{ $stats->ftm }}</td>
                @endif

                @if($league->isShowFta == 1)
                    <td>{{ $stats->fta }}</td>
                @endif

                @if($league->isShowFtp == 1)
                    @if ($stats->ftm != 0 && $stats->fta != 0)
                        <td>{{ number_format(($stats->ftm / $stats->fta) * 100, 1) }}%</td>
                    @else
                        <td>0%</td>
                    @endif
                @endif

                @if($league->isShowReb == 1)
                    <td>{{ $stats->reb }}</td>
                @endif

                @if($league->isShowOreb == 1)
                    <td>{{ $stats->oreb }}</td>
                @endif

                @if($league->isShowDreb == 1)
                    <td>{{ $stats->dreb }}</td>
                @endif

                @if($league->isShowAst == 1)
                    <td>{{ $stats->ast }}</td>
                @endif

                @if($league->isShowStl == 1)
                    <td>{{ $stats->stl }}</td>
                @endif

                @if($league->isShowBlk == 1)
                    <td>{{ $stats->blk }}</td>
                @endif

                @if($league->isShowTov == 1)
                    <td>{{ $stats->tov }}</td>
                @endif

            </tr>
            @endforeach

        </tbody>
    </table>
</div>

@endsection