@extends('layout.master')

@section('title')
    Home: Player
@stop

@include('layout.navi')

@section('content')
<div class="search-bar">
     {!! Form::open([      
        'method' => 'GET',
        'url' => 'player'
    ]) !!}
    
    <input type="search" class="search-box"  name="search" id="players" placeholder="FIND PLAYERS" />
    <button id="btnSearch" type="submit" class="btn-mag">      &#128269;
    </button>
    
    {!! Form::close() !!}
</div>

<div class="alpha-bar">  

    <?php $j = 0 ?>
    @for ($i = 'a'; $i <= 'z'; $i++)            
        @if(strlen($i) == 1) 
                @if (($j < count($activeLetters)) && (strtoupper($activeLetters[$j]) == strtoupper($i)))
                    <a href="#{{$i}}">{{strtoupper($i)}}</a>
                    <?php $j++ ?>
                @else
                    <a class="disabled" href="#{{$i}}">{{strtoupper($i)}}</a>     
                @endif
        @endif <!-- end if for strlen -->
    @endfor
    
</div>

@if (Session::get('selectedLeague') == 0)
    <br>
     <center>Please Select a League from the League Selector.</center>
@else

    @if (count($player) > 0)

        <div class="display-player">
            
            <?php $k = 0 ?>
             @for ($i = 'a'; $i <= 'z'; $i++)                                    
                @if(strlen($i) == 1)    
                    @if (($k < count($activeLetters)) && (strtoupper($activeLetters[$k]) == strtoupper($i)))
                        <div class="alpha">
                            <a class="cap" name="{{$i}}">{{$i}}</a>
                                <div class="player-names">        
                                    
                                    @foreach($player as $players)                                       
                                        @if (strtoupper($players->last_name[0]) == strtoupper($i))
                                            <a href="{{ route('player.show', $players->slug) }}" class="playerpage">{{$players->last_name}}, {{$players->first_name}} [{{$players["team"]->nickname}}]</a>
                                        @endif
                                    @endforeach 
                                </div>
                            <?php $k++ ?>
                        </div>
                    @endif
                @endif
            @endfor
        </div>
    @elseif ($isSearch == "true")
        <br>
        <center>"0" Result</center>
    @else
        <br>
        <center>Nothing to Display</center>
    @endif
@endif

<script>
$(document).ready(function() {
  $('a[href^="#"]').click(function() {
      var target = $(this.hash);
      if (target.length == 0) target = $('a[name="' + this.hash.substr(1) + '"]');
      if (target.length == 0) target = $('html');
      $('html, body').animate({ scrollTop: target.offset().top-160 }, 1000);
      return false;
  });
});
    </script>

@endsection

