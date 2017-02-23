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
                        <a href="{{ route('game.edit', $game->game_id) }}" class="button">Edit Schedule</a>
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
                    
                </div>
            </td>

            <td>
                <ul>
                    <li></li>                   
                    <li></li>                   
                    <li class="date">{{ date('F d, Y', strtotime($game->match_date)) }}</li>
                    <li class="time">{{ date('h:i A', strtotime($game->match_time)) }}</li>
                    <li class="venue">{{ $game["venue"]->display_name }}</li>
                </ul>
            </td>

            <td align="left" width="45%">
                <div class="game-team-logo" align="left">
                    
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
                    <br><br>
                    {{$game->custom_hometeam}} 
                    <br><br><br><br>
                
                </div>
                
            </td>

            <td><li class="venue-lower">HELLO</li></td>   

            <td class="game-away">
            <div class="pending-game-team-name">
                    <br><br>
                    {{$game->custom_awayteam}}
                    <br><br><br><br>
                 </div>


            </td>
        </tr>
    </table>

@endif

@endsection