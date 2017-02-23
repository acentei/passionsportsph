@extends('layout.master')

@section('title')
    Venue : Edit
@stop

@include('layout.navi')

@section('content')

{{-- SHOW ERRORS --}}
@include('partials.alerts.error')

{!! Form::model($venue, [
        'method' => 'PATCH',
        'route' => ['venue.update', $venue->venue_id]
    ]) !!}

<h1>Edit Venue: {{$venue->display_name}}</h1>

<div id="errortask">
</div>

    <div class="col">
        <div class="col-title">Venue Name: </div>
            <input id="col-border" type="text" name="name" maxlength = "40" value="{{$venue->display_name}}" required />
    </div>

    <div class="col">
        <div class="col-title">Venue Address: </div>
            <input id="col-border" class="inputText" type="text" name="address" maxlength = "100" value="{{$venue->address}}" required />
    </div>

    <div> 
        <br><br>
        <input type="submit" class="btn-flat" value="Update"> 
        <a href="{{ route('venue.index') }}" class="button">Cancel</a>
        <br><br>
    </div>

@endsection