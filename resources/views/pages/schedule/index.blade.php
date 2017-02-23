@extends('layout.master')

@section('title')
    Home: Schedule
@stop

@include('layout.navi')

@section('content')

<br>
<a href="{{ route('schedule.create') }}" class="button">Create New Schedule</a>
<a href="{{ route('venue.index')}}" class="button">View Venues</a>
<br><br>
<table cellpadding="0" cellspacing="0" border="0">
    
    <tbody>
			@foreach($schedule as $schedule)
        
        <tr>
            <td class="col-sched">{{ $schedule["hometeam"]->display_name }}</td>
            <td class="col-sched">{{ $schedule["venue"]->display_name }} <br> 
                <!-- format then display -->    
                {{ date('F d, Y', strtotime($schedule->match_date)) }}  <br> 
                {{ date('h:i A', strtotime($schedule->match_time)) }}
            </td>
            <td class="col-sched">{{ $schedule["awayteam"]->display_name }}</td>
        </tr>
            @endforeach
    </tbody>
    
    
</table>

@endsection