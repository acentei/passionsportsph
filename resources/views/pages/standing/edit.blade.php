@extends('layout.master')

@section('title')
    League Standing : Edit
@stop

@include('layout.navi')

@section('content')

{{-- SHOW ERRORS --}}
@include('partials.alerts.error')

{!! Form::model($standing, [
        'method' => 'PATCH',
        'route' => ['standing.update', $standing->team_id],
        'files' => 'true'
    ]) !!}

<h1> Wins and Losses: {{$standing->display_name}}</h1>
<input type="hidden" value="{{Session::get('selectedLeague')}}" name="league" />
<input type="hidden" value="{{$standing->team_id}}" name="team" />

            <div class="col">
                <div class="col-title">Win: </div>
                <input id="col-border" type="number" name="win" maxlength = "40" value="{{ $standing->wins }}" required />
            </div>
            
            <div class="col">
                <div class="col-title">Loss: </div>
                <input id="col-border" type="number" name="loss" maxlength = "40" value="{{ $standing->losses }}" />
            </div>
           
    <div> 
        <br><br>
        <input type="submit" class="btn-flat" value="Update"> 
        <a href="{{ route('league.show', $standing['league']->slug) }}" class="button">Cancel</a>
        <br><br>
    </div>
    
@endsection