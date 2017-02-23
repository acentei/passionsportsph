@extends('layout.master')

@section('title')
    Home: League
@stop

@include('layout.navi')

@section('content')
<br>
@if(Auth::user())

    <a href="{{ route('league.create') }}" class="button">Create New League</a>
<br><br>
@endif

<center>
			@foreach($league as $leag) 
    <div class="league-container">
        <div class="league-img-container">
            <a href="{{ route('league.show', $leag->slug) }}">
                <img src="{{ $leag->photo }}" alt="{{ $leag->display_name }}">
            </div>
                {{ $leag->display_name }}
            </a>
    </div>
        @endforeach
</center>
@endsection