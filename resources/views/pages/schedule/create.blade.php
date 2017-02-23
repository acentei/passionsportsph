@extends('layout.master')

@section('title')
    Schedule : Create
@stop

@include('layout.navi')

@section('content')

{{-- SHOW ERRORS --}}
@include('partials.alerts.error')

<h1>Create New Schedule:</h1>

<div id="errortask">
</div>


            <div class="col">
            <b>Venue: </b>
                {!!Form:: select('venue', $venue, 0, ['id' => 'dropdown', 'class' => 'choices', 'placeholder' => '--Select Venue--','required']) !!}
            </div>

            <div class="col">
            <b>Home Team: </b>
                {!!Form:: select('hometeam', $team, 0, ['id' => 'dropdownHome', 'class' => 'choices', 'placeholder' => '--Select Home Team--','required']) !!}
            </div>

            <div class="col">
            <b>Away Team: </b>
                {!!Form:: select('awayteam', $team, 0, ['id' => 'dropdownTeam', 'class' => 'choices', 'placeholder' => '--Select Away Team--','required']) !!}
            </div>

                
            <div class="col">
            <b>Match Date: </b> <input type="text" id="datepicker" name="date">
            </div>

            <div class="col">
            <b>Match Time: </b> 
                {!!Form:: select('time', ['01:00:00' => "1:00", '1:30:00' => "1:30",
                                        '02:00:00' => "2:00", '2:30:00' => "2:30",
                                        '03:00:00' => "3:00", '3:30:00' => "3:30",
                                        '04:00:00' => "4:00", '4:30:00' => "4:30",
                                        '05:00:00' => "5:00", '5:30:00' => "5:30",
                                        '06:00:00' => "6:00", '6:30:00' => "6:30",
                                        '07:00:00' => "7:00", '7:30:00' => "7:30",
                                        '08:00:00' => "8:00", '8:30:00' => "8:30",
                                        '09:00:00' => "9:00", '9:30:00' => "9:30",
                                        '10:00:00' => "10:00", '10:30:00' => "10:30",
                                        '11:00:00' => "11:00", '11:30:00' => "11:30",
                                        '12:00:00' => "12:00", '12:30:00' => "12:30"],
                0, ['id' => 'dropdownTime', 'class' => 'choices', 'placeholder' => '--Select Time--','required']) !!}
                
                {!!Form:: select('time', ['AM' => "AM", 'PM' => "PM"],
                0, ['id' => 'dropdownPeriod', 'class' => 'choices','required']) !!}
            </div>

    <div> 
        <br><br>
        <input id="btnScheduleSave" type="submit" class="btnEdit" value="Save"> 
        <a href="{{ route('schedule.index') }}" class="button">Cancel</a>
        <br><br>
    </div>

<script type="text/javascript"> 
      $( document ).ready(function() {
        $('#btnScheduleSave').click(function() {  
         $.ajax({        
             type: 'POST',
             url: 'store',
             data: {
                 "venue" : $("#dropdown").val(),
                 "hometeam" : $("#dropdownHome").val(),
                 "awayteam" : $("#dropdownTeam").val(),
                 "m_date": $("#datepicker").val(),
                 "m_time" : $("#dropdownTime").val(),
                 "period" : $("#dropdownPeriod").val(),
             },
             success: function() {
                console.log('success');
                // window.location.href = "/league/public/index.php/schedule";
                $('#errortask').empty();
           },
           
          error: function(xhr, status, error) {
                
                  if (xhr.responseText == "Same team")
                    {
                        $('#errortask').html("<div class='error'></div>");
                        $('.error').append("Error: <br> Same picked team for Home Team and Away Team.")
                    }
                  else
                  {
                    var result = xhr.responseText.replace(/[^{]*/i,'');

                    //We parse the json
                    var data = JSON.parse(result);

                    $('#errortask').html("<div class='error'></div>");
                     //And continue like no error ever happened
                    $.each(data, function(i,item){
                        $('.error').append(item + "<br>");
                    });
                  }
                } 
            }); 
        });
          
        $( "#datepicker" ).datepicker({
                format: 'yyyy/mm/dd',
                formatSubmit: 'yyyy/mm/dd',
        }); 
      
});
     
     
    
</script>

@endsection