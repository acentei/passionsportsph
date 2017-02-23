@extends('layout.master')

@section('title')
    League : Edit Rules
@stop

@include('layout.navi')

@section('content')

{{-- SHOW ERRORS --}}
@include('partials.alerts.error')

{!! Form::model($regulation, [
        'method' => 'PATCH',
        'route' => ['league.updateRules', $regulation->league_id]
    ]) !!}

<h1>Edit League Rules and Regulation: {{$league->display_name}}</h1>

<div id="errortask">
</div>



    <div> 
        <br><br>
        <input type="submit" class="btn-flat" value="Update"> 
        <a href="{{ route('league.view', $regulation->league_id) }}" class="button">Cancel</a>
        <br><br>
    </div>

@endsection