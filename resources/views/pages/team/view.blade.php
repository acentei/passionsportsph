@extends('layout.master')

@section('title')
    Team : Players
@stop

@include('layout.navi')

@section('content')


@if(Auth::user())
<br>
    <a href="{{ route('player.create') }}" class="button">Create New Player</a>

    <div class="page-btn-position">
        <table cellpadding="0" cellspacing="0" border="0">
            <tr class="transparent">
                <td>
                     <a href="{{ route('team.edit', $rawTeam->team_id) }}" class="button">Edit Team</a>
                </td>
                <td>
                    {!! Form::open([
                    'method' => 'DELETE',
                    'route' => ['team.destroy', $rawTeam->team_id]
                    ]) !!}
                    {!! Form::button('Delete Team', 
                    array('type' => 'submit', 
                    'class' => 'btn-flat')) !!}

                    {!! Form::close() !!}
                </td>
            </tr>
        </table>
    </div>
<br>
<br>
@endif

<h1 style="background-color: #000;text-align:center;">
    {{$rawTeam->display_name}}   
</h1>

<h1>Team Leaders</h1>

<center>
<div class="team-leader">
    <h5>Points Per Game</h5>
     
     @if (!empty($pointLeader))
	@if($pointLeader[0]->pts != 0.0)
		{{$pointLeader[0]['player']->last_name}}, {{$pointLeader[0]['player']->first_name[0]}}.   
                &nbsp;&nbsp;
            <b>{{$pointLeader[0]->pts}}</b>
	@else
	    <center>Nothing to display.</center>
	@endif
    @endif
</div> 

<div class="team-leader">
    <h5>Assists Per Game</h5>
     @if (!empty($astLeader))
	@if($astLeader[0]->ast != 0.0)
            {{$astLeader[0]['player']->last_name}}, {{$astLeader[0]['player']->first_name[0]}}.
	    &nbsp;&nbsp;
            <b>{{$astLeader[0]->ast}}</b>
	@else
	    <center>Nothing to display.</center>
	@endif

    @endif             
</div>

<div class="team-leader">
    <h5>Rebounds Per Game</h5>
    @if (!empty($rebLeader))
	@if($rebLeader[0]->reb != 0.0)
            {{$rebLeader[0]['player']->last_name}}, {{$rebLeader[0]['player']->first_name[0]}}.
	    &nbsp;&nbsp;
            <b>{{$rebLeader[0]->reb}}</b>
	@else
	    <center>Nothing to display.</center>
	@endif
    @endif
</div>

<div class="team-leader">
    <h5>Steals Per Game</h5>
    @if (!empty($stlLeader))
	@if($stlLeader[0]->stl != 0.0)
            {{$stlLeader[0]['player']->last_name}}, {{$stlLeader[0]['player']->first_name[0]}}.
	    &nbsp;&nbsp;
            <b>{{$stlLeader[0]->stl}}</b>
   	@else
	    <center>Nothing to display.</center>
	@endif
    @endif
</div>

<div class="team-leader">
    <h5>Blocks Per Game</h5>
    @if (!empty($blkLeader))
	@if($blkLeader[0]->blk != 0.0)
            {{$blkLeader[0]['player']->last_name}}, {{$blkLeader[0]['player']->first_name[0]}}.
	    &nbsp;&nbsp;
            <b>{{$blkLeader[0]->blk}}</b>
	@else
	    <center>Nothing to display.</center>
	@endif
    @endif
        
    </table>
</div>
</center>

<h1>Team Standing</h1>
<center>
    <div class="team-standing">
        <h4>WINS</h4>
        {{$teamStanding->wins}}
    </div>
    
    <div class="team-standing">
        <h4>LOSSES</h4>
        {{$teamStanding->losses}} 
    </div>
    
    <div class="team-standing">
        <h4>Status</h4>
        {{$teamStanding->league_status}}
    </div>
</center>


@if($league->hasBracket == 1)
    <h1>Team Bracket</h1>
    <center>
        <div class="team-standing">
            <h4>Bracket</h4>
            {{$rawTeam['bracket']->display_name}}
        </div>  
    </center>
@endif

<h1>Team Game Stats</h1>
<div class="scroll-table">
    <table class="team-game-stats" cellpadding="0" cellspacing="0" border="0">
    <thead>
        <tr>
            <th class="game-date">Game</th>
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
        @foreach ($teamGameStats as $stats)
        <tr>
            <td>{{$stats["game"]->match_date}}</td>
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

<h1>Players</h1>
<center>

    @foreach($team as $player)

        @if($league->hasPhotos == 1)
            <div class="player-container">
                <a href="{{ route('player.show', $player->slug) }}">
                    <img src="{{ $player->photo }}">
                    {{ $player->last_name }}, {{ $player->first_name }} {{ $player->middle_name}}
                    <br>
                    {{ $player->position }}
                    <br>
                    #{{ $player->jersey_number }}
                </a>
            </div>
        @else

            <div class="team-name-only">
                 <a href="{{ route('player.show', $player->slug) }}">
                    {{ $player->last_name }}, {{ $player->first_name }} {{ $player->middle_name}}
                    <br>
                    {{ $player->position }}
                    <br>
                    #{{ $player->jersey_number }}
                </a>
            </div>
        @endif

    @endforeach
</center>

@endsection