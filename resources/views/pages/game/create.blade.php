@extends('layout.master')

@section('title')
    Game : Create
@stop

@include('layout.navi')

@section('content')

{{-- SHOW ERRORS --}}
@include('partials.alerts.error')

<h1>Create New Game:</h1>

{!! Form::open([      
        'method' => 'POST',
        'action' => 'GameController@store'
    ]) !!}

<div id="errortask">
</div>

<input type="hidden" value="{{Session::get('selectedLeague')}}" name="league" />

            <div class="col">
                <div class="col-title">Venue: </div>
                {!!Form:: select('venue', $venue, 0, ['id' => 'dropdown', 'class' => 'choices', 'placeholder' => '--Select Venue--','required']) !!}
            </div>
           
            <div class="col">
                <div class="col-title">Home Team: </div>
                {!!Form:: select('hometeam', $team, 0, ['id' => 'dropdown', 'class' => 'choices', 'placeholder' => '--Select Home Team--']) !!}

                or &nbsp;

                <input id="col-border" type="text" name="custom_hometeam" placeholder="Custom Team Name" value='{{old("custom_hometeam")}}' /> 
            </div>

            <div class="col">
                <div class="col-title">Away Team: </div>
                {!!Form:: select('awayteam', $team, 0, ['id' => 'dropdown', 'class' => 'choices', 'placeholder' => '--Select Away Team--']) !!}

                or &nbsp;

                <input id="col-border" type="text" name="custom_awayteam" placeholder="Custom Team Name" value='{{old("custom_awayteam")}}' />
            </div>            

            <div class="col">
                <div class="col-title">Match Date: </div> <input type="text" id="datepicker" class="datepicker-border" name="m_date" value='{{old("m_date")}}' required />
            </div>

            <div class="col">
                <div class="col-title">Match Time: </div>
                {!!Form:: select('m_time', ['01:00:00' => "1:00",'1:15:00' => "1:15",'1:20:00' => "1:20", '1:30:00' => "1:30",
                                        '02:00:00' => "2:00", '2:15:00' => "2:15",'2:20:00' => "2:20", '2:30:00' => "2:30",
                                        '03:00:00' => "3:00", '3:15:00' => "3:15",'3:20:00' => "3:20", '3:30:00' => "3:30",
                                        '04:00:00' => "4:00", '4:15:00' => "4:15",'4:20:00' => "4:20", '4:30:00' => "4:30",
                                        '05:00:00' => "5:00", '5:15:00' => "5:15",'5:20:00' => "5:20", '5:30:00' => "5:30",
                                        '06:00:00' => "6:00", '6:15:00' => "6:15",'6:20:00' => "6:20", '6:30:00' => "6:30",
                                        '07:00:00' => "7:00", '7:15:00' => "7:15",'7:20:00' => "7:20", '7:30:00' => "7:30",
                                        '08:00:00' => "8:00", '8:15:00' => "8:15",'8:20:00' => "8:20", '8:30:00' => "8:30",
                                        '09:00:00' => "9:00", '9:15:00' => "9:15",'9:20:00' => "9:20", '9:30:00' => "9:30",
                                        '10:00:00' => "10:00", '10:15:00' => "10:15",'10:20:00' => "10:20", '10:30:00' => "10:30",
                                        '11:00:00' => "11:00", '11:15:00' => "11:15",'11:20:00' => "11:20", '11:30:00' => "11:30",
                                        '12:00:00' => "12:00", '12:15:00' => "12:15",'12:20:00' => "12:20", '12:30:00' => "12:30"],
                0, ['id' => 'dropdown', 'class' => 'choices', 'placeholder' => '--Select Time--','required']) !!}
                
                {!!Form:: select('period', ['AM' => "AM", 'PM' => "PM"],
                0, ['id' => 'dropdown', 'class' => 'choices','required']) !!}
            </div>

    <div> 
        <br><br>
        <input type="submit" class="btn-flat" value="Save"> 
        <a href="{{ route('game.index') }}" class="button">Cancel</a>
        <br><br>
    </div>

<script type="text/javascript"> 
      $( document ).ready(function() {
        $( "#datepicker" ).datepicker({
                format: 'yyyy/mm/dd',
                formatSubmit: 'yyyy/mm/dd',
        }); 
}); 
</script>

@endsection