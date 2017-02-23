@extends('layout.master')

@section('title')
    Game : Edit Stats
@stop

@include('layout.navi')

@section('content')

<h1>Edit Game Stats:</h1>

{!! Form::model($game, [
        'method' => 'PATCH',
        'route' => ['updateStats', $game->game_id]
    ]) !!}

<div id="errortask">
</div>
<div class="scroll-table">
    <table class="game-stats" cellpadding="0" cellspacing="0" border="0">
        <thead>
            <tr>
                <th>TEAM NAME</th>
                <th>1st Quarter</th>
                <th>2nd Quarter</th>
                <th>3rd Quarter</th>
                <th>4th Quarter</th>
                <th>1st OT</th>
                <th>2nd OT</th>
                <th>3rd OT</th>
                <th>4th OT</th>
                <th>5th OT</th>
            </tr>
        </thead>

        <tbody>
            <!--- HQ = HOME QUARTER-->
            @if (!empty($gameHomeScore))
            <tr>
                <td>{{$game["hometeam"]->display_name}}</td>
                <td><input type="number" name="hq_1" min="0" value="{{ $gameHomeScore->Q1_score }}" required /></td>
                <td><input type="number" name="hq_2" min="0" value="{{ $gameHomeScore->Q2_score }}" required /></td>
                <td><input type="number" name="hq_3" min="0" value="{{ $gameHomeScore->Q3_score }}" required /></td>
                <td><input type="number" name="hq_4" min="0" value="{{ $gameHomeScore->Q4_score }}" required /></td>
                <td><input type="number" name="hot_1" min="0" value="{{ $gameHomeScore->OT1_score }}" required /></td>
                <td><input type="number" name="hot_2" min="0" value="{{ $gameHomeScore->OT1_score }}" required /></td>
                <td><input type="number" name="hot_3" min="0" value="{{ $gameHomeScore->OT1_score }}" required /></td>
                <td><input type="number" name="hot_4" min="0" value="{{ $gameHomeScore->OT1_score }}" required /></td>
                <td><input type="number" name="hot_5" min="0" value="{{ $gameHomeScore->OT1_score }}" required /></td>
            </tr>
            @endif
            <!--- AQ = AWAY QUARTER-->
            @if (!empty($gameAwayScore))
            <tr>
                <td>{{$game["awayteam"]->display_name}}</td>
                <td><input type="number" name="aq_1" min="0" value="{{ $gameAwayScore->Q1_score }}" required /></td>
                <td><input type="number" name="aq_2" min="0" value="{{ $gameAwayScore->Q2_score }}" required /></td>
                <td><input type="number" name="aq_3" min="0" value="{{ $gameAwayScore->Q3_score }}" required /></td>
                <td><input type="number" name="aq_4" min="0" value="{{ $gameAwayScore->Q4_score }}" required /></td>
                <td><input type="number" name="aot_1" min="0" value="{{ $gameAwayScore->OT1_score }}" required /></td>
                <td><input type="number" name="aot_2" min="0" value="{{ $gameAwayScore->OT1_score }}" required /></td>
                <td><input type="number" name="aot_3" min="0" value="{{ $gameAwayScore->OT1_score }}" required /></td>
                <td><input type="number" name="aot_4" min="0" value="{{ $gameAwayScore->OT1_score }}" required /></td>
                <td><input type="number" name="aot_5" min="0" value="{{ $gameAwayScore->OT1_score }}" required /></td>

            </tr>
            @endif
        </tbody>
    </table>
</div>

<br><br>

<div class="scroll-table">
    <table class="game-stats" cellpadding="0" cellspacing="0" border="0">
        <thead>
            <tr>
                <th colspan="15" class="game-team">
                    <input type="hidden" value="{{$game['hometeam']->team_id}}" name="hometeam" />
                    {{$game["hometeam"]->display_name}}
                </th>
            </tr>
            <tr>
                <th class="game-player-name">Player Name</th>
                <th title="Points">Pts</th>

                @if($league->isShowFgm == 1)
                    <th title="Field Goal Made">FGM</th>
                @endif

                @if($league->isShowFga == 1)
                    <th title="Field Goal Attempted">FGA</th>
                @endif

                @if($league->isShow3pm == 1)
                    <th title="3-Points Made">3PM</th>
                @endif

                @if($league->isShow3pa == 1)
                    <th title="3-Points Attempted">3PA</th>
                @endif

                @if($league->isShowFtm == 1)
                    <th title="Free Throw Made">FTM</th>
                @endif

                @if($league->isShowFta == 1)
                    <th title="Free Throw Attempted">FTA</th>
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
                    <input type="hidden" value="{{$home->gamestats_id}}" name="homegamestats[]" />
                    <input type="hidden" value="{{$home->player_id}}" name="homeplayer[]" />
                    {{$home["player"]->first_name}} {{$home["player"]->middle_name}} {{$home["player"]->last_name}}
                </td>
                <td><input type="number" step="any" name="pts[]" min="0" value="{{ $home->pts }}" required /></td>

            @if($league->isShowFgm == 1)
                    <td><input type="number" step="any" name="fgm[]" min="0" value="{{ $home->fgm }}" required /></td>
            @else            
            <td style="display:none;"><input type="number" step="any" name="fgm[]" min="0" value="{{ $home->fgm }}" style="display:hidden;" /></td>
            @endif

            @if($league->isShowFga == 1)
                    <td><input type="number" step="any" name="fga[]" min="0" value="{{ $home->fga }}" required /></td>
            @else            
            <td style="display:none;"><input type="number" step="any" name="fga[]" min="0" value="{{ $home->fga }}" /></td>
            @endif

            @if($league->isShow3pm == 1)
                    <td><input type="number" step="any" name="pm3[]" min="0" value="{{ $home->pm3 }}" required /></td>
            @else            
            <td style="display:none;"><input type="number" step="any" name="pm3[]" min="0" value="{{ $home->pm3 }}" /></td>
            @endif

            @if($league->isShow3pa == 1)
                    <td><input type="number" step="any" name="pa3[]" min="0" value="{{ $home->pa3 }}" required /></td>
            @else            
            <td style="display:none;"><input type="number" step="any" name="pa3[]" min="0" value="{{ $home->pa3 }}" /></td>
            @endif

            @if($league->isShowFtm == 1)
                    <td><input type="number" step="any" name="ftm[]" min="0" value="{{ $home->ftm }}" required /></td>
            @else            
            <td style="display:none;"><input type="number" step="any" name="ftm[]" min="0" value="{{ $home->ftm }}" /></td>
            @endif

            @if($league->isShowFta == 1)
                    <td><input type="number" step="any" name="fta[]" min="0" value="{{ $home->fta }}" required /></td>
            @else            
            <td style="display:none;"><input type="number" step="any" name="fta[]" min="0" value="{{ $home->fta }}" /></td>
            @endif

            @if($league->isShowReb == 1)
                    <td><input type="number" step="any" name="reb[]" min="0" value="{{ $home->reb }}" required /></td>
            @else            
            <td style="display:none;"><input type="number" step="any" name="reb[]" min="0" value="{{ $home->reb }}" /></td>
            @endif

            @if($league->isShowOreb == 1)
                    <td><input type="number" step="any" name="oreb[]" min="0" value="{{ $home->oreb }}" required /></td>
            @else            
            <td style="display:none;"><input type="number" step="any" name="oreb[]" min="0" value="{{ $home->oreb }}" /></td>
            @endif

            @if($league->isShowDreb == 1)
                    <td><input type="number" step="any" name="dreb[]" min="0" value="{{ $home->dreb }}" required /></td>
            @else            
            <td style="display:none;"><input type="number" step="any" name="dreb[]" min="0" value="{{ $home->dreb }}" /></td>
            @endif

            @if($league->isShowAst == 1)
                    <td><input type="number" step="any" name="ast[]" min="0" value="{{ $home->ast }}" required /></td>
            @else            
            <td style="display:none;"><input type="number" step="any" name="ast[]" min="0" value="{{ $home->ast }}" /></td>
            @endif

            @if($league->isShowStl == 1)
                    <td><input type="number" step="any" name="stl[]" min="0" value="{{ $home->stl }}" required /></td>
            @else            
            <td style="display:none;"><input type="number" step="any" name="stl[]" min="0" value="{{ $home->stl }}" /></td>
            @endif

            @if($league->isShowBlk == 1)
                    <td><input type="number" step="any" name="blk[]" min="0" value="{{ $home->blk }}" required /></td>
            @else            
            <td style="display:none;"><input type="number" step="any" name="blk[]" min="0" value="{{ $home->blk }}" /></td>
            @endif

            @if($league->isShowTov == 1)
                    <td><input type="number" step="any" name="tov[]" min="0" value="{{ $home->tov }}" required /></td>
            @else            
            <td style="display:none;"><input type="number" step="any" name="tov[]" min="0" value="{{ $home->tov }}" /></td>
            @endif

            </tr>
                @endforeach
        </tbody>
    </table>
</div>

<br><br>
<div class="scroll-table">
    <table class="game-stats" cellpadding="0" cellspacing="0" border="0">
        <thead>
            <tr>
                <th colspan="15" class="game-team">
                    <input type="hidden" value="{{$game['awayteam']->team_id}}" name="awayteam" />
                    {{$game["awayteam"]->display_name}}
                </th>
            </tr>
            <tr>
                <th class="game-player-name">Player Name</th>
                <th>Pts</th>

                @if($league->isShowFgm == 1)
                    <th title="Field Goal Made">FGM</th>
                @endif

                @if($league->isShowFga == 1)
                    <th title="Field Goal Attempted">FGA</th>
                @endif

                @if($league->isShow3pm == 1)
                    <th title="3-Points Made">3PM</th>
                @endif

                @if($league->isShow3pa == 1)
                    <th title="3-Points Attempted">3PA</th>
                @endif

                @if($league->isShowFtm == 1)
                    <th title="Free Throw Made">FTM</th>
                @endif

                @if($league->isShowFta == 1)
                    <th title="Free Throw Attempted">FTA</th>
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
                    <input type="hidden" value="{{$away->gamestats_id}}" name="awaygamestats[]" />
                    <input type="hidden" value="{{$away->player_id}}" name="awayplayer[]" />
                    {{$away["player"]->first_name}} {{$away["player"]->middle_name}} {{$away["player"]->last_name}}
                </td>
                <td><input type="number" step="any" name="awaypts[]" min="0" value="{{ $away->pts }}" required /></td>

            @if($league->isShowFgm == 1)
                    <td><input type="number" step="any" name="awayfgm[]" min="0" value="{{ $away->fgm }}" required /></td>
            @else            
            <td style="display:none;"><input type="number" step="any" name="awayfgm[]" min="0" value="{{ $away->fgm }}" style="display:hidden;" /></td>
            @endif

            @if($league->isShowFga == 1)
                    <td><input type="number" step="any" name="awayfga[]" min="0" value="{{ $away->fga }}" required /></td>
            @else            
            <td style="display:none;"><input type="number" step="any" name="awayfga[]" min="0" value="{{ $away->fga }}" /></td>
            @endif

            @if($league->isShow3pm == 1)
                    <td><input type="number" step="any" name="awaypm3[]" min="0" value="{{ $away->pm3 }}" required /></td>
            @else            
            <td style="display:none;"><input type="number" step="any" name="awaypm3[]" min="0" value="{{ $away->pm3 }}" /></td>
            @endif

            @if($league->isShow3pa == 1)
                    <td><input type="number" step="any" name="awaypa3[]" min="0" value="{{ $away->pa3 }}" required /></td>
            @else            
            <td style="display:none;"><input type="number" step="any" name="awaypa3[]" min="0" value="{{ $away->pa3 }}" /></td>
            @endif

            @if($league->isShowFtm == 1)
                    <td><input type="number" step="any" name="awayftm[]" min="0" value="{{ $away->ftm }}" required /></td>
            @else            
            <td style="display:none;"><input type="number" step="any" name="awayftm[]" min="0" value="{{ $away->ftm }}" /></td>
            @endif

            @if($league->isShowFta == 1)
                    <td><input type="number" step="any" name="awayfta[]" min="0" value="{{ $away->fta }}" required /></td>
            @else            
            <td style="display:none;"><input type="number" step="any" name="awayfta[]" min="0" value="{{ $away->fta }}" /></td>
            @endif

            @if($league->isShowReb == 1)
                    <td><input type="number" step="any" name="awayreb[]" min="0" value="{{ $away->reb }}" required /></td>
            @else            
            <td style="display:none;"><input type="number" step="any" name="awayreb[]" min="0" value="{{ $away->reb }}" /></td>
            @endif

            @if($league->isShowOreb == 1)
                    <td><input type="number" step="any" name="awayoreb[]" min="0" value="{{ $away->oreb }}" required /></td>
            @else            
            <td style="display:none;"><input type="number" step="any" name="awayoreb[]" min="0" value="{{ $away->oreb }}" /></td>
            @endif

            @if($league->isShowDreb == 1)
                    <td><input type="number" step="any" name="awaydreb[]" min="0" value="{{ $away->dreb }}" required /></td>
            @else            
            <td style="display:none;"><input type="number" step="any" name="awaydreb[]" min="0" value="{{ $away->dreb }}" /></td>
            @endif

            @if($league->isShowAst == 1)
                    <td><input type="number" step="any" name="awayast[]" min="0" value="{{ $away->ast }}" required /></td>
            @else            
            <td style="display:none;"><input type="number" step="any" name="awayast[]" min="0" value="{{ $away->ast }}" /></td>
            @endif

            @if($league->isShowStl == 1)
                    <td><input type="number" step="any" name="awaystl[]" min="0" value="{{ $away->stl }}" required /></td>
            @else            
            <td style="display:none;"><input type="number" step="any" name="awaystl[]" min="0" value="{{ $away->stl }}" /></td>
            @endif

            @if($league->isShowBlk == 1)
                    <td><input type="number" step="any" name="awayblk[]" min="0" value="{{ $away->blk }}" required /></td>
            @else            
            <td style="display:none;"><input type="number" step="any" name="awayblk[]" min="0" value="{{ $away->blk }}" /></td>
            @endif

            @if($league->isShowTov == 1)
                    <td><input type="number" step="any" name="awaytov[]" min="0" value="{{ $away->tov }}" required /></td>
            @else            
            <td style="display:none;"><input type="number" step="any" name="awaytov[]" min="0" value="{{ $away->tov }}" /></td>
            @endif

            </tr>
                @endforeach
        </tbody>
    </table>    
</div>

    <div> 
        <br><br>
        <input type="submit" class="btn-flat" value="Update"> 
        <a href="{{ route('game.show', $game->slug) }}" class="button">Cancel</a>
    </div>

	<br>

@endsection