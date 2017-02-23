@extends('layout.master')

@section('title')
    Moderator : Edit
@stop

@include('layout.navi')

@section('content')

{{-- SHOW ERRORS --}}
@include('partials.alerts.error')

{!! Form::model($moderator, [
        'method' => 'PATCH',
        'route' => ['moderator.update', $moderator->account_id]
    ]) !!}

<h1>Edit Moderator: {{$moderator->email}}</h1>

            
        <div class="col">
            <div class="col-title">First Name: </div>
            <input id="col-border" type="text" name="first_name" maxlength = "40" value="{{$moderator->first_name}}" required />
        </div>

        <div class="col">
            <div class="col-title">Middle Name: </div>
            <input id="col-border" type="text" name="middle_name" maxlength = "40" value="{{$moderator->middle_name}}"/>
        </div>

        <div class="col">
            <div class="col-title">Last Name: </div>
            <input id="col-border" type="text" name="last_name" maxlength = "40" value="{{$moderator->last_name}}" required />
        </div>

        <div class="col">
            <div class="col-title">Username: </div>
            <input id="col-border" type="text" name="username" value="{{$moderator->username}}" required />
        </div>

	<div class="col">
            <div class="col-title">League to handle: </div>
            {!! Form::select('league',$league,$moderator->handled_league, ['id'=>'league','class' => 'choices','placeholder' => '--Select--']) !!} 
        </div>


<div class="col">
        <b>NOTE:</b> Only Input <u>NEW</u> password if you want it to be changed. Otherwise leave it blank. 
</div>

        <div class="col">
            <div class="col-title">Password: </div>
            <input id="col-border" type="password" name="password" />
        </div>

        <div class="col">
            <div class="col-title">Confirm Password: </div>
            <input id="col-border" type="password" name="passconfirm" />
        </div>


    <div> 
        <br><br>
        <input type="submit" class="btn-flat" value="Update"> 
        <a href="{{ route('moderator.index') }}" class="button">Cancel</a> 
    </div>
<br>
@endsection