@extends('layout.master')

@section('title')
    Venue : Create
@stop

@include('layout.navi')

@section('content')

{{-- SHOW ERRORS --}}
@include('partials.alerts.error')

{!! Form::open([      
        'method' => 'POST',
        'action' => 'VenueController@store'
    ]) !!}

<h1>Create New Venue:</h1>

<div id="errortask">
</div>

    <div class="col">
        <div class="col-title">Venue Name: </div>
            <input id="col-border" type="text" name="name" maxlength = "40" required />
    </div>

    <div class="col">
        <div class="col-title">Venue Address: </div>
            <input id="col-border" class="inputText" type="text" name="address" maxlength = "100" required />
    </div>

    <div> 
        <br><br>
        <input type="submit" value="Save" class="btn-flat">
        <a href="{{ route('venue.index') }}" class="button">Cancel</a>
        <br><br>
    </div>

@endsection