@extends('layout.master')

@section('title')
    Bracket : Edit
@stop

@include('layout.navi')

@section('content')

{{-- SHOW ERRORS --}}
@include('partials.alerts.error')

{!! Form::model($bracket, [
        'method' => 'PATCH',
        'route' => ['bracket.update', $bracket->bracket_id]
]) !!}


<h1>Edit Bracket: <!-- REMINDER PUT DISPLAY NAME HERE--></h1>

<div id="errortask">
</div>

    <div class="col">
        <div class="col-title">Bracket Name: </div>
            <input id="col-border" type="text" name="name" maxlength = "40" value="{{$bracket->display_name}}" required />
    </div>

    <div> 
        <br><br>
        <input type="submit" class="btn-flat" value="Update"> 
        <a href="{{ route('bracket.index') }}" class="button">Cancel</a>
        <br><br>
    </div>

@endsection