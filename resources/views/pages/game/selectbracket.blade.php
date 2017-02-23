@extends('layout.master')

@section('title')
    Select: Team Brackets
@stop

@include('layout.navi')

@section('content')

<h1>Select Bracket to create game</h1>
    <br><br>

    <center>
        @foreach($bracket as $bra)
            <a href="{{route('select-bracket.show',$bra->bracket_id)}}" class="btn-bracket">{{$bra->display_name}}</a>    
        @endforeach
    </center>

@endsection