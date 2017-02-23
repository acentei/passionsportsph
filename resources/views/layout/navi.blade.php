@section('navigation')
    <div class="manager">
        @if(Auth::guest())
            <a href="{{ url('auth/login') }}">Manager Sign In</a>
        @else
            {{Auth::user()->first_name}}
            @if(Auth::user()->account_type == "Admin")
                <a href="{{ route('moderator.index') }}">[Moderators]</a>
            @endif
            <a href="{{ url('auth/logout') }}">[Logout]</a>
        @endif
    </div>

    <div id="social">
        <a href="https://www.facebook.com/passionsportsph/?fref=ts" title="Passion Sports PH Facebook Page" target="_blank"><img src="{{ URL::asset('/images/facebook.png') }}"></a>
        <a href="https://www.instagram.com/passionsports/" title="Passion Sports PH Instagram Page" target="_blank"><img src="{{ URL::asset('/images/instagram.png') }}"></a>
    </div>

@if (!empty(Session::get('selectedLeague')))
<div id="mini-logo">
    <a href="{{ route('league.index')}}">
        <img src="{{ URL::asset('/images/PSLogo4.png') }}">
    </a>
</div>
@endif

<div class="bar-container">
    <div class="logo">
        @if (!empty(Session::get('selectedLeague')))
            <a href="{{ route('league.show', $selLeg[0]->slug) }}">
                <img src="{{$selLeg[0]->photo}}">
            </a>
        @else
        <img src="{{ URL::asset('/images/PSLogo4.png') }}">
        @endif
    </div>   

    <div class="about">
        <a href="{{route('about.index')}}">About</a>
    </div> 

@if(empty(Session::get('selectedLeague')))
    <div class="select-league">
        SELECT LEAGUE
    </div>

    <div class="result" data-flickity='{ "cellAlign": "left" }'>

@elseif (!empty(Session::get('selectedLeague')))
    <div class="result" data-flickity='{ "cellAlign": "left" }'>
        <div class="static-banner static-banner1">&nbsp;</div>
            @foreach($game as $key => $games)
                <div class="result-cell">
                     <div class="carousel-post">
                            <div class="result-top">
                                {{date('M d, Y', strtotime($game[$key]->match_date))}} 
                                {{date('g:i a', strtotime($game[$key]->match_time))}} 
                                @if($selLeg[0]->hasBracket == 1)
                                
                                    @if(count($game[$key]['hometeam']['bracket']) > 0)
                                        @if($game[$key]['hometeam']['bracket']->display_name == $game[$key]['awayteam']['bracket']->display_name)
                                            <span style="font-size: 7pt;">Bracket {{$game[$key]['hometeam']['bracket']->display_name}}</span>    
                                        @endif
                                    @endif                            
                                @endif
                                
                            </div>
                            @if($selLeg[0]->hasBracket == 1)
                                <div class="result-content">    
                            @else
                                <div class="result-content" style="font-size:9pt;">
                            @endif
                                    
                                <table cellpadding="0" cellspacing="1" border="0">                                
                                    <tr>
                                        @if(strlen($game[$key]->custom_hometeam) > 5 )
                                            <td align="right" width="45%" style="font-size: 6.5pt;">
                                                @if(count($game[$key]["hometeam"]) == 0)
                                                    {{$game[$key]->custom_hometeam}}                                            
                                                @else
                                                    {{$game[$key]["hometeam"]->nickname}}                                               
                                                @endif
                                            </td> 
                                        @else
                                            <td align="right" width="45%">
                                                @if(count($game[$key]["hometeam"]) == 0)
                                                    {{$game[$key]->custom_hometeam}}                                            
                                                @else
                                                    {{$game[$key]["hometeam"]->nickname}}                                               
                                                @endif
                                            </td> 
                                        @endif
                                        
                                        <td align="center">|</td>
                                        @if(strlen($game[$key]->custom_awayteam) > 5 )
                                            <td align="left" style="font-size: 6.5pt;">                                    
                                                @if(count($game[$key]["awayteam"]) == 0)
                                                    {{$game[$key]->custom_awayteam}}     
                                                @else                                            
                                                    {{$game[$key]["awayteam"]->nickname}}                                         
                                                @endif                                        
                                            </td>
                                        @else
                                            <td align="left">                                    
                                                @if(count($game[$key]["awayteam"]) == 0)
                                                    {{$game[$key]->custom_awayteam}}     
                                                @else                                            
                                                    {{$game[$key]["awayteam"]->nickname}}                                         
                                                @endif                                        
                                            </td>
                                        @endif
                                    </tr>
                                    <tr>
                                       
                                        <td align="right" width="45%">
                                            @if(count($game[$key]["stats"]) != 0)
                                                {{$game[$key]["stats"][0]["pts"]}}
                                            @else
                                                 0                                           
                                            @endif
                                        </td>                                   
                                        
                                        <td align="center">|</td>
                                        
                                        <td align="left">
                                            @if(count($game[$key]["stats"]) != 0)
                                                {{$game[$key]["stats"][1]["pts"]}}
                                            @else
                                                 0                                           
                                            @endif   
                                        </td>
                                       
                                    </tr>
                                </table>
                            </div>
                     </div>
                     <div class="result-box">
                        <a href="{{ route('game.show', $game[$key]->slug) }}">BOX SCORE</a>
                     </div>
                </div>
            @endforeach 
        @endif
    </div>

    @if(!empty($selLeg[0]))
        <div class="my-navbar">
    @else
        <div class="my-navbar nav-empty-leg">
    @endif
      
          <div class="dropdown">
              
                @if(!empty($selLeg[0]))
                    @if(strlen($selLeg[0]->display_name) > 25)
                        <a href="{{ route('league.index')}}" style="font-size: 8.5pt;"> ► 
                    @else
                        <a href="{{ route('league.index')}}"> ► 
                    @endif
                        {{$selLeg[0]->display_name}}

                    </a>
                @else
                    <a href="{{ route('league.index')}}"> ► 
                        Choose a League
                    </a>
                @endif
                
                <div class="dropdown-content">
                    @foreach($league as $leag) 
                        <a href="{{ route('league.show', $leag->slug) }}">- {{ $leag->display_name }}</a>
                        <hr width="80%">
                    @endforeach
                </div>      
            </div>
        
    <input class="menu-btn" type="checkbox" id="menu-btn" />
    <label class="menu-icon" for="menu-btn"><span class="navicon"></span></label>
    @if(!empty($selLeg[0]))
        <ul class="menu">
            <li class="nav-spec">
                <a href="{{ route('league.index')}}"> ► 
                    @if(!empty($selLeg[0]))
                        {{$selLeg[0]->display_name}}
                    @else
                        Choose a League
                    @endif
                </a>
            </li>
            
            <li class="nav"><a href="{{ route('game.index')}}" id="btnGame">Schedule</a></li>
            <li class="nav"><a href="{{ route('team.index')}}" id="btnTeam">Teams</a></li>
            <li class="nav"><a href="{{ route('player.index')}}" id="btnPlayer">Players</a></li>
            <li class="nav"><a href="{{ route('stat-leader.index')}}" id="btnGallery">Stats</a></li>
            <li class="nav"><a href="{{ route('gallery.index')}}" id="btnGallery">Gallery</a></li>
            
        </ul>
    @else
        <ul class="menu">
            <li class="nav-spec">
                <a href="{{ route('league.index')}}"> ► 
                    @if(!empty($selLeg[0]))
                        {{$selLeg[0]->display_name}}
                    @else
                        Choose a League
                    @endif
                </a>
            </li>            
        </ul>
    @endif

    </div>
</div>

<script>
    $(function() {
        
    if (window.sessionStorage.activeMenuItem) {
    $("#"+sessionStorage.activeMenuItem).addClass('active');
}
        
$('.navbar a').click(function(){
    window.sessionStorage.activeMenuItem = this.id;
});
  
        

    });
    
</script>

@endsection