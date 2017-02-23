@extends('layout.master')

@section('title')
    Bracket : Create
@stop

@include('layout.navi')

@section('content')

{{-- SHOW ERRORS --}}
@include('partials.alerts.error')

{!! Form::open([      
    'method' => 'POST',       
    'action' => 'TeamBracketController@store'
]) !!}

<h1>Create Bracket:</h1>

<div id="errortask">
</div>

    <div class="col">
        <div class="col-title">Bracket Name: </div>
            <input id="col-border" type="text" name="name" maxlength = "100" required />
    </div>

    <div> 
        <br><br>
        <input type="submit" value="Save" class="btn-flat">
        <a href="{{ route('bracket.index') }}" class="button">Cancel</a>
        <br><br>
    </div>

@endsection