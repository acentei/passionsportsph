@extends('layout.master')

@section('title')
    Home: Team
@stop

@include('layout.navi')

@section('content')

<h1>League Teams</h1>

<center>
@if (Session::get('selectedLeague') == 0)
    <br>
     Please Select a League from the League Selector.
@else
    
    
    @if (count($team) > 0)
        @if($league->hasBracket == 1)    
            @foreach($bracket as $bra)
                <!-- TEAM BRACKET CONTAINER, Isang buo-->
                <!-- Start your foreach for brackets here -->
                <div class="team-bracket-container">

                    <!-- Bracket NAME -->
                    <div class="team-bracket-label">
                        <!-- Bracket Name here -->
                        {{$bra->display_name}}
                    </div>

                    <center>    
                        <!-- Start your foreach for teams inside this bracket here-->
                        @foreach($bra['team'] as $teams) 
                            <!-- Set the width and height of each team-->
                            @if($teams->deleted == 0 && $teams->active == 1 )
                                @if($league->hasPhotos == 1)
                                    <div class="team-container"> 
                                        <!-- Team Logo/Image container-->
                                        <div class="team-img-container"> 
                                            <a href="{{ route('team.show', $teams->slug) }}">
                                            <img src="{{ $teams->photo }}" alt="{{ $teams->display_name }}">
                                        </div>
                                                {{ $teams->display_name }}
                                            </a>
                                    </div>
                                @else
                                     <div class="team-name-only">

                                        <a href="{{ route('team.show', $teams->slug) }}">
                                             {{ $teams->display_name }}
                                        </a>

                                      </div>
                                @endif
                            @endif
                        @endforeach
                        <!-- End foreach for teams inside this bracket here-->
                    </center>


                </div>
                <!-- End foreach for brackets here-->
            @endforeach
        <!-- IF NO BRACKET-->
        @else
            @foreach($team as $teams)  
                @if($league->hasPhotos == 1)
                    <div class="team-container">
                        <div class="team-img-container">
                            <a href="{{ route('team.show', $teams->slug) }}">
                            <img src="{{ $teams->photo }}" alt="{{ $teams->display_name }}">
                        </div>
                        {{ $teams->display_name }}
                         </a>
                    </div>    
                @else
                    <div class="team-name-only">                 
                        <a href="{{ route('team.show', $teams->slug) }}">
                             {{ $teams->display_name }}
                        </a>       
                  </div>
                @endif

            @endforeach
        @endif
    @else
          <br>
        Nothing to display
    @endif
@endif
</center>

@endsection