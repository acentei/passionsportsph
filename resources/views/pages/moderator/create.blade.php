@extends('layout.master')

@section('title')
    Moderator : Create
@stop

@include('layout.navi')

@section('content')

{{-- SHOW ERRORS --}}
@include('partials.alerts.error')

{!! Form::open([      
        'method' => 'POST',
        'action' => 'ModeratorController@store'
    ]) !!}

<h1>Create New Moderator:</h1>

            
        <div class="col">
            <div class="col-title">First Name: </div>
            <input id="col-border" type="text" name="first_name" maxlength = "40" value='{{old("first_name")}}' required />
        </div>

        <div class="col">
            <div class="col-title">Middle Name: </div>
            <input id="col-border" type="text" name="middle_name" maxlength = "40" value='{{old("middle_name")}}' />
        </div>

        <div class="col">
            <div class="col-title">Last Name: </div>
            <input id="col-border" type="text" name="last_name" maxlength = "40" value='{{old("last_name")}}' required />
        </div>

        <div class="col">
            <div class="col-title">Username: </div>
            <input id="col-border" type="text" name="username" value='{{old("username")}}' required />
        </div>

	<div class="col">
            <div class="col-title">League to handle: </div>
            {!! Form::select('league',$league,null, ['id'=>'league','class' => 'choices','placeholder' => '--Select--']) !!} 
        </div>

        <div class="col">
            <div class="col-title">Password: </div>
            <input id="col-border" type="password" name="password" required />
        </div>

        <div class="col">
            <div class="col-title">Confirm Password: </div>
            <input id="col-border" type="password" name="passconfirm" required />
        </div>


    <div> 
        <br><br>
        <input type="submit" class="btn-flat" value="Save"> 
       <a href="{{ route('moderator.index') }}" class="button">Cancel</a> 
    </div>
    <br>
@endsection