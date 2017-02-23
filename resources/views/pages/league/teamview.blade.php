
@if(Auth::user())
<br>
    <a href="{{ route('team.create') }}" class="button">Create New Team</a>

    @if($league->hasBracket == 1)
        <a href="{{ route('bracket.index') }}" class="button">View Team Brackets</a>
    @endif

    <div class="page-btn-position">
        <table cellpadding="0" cellspacing="0" border="0">
            <tr class="transparent">
                <td>
                     <a href="{{ route('league.edit', $league->league_id) }}" class="button">Edit League</a>
                </td>
		@if(Auth::user())        
    		  
       		    @if((Auth::user()->account_type == "Admin") && (Auth::user()->handled_league == 0))
                    
			<td>
                    		{!! Form::open([
                    			'method' => 'DELETE',
                    			'route' => ['league.destroy', $league->league_id]
                    		]) !!}
                    		{!! Form::button('Delete League', 
                    					array('type' => 'submit', 
                    					'class' => 'btn-flat')) !!}

                    		{!! Form::close() !!}
                	</td>
	  	    @endif
	        @endif

                
            </tr>
        </table>
    </div>
<br>
<br>
@endif

<h1 style="background-color: #000;text-align:center;">
    {{$league->display_name}}    
</h1>

<h1> &nbsp;&nbsp; League Leaders</h1>

<center>
    
@if($league->hasBracket == 1)
    @foreach($allbracket as $key => $brac)  
    
        <h6>{{$brac->display_name}}</h6>
        
        <br>
    
        <div class="league-leader">
            <h5>Points Per Game</h5>

            <?php $i=0; ?>

            <table cellpadding="0" cellspacing="0" border="0">
                @if(count($finalPtsLeader) > 0)
                    @if($finalPtsLeader[0]->pts != 0.0)
                        @foreach ($finalPtsLeader as $points)
                            @if($points['player']['team']->bracket_id == $brac->bracket_id )  
                                @if($i < 5) 
                                    <tr>		
                                        <td>
                                            <a href="{{ route('player.show', $points['player']->slug) }}">
                                            [{{$points['player']['team']->nickname}}] {{$points['player']->last_name}}, {{$points['player']->first_name[0]}}.
                                            </a>
                                        </td>

                                        <td align="center">
                                            {{$points->pts}}
                                        </td>
                                    </tr>

                                    <?php $i++; ?>

                                @endif
                            @endif
                       @endforeach
                    @else
                        <tr>
                            <td>Nothing to display.</td>
                        </tr>
                    @endif
                @else
                    <tr>
                        <td>Nothing to display.</td>
                    </tr>
                @endif
            
                @if($i == 0)
                    <tr>
                        <td><center>Nothing to display.</center></td>
                    </tr>
                @endif
            </table>
        </div> 

        @if($league->isShowAst == 1)
            <div class="league-leader">
                <h5>Assists Per Game</h5>

                <?php $i=0; ?>

                <table cellpadding="0" cellspacing="0" border="0">
                    @if(count($finalAstLeader) > 0)                        
                        @if($finalAstLeader[0]->ast != 0.0)
                            @foreach ($finalAstLeader as $assist)                                
                                @if($assist['player']['team']->bracket_id == $brac->bracket_id )
                                    @if($i < 5)
                                        <tr>
                                            <td>
                                                <a href="{{ route('player.show', $assist['player']->slug) }}">
                                                [{{$assist['player']['team']->nickname}}] {{$assist['player']->last_name}}, {{$assist['player']->first_name[0]}}. 
                                                </a>
                                            </td>
                                            <td align="center">
                                                {{$assist->ast}}
                                            </td>   
                                        </tr>

                                        <?php $i++; ?>      
                                    @endif      
                                @endif
                            @endforeach
                        @else
                            <tr>
                                <td><center>Nothing to display.</center></td>
                           </tr>
                        @endif
                    @else
                        <tr>
                            <td>Nothing to display.</td>
                        </tr>
                    @endif
                
                    @if($i == 0)
                        <tr>
                            <td><center>Nothing to display.</center></td>
                        </tr>
                    @endif   
                </table>
            </div>
        @endif

        @if($league->isShowReb == 1)
            <div class="league-leader">
                <h5>Rebounds Per Game</h5>

                <?php $i=0; ?>

                <table cellpadding="0" cellspacing="0" border="0">
                    @if(count($finalRebLeader) > 0)
                        @if($finalRebLeader[0]->reb != 0.0)
                            @foreach ($finalRebLeader as $rebound)
                                @if($rebound['player']['team']->bracket_id == $brac->bracket_id )
                                    @if($i < 5)
                                        <tr>
                                            <td>
                                                <a href="{{ route('player.show', $rebound['player']->slug) }}">
                                                [{{$rebound['player']['team']->nickname}}] {{$rebound['player']->last_name}}, {{$rebound['player']->first_name[0]}}.
                                                </a>
                                            </td>
                                            <td align="center">
                                                {{$rebound->reb}}
                                            </td>
                                        </tr>

                                        <?php $i++; ?>

                                    @endif   
                                @endif
                            @endforeach
                        @else
                            <tr>
                                <td><center>Nothing to display.</center></td>
                            </tr>
                        @endif
                    @else
                        <tr>
                            <td>Nothing to display.</td>
                        </tr>
                    @endif
                
                    @if($i == 0)
                        <tr>
                            <td><center>Nothing to display.</center></td>
                        </tr>
                    @endif
                </table>
            </div>
        @endif

        @if($league->isShowStl == 1)
            <div class="league-leader">
                <h5>Steals Per Game</h5>

                <?php $i=0; ?>

                <table cellpadding="0" cellspacing="0" border="0">
                    @if(count($finalStlLeader) > 0)
                        @if($finalStlLeader[0]->stl != 0.0)
                            @foreach ($finalStlLeader as $steal)
                                @if($steal['player']['team']->bracket_id == $brac->bracket_id )
                                    @if($i < 5)
                                        <tr>
                                            <td>
                                                <a href="{{ route('player.show', $steal['player']->slug) }}">
                                                [{{$steal['player']['team']->nickname}}] {{$steal['player']->last_name}}, {{$steal['player']->first_name[0]}}.
                                                </a>
                                            </td>
                                            <td align="center">
                                                {{$steal->stl}}
                                            </td>
                                        </tr>

                                        <?php $i++; ?>

                                    @endif
                                @endif
                             @endforeach
                        @else
                            <tr>
                                <td><center>Nothing to display.</center></td>
                            </tr>
                        @endif
                    @else
                        <tr>
                            <td>Nothing to display.</td>
                        </tr>
                    @endif
                
                    @if($i == 0)
                        <tr>
                            <td><center>Nothing to display.</center></td>
                        </tr>
                    @endif
                </table>
            </div>
        @endif

        @if($league->isShowBlk == 1)
            <div class="league-leader">
                <h5>Blocks Per Game</h5>

                <?php $i=0; ?>

                <table cellpadding="0" cellspacing="0" border="0">
                @if(count($finalBlkLeader) > 0)
                    @if($finalBlkLeader[0]->reb != 0.0)
                        @foreach ($finalBlkLeader as $block)
                            @if($block['player']['team']->bracket_id == $brac->bracket_id )
                                @if($i < 5)
                                    <tr>
                                        <td>
                                            <a href="{{ route('player.show', $block['player']->slug) }}">
                                            [{{$block['player']['team']->nickname}}] {{$block['player']->last_name}}, {{$block['player']->first_name[0]}}.
                                            </a>
                                        </td>
                                        <td align="center">
                                            {{$block->blk}}
                                        </td>
                                    </tr>

                                    <?php $i++; ?>

                                @endif
                            @endif
                        @endforeach
                    @else
                        <tr>
                            <td><center>Nothing to display.</center></td>
                        </tr>
                    @endif
                @else
                    <tr>
                        <td>Nothing to display.</td>
                    </tr>
                @endif 
                
                @if($i == 0)
                    <tr>
                        <td><center>Nothing to display.</center></td>
                    </tr>
                @endif
                
                </table>
            </div>   
        @endif
        <br><br><br>
    @endforeach  

{{--NO BRACKETS--}}
@else
    <div class="league-leader">
        <h5>Points Per Game</h5>
        
        <?php $i=0; ?>
        
        <table cellpadding="0" cellspacing="0" border="0">
            @if(count($finalPtsLeader) > 0)
                @if($finalPtsLeader[0]->pts != 0.0)
                    @foreach ($finalPtsLeader as $points)
                        @if($i < 5)
                            <tr>		
                                <td>
                                    <a href="{{ route('player.show', $points['player']->slug) }}">
                                    [{{$points['player']['team']->nickname}}] {{$points['player']->last_name}}, {{$points['player']->first_name[0]}}.
                                    </a>
                                </td>
                                <td align="center">
                                    {{$points->pts}}
                                </td>
                             </tr>
        
                            <?php $i++; ?>                
        
                        @endif
                    @endforeach
                @else
                    <tr>
                        <td>Nothing to display.</td>
                    </tr>
                @endif
            @else
                <tr>
                    <td>Nothing to display.</td>
                </tr>
            @endif
        </table>
    </div> 
    
    @if($league->isShowAst == 1)
        <div class="league-leader">
            <h5>Assists Per Game</h5>
            
            <?php $i=0; ?>
            
            <table cellpadding="0" cellspacing="0" border="0">
                @if(count($finalAstLeader) > 0)
                    @if($finalAstLeader[0]->ast != 0.0)
                        @foreach ($finalAstLeader as $assist)
                            @if($i < 5)
                                <tr>
                                    <td>
                                        <a href="{{ route('player.show', $assist['player']->slug) }}">
                                        [{{$assist['player']['team']->nickname}}] {{$assist['player']->last_name}}, {{$assist['player']->first_name[0]}}. 
                                        </a>
                                    </td>
                                    <td align="center">
                                        {{$assist->ast}}
                                    </td>   
                                </tr>
            
                                <?php $i++; ?>                
            
                            @endif
                        @endforeach
                    @else
                        <tr>
                            <td><center>Nothing to display.</center></td>
                        </tr>
                    @endif
                @else
                    <tr>
                        <td>Nothing to display.</td>
                    </tr>
                @endif
            </table>
        </div>
    @endif
    
    @if($league->isShowReb == 1)
        <div class="league-leader">
            <h5>Rebounds Per Game</h5>
            
            <?php $i=0; ?>
            
            <table cellpadding="0" cellspacing="0" border="0">
                @if(count($finalRebLeader) > 0)
                    @if($finalRebLeader[0]->reb != 0.0)
                        @foreach ($finalRebLeader as $rebound)
                            @if($i < 5)
                                <tr>
                                    <td>
                                        <a href="{{ route('player.show', $rebound['player']->slug) }}">
                                        [{{$rebound['player']['team']->nickname}}] {{$rebound['player']->last_name}}, {{$rebound['player']->first_name[0]}}.
                                        </a>
                                    </td>
                                    <td align="center">
                                        {{$rebound->reb}}
                                    </td>
                                </tr>
            
                                <?php $i++; ?>                
            
                            @endif
                        @endforeach
                    @else
                        <tr>
                            <td><center>Nothing to display.</center></td>
                        </tr>
                    @endif
                @else
                    <tr>
                        <td>Nothing to display.</td>
                    </tr>
                @endif
            </table>
        </div>
    @endif
    
    @if($league->isShowStl == 1)
        <div class="league-leader">
            <h5>Steals Per Game</h5>
            
            <?php $i=0; ?>
            
            <table cellpadding="0" cellspacing="0" border="0">
                @if(count($finalStlLeader) > 0)
                    @if($finalStlLeader[0]->stl != 0.0)
                        @foreach ($finalStlLeader as $steal)
                            @if($i < 5)
                                <tr>
                                    <td>
                                        <a href="{{ route('player.show', $steal['player']->slug) }}">
                                        [{{$steal['player']['team']->nickname}}] {{$steal['player']->last_name}}, {{$steal['player']->first_name[0]}}.
                                        </a>
                                    </td>
                                    <td align="center">
                                        {{$steal->stl}}
                                    </td>
                                </tr>
            
                                <?php $i++; ?> 
            
                            @endif
                        @endforeach
                    @else
                        <tr>
                            <td><center>Nothing to display.</center></td>
                        </tr>
                    @endif
                @else
                    <tr>
                        <td>Nothing to display.</td>
                    </tr>
                @endif
            </table>
        </div>
    @endif
    
    @if($league->isShowBlk == 1)
        <div class="league-leader">
            <h5>Blocks Per Game</h5>
            
            <?php $i=0; ?>
            
            <table cellpadding="0" cellspacing="0" border="0">
                @if(count($finalBlkLeader) > 0)
                    @if($finalBlkLeader[0]->reb != 0.0)
                        @foreach ($finalBlkLeader as $block)
                            @if($i < 5)
                                <tr>
                                    <td>
                                        <a href="{{ route('player.show', $block['player']->slug) }}">
                                        [{{$block['player']['team']->nickname}}] {{$block['player']->last_name}}, {{$block['player']->first_name[0]}}.
                                        </a>
                                    </td>
                                    <td align="center">
                                        {{$block->blk}}
                                    </td>
                                </tr>
            
                                <?php $i++; ?> 
            
                            @endif
                        @endforeach
                    @else
                        <tr>
                            <td><center>Nothing to display.</center></td>
                        </tr>
                    @endif
                @else
                    <tr>
                        <td>Nothing to display.</td>
                    </tr>
                @endif        
            </table>
        </div>   
    @endif
        
@endif

</center>

<h1>Top Performing Players</h1>
<center>
    <div class="mvp-container">
        <div class="mvp-title">MVP Ladder</div>
        
        <!-- Mark dito ka magforeach -->
        @foreach($spLeader as $sp)
        <div class="mvp-each">
            @if($league->hasPhotos == 1)
                <div class="mvp-img">
                    <img src="{{$sp->photo}}">
                </div>
            @endif
            
            <div class="mvp-info">
                {{$sp->last_name}}, {{$sp->first_name}}<br>
                {{$sp['team']->display_name}} [{{$sp->position}}]<br>
                <b style="font-size: 18pt;">{{$sp->statistical_points}} SP</b><br>
            </div>

            <div class="mvp-careerstats-container">
                <div class="mvp-careerstats"> 
                    <div class="mvp-stats-label">
                        PPG
                    </div> 
                    <b>{{$sp['careerstats']->pts}}</b> 
                </div>

                <div class="mvp-careerstats"> 
                    <div class="mvp-stats-label">
                        RPG
                    </div> 
                    <b>{{$sp['careerstats']->reb}}</b> 
                </div>
                
                <div class="mvp-careerstats"> 
                    <div class="mvp-stats-label">
                        APG
                    </div> 
                    <b>{{$sp['careerstats']->ast}}</b> 
                </div>             
            </div>

     
            
        </div>
        @endforeach
        <!-- dito endforeach -->
    </div>
    
    <div class="mvp-container">
        <div class="mvp-title">Defensive Player Tracker</div>
        
        <!-- Mark dito ka magforeach -->
        @foreach($dspLeader as $sp)
        <div class="mvp-each">
            @if($league->hasPhotos == 1)
                <div class="mvp-img">
                    <img src="{{$sp->photo}}">
                </div>
            @endif
            <div class="mvp-info">
                {{$sp->last_name}}, {{$sp->first_name}}<br>
                {{$sp['team']->display_name}} [{{$sp->position}}]<br>
                <b style="font-size: 18pt;">{{$sp->defensive_statistical_points}}  SP</b><br>
            </div>
                
            <div class="mvp-careerstats-container">
                <div class="mvp-careerstats"> 
                    <div class="mvp-stats-label">
                        RPG
                    </div> 
                    <b>{{$sp['careerstats']->reb}}</b> 
                </div>
                <div class="mvp-careerstats"> 
                    <div class="mvp-stats-label">
                        SPG
                    </div> 
                    <b>{{$sp['careerstats']->stl}}</b> 
                </div>
                <div class="mvp-careerstats">  
                    <div class="mvp-stats-label">
                        BPG
                    </div> 
                    <b>{{$sp['careerstats']->blk}}</b> 
                </div>
            </div>
        </div>
        @endforeach
        <!-- dito endforeach -->
    </div>
    
</center>

<h1>Teams</h1>
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
   

@else
<!-- If league has no brackets -->

<center>    
    <!-- Start your foreach for teams inside this bracket here-->
    @foreach($team as $teams) 
        <!-- Set the width and height of each team-->
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
    
    @endforeach
    <!-- End foreach for teams inside this bracket here-->
</center>
<br>
@endif