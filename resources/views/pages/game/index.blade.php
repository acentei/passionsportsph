@extends('layout.master')

@section('title')
    Home: Game
@stop

@include('layout.navi')

@section('content')

<br>
@if(Auth::user() && Session::get('selectedLeague') > 0)
  
    <a href="{{ route('game.create') }}" class="button">Create New Game</a>

    <a href="{{ route('venue.index')}}" class="button">View Venues</a>
<br><br>
@endif


@if (Session::get('selectedLeague') == 0)
    <br>
     <center>Please Choose a League.</center>
@else
    @if (count($game) > 0)
        @foreach($game as $schedule)
             @if($schedule->isFinished == 0)
            <table class="schedule" cellpadding="0" cellspacing="0" border="0">
                    <tr style="cursor:pointer" onclick="document.location.href='{{route('game.show', $schedule->slug)}}'">
                        <td class="col-sched">
                           
                            @if(count($schedule['hometeam']) == 0)
                                {{$schedule->custom_hometeam}}

                                <div class="sched-bg"></div>
                            @else
                                {{$schedule['hometeam']->display_name}}

                                <div class="sched-bg" style="background: url('{{$schedule['hometeam']->photo}}') no-repeat center center; 
                                 background-size: 100% auto;"></div>
                            @endif  

                        </td>
                        <td class="col-sched-mid">
                            @if($league->hasBracket == 1)
                                @if(count($schedule['hometeam']['bracket']) > 0)
                                    @if($schedule['hometeam']['bracket']->display_name == $schedule['awayteam']['bracket']->display_name)
                                        <b style="font-size: 10pt;">Bracket {{$schedule['hometeam']['bracket']->display_name}}</b>  <br>     
                                    @endif
                                @endif
                            @endif
                        
                            {{ date('F d', strtotime($schedule->match_date)) }}  <br> 
                            {{ date('h:i A', strtotime($schedule->match_time)) }} <br>	
                            VS
                        </td>
                        <td class="col-sched">  
                            
                            @if(count($schedule['awayteam']) == 0)
                                {{$schedule->custom_awayteam}}

                                <div class="sched-bg"></div>
                            @else
                                {{$schedule['awayteam']->display_name}}

                                <div class="sched-bg" style="background: url('{{$schedule['awayteam']->photo}}') no-repeat center center;
                                 background-size: 100% auto;"></div>
                            @endif                           
                            
                        </td>
                    </tr>     
            </table>
            @else
            <table class="schedule" cellpadding="0" cellspacing="0" border="0">
                    <tr style="cursor:pointer" onclick="document.location.href='{{route('game.show', $schedule->slug)}}'">

                        <td class="col-sched">
                            <br>
                            <ul>
                                <li class="col-name col-margin-top">
                                {{ $schedule['hometeam']->display_name }}
                                </li>
                                <li class="col-score">
                                    {{ $schedule['stats'][0]->pts }}
                                </li>
                            </ul>
                            
                            <div class="sched-bg" style="background: url('{{$schedule['hometeam']->photo}}') no-repeat center center;
							 background-size: 100% auto;"></div>

                        </td>
                        
                        <td class="col-sched-mid">
                                                        
                            {{ date('F d', strtotime($schedule->match_date)) }}  <br> 
                            {{ date('h:i A', strtotime($schedule->match_time)) }} <br>	
                            FINAL
                        </td>
                        <td class="col-sched">
                            <br>
                            <ul>
                                <li class="col-score">
        
                                    {{ $schedule['stats'][1]->pts }}
                                </li>
                                <li class="col-name">
                                     {{ $schedule['awayteam']->display_name }} 
                                </li>
                            </ul>
                            <div class="sched-bg" style="background: url('{{$schedule['awayteam']->photo}}') no-repeat center center;
							 background-size: 100% auto;"></div>
                        </td>
                    </tr>     
            </table>
        @endif
    @endforeach
    @else
        <br>
        <center>Nothing to Display</center>
    @endif
@endif

@endsection