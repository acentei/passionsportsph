@extends('layout.master')

@section('title')
    Statistic Leaders
@stop

@include('layout.navi')

@section('content')

{{-- CONTROL NUMBER OF TOP PLAYERS TO DISPLAY--}}        
@if($teamCount >= 20)
    <?php $topCount=20; ?>
@else
   <?php $topCount=15; ?>
@endif

<h1>Top {{$topCount}} Players by statistics</h1>

<center>
    @if (Session::get('selectedLeague') == 0)
        <br>
         Please Select a League from the League Selector.
    @else   
        
        @if($league->hasBracket == 1) 
            @foreach($bracket as $bra)
    
                <h6>{{$bra->display_name}}</h6>
                
                {{-- POINTS LEADER--}}
                <div class="league-leader top-stats">
                    <h5 style="font-size: 11pt;line-height: 30px;">Points Per Game</h5>

                    <?php $i=0; ?>

                    <table cellpadding="8" cellspacing="0" border="0">
                        @if(count($ptsLeader) > 0)                        
                            @if($ptsLeader[0]->pts != 0.0)                                                
                                @foreach ($ptsLeader as $stat)                                
                                    @if($stat['player']['team']->bracket_id == $bra->bracket_id )  
                                        @if($i < $topCount)                                                
                                            @if($stat->pts > 0)
                                                <tr>                                    
                                                    <td>
                                                        <a href="{{ route('player.show', $stat['player']->slug) }}">
                                                            [{{$stat['player']['team']->nickname}}] {{$stat['player']->last_name}}, {{$stat['player']->first_name}}
                                                        </a>
                                                    </td>

                                                    <td align="right" style="font-size: 11pt;">
                                                        {{$stat->pts}}
                                                    </td>
                                                </tr>

                                                <?php $i++; ?>

                                            @endif     
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


                @if($league->isShowAst == 1)
                    {{-- ASSISTS LEADER--}}
                    <div class="league-leader top-stats">
                        <h5 style="font-size: 11pt;line-height: 30px;">Assists Per Game</h5>

                        <?php $i=0; ?>

                        <table cellpadding="8" cellspacing="0" border="0">
                            @if(count($astLeader) > 0)
                                @if($astLeader[0]->ast != 0.0)
                                    @foreach ($astLeader as $stat)
                                        @if($stat['player']['team']->bracket_id == $bra->bracket_id )
                                            @if($i < $topCount) 
                                                @if($stat->ast > 0)
                                                    <tr>
                                                        <td>
                                                            <a href="{{ route('player.show', $stat['player']->slug) }}">
                                                                [{{$stat['player']['team']->nickname}}] {{$stat['player']->last_name}}, {{$stat['player']->first_name}}
                                                            </a>
                                                        </td>

                                                        <td align="right" style="font-size: 11pt;">
                                                            {{$stat->ast}}
                                                        </td>                                                    
                                                    </tr>


                                                    <?php $i++; ?>

                                                @endif
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
                    {{-- REBOUNDS LEADER--}}
                    <div class="league-leader top-stats">
                        <h5 style="font-size: 11pt;line-height: 30px;">Rebounds Per Game</h5>

                        <?php $i=0; ?>

                        <table cellpadding="8" cellspacing="0" border="0">
                            @if(count($rebLeader) > 0)
                                @if($rebLeader[0]->reb != 0.0)
                                    @foreach ($rebLeader as $stat)
                                        @if($stat['player']['team']->bracket_id == $bra->bracket_id )
                                            @if($i < $topCount) 
                                                @if($stat->reb > 0)
                                                    <tr>
                                                        <td>
                                                            <a href="{{ route('player.show', $stat['player']->slug) }}">
                                                                [{{$stat['player']['team']->nickname}}] {{$stat['player']->last_name}}, {{$stat['player']->first_name}}.
                                                            </a>
                                                        </td>

                                                        <td align="right" style="font-size: 11pt;">
                                                            {{$stat->reb}}
                                                        </td>
                                                    </tr>

                                                    <?php $i++; ?>

                                                @endif
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
                    {{-- STEALS LEADER--}}
                    <div class="league-leader top-stats">
                        <h5 style="font-size: 11pt;line-height: 30px;">Steals Per Game</h5>

                        <?php $i=0; ?>

                        <table cellpadding="8" cellspacing="0" border="0">
                            @if(count($stlLeader) > 0)
                                @if($stlLeader[0]->stl != 0.0)
                                    @foreach ($stlLeader as $stat)
                                        @if($stat['player']['team']->bracket_id == $bra->bracket_id )
                                            @if($i < $topCount)
                                                @if($stat->stl > 0)
                                                    <tr>
                                                        <td>
                                                            <a href="{{ route('player.show', $stat['player']->slug) }}">
                                                                [{{$stat['player']['team']->nickname}}] {{$stat['player']->last_name}}, {{$stat['player']->first_name}}
                                                            </a>
                                                        </td>

                                                        <td align="right" style="font-size: 11pt;">
                                                            {{$stat->stl}}
                                                        </td>
                                                    </tr>

                                                    <?php $i++; ?>

                                                @endif
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
                    {{-- BLOCKS LEADER--}}
                    <div class="league-leader top-stats">
                        <h5 style="font-size: 11pt;line-height: 30px;">Blocks Per Game</h5>

                        <?php $i=0; ?>

                        <table cellpadding="8" cellspacing="0" border="0">
                            @if(count($blkLeader) > 0)
                                @if($blkLeader[0]->blk != 0.0)
                                    @foreach ($blkLeader as $stat)
                                        @if($stat['player']['team']->bracket_id == $bra->bracket_id )
                                            @if($i < $topCount)
                                                @if($stat->blk > 0)                    
                                                    <tr>
                                                        <td>
                                                            <a href="{{ route('player.show', $stat['player']->slug) }}">
                                                                [{{$stat['player']['team']->nickname}}] {{$stat['player']->last_name}}, {{$stat['player']->first_name}}
                                                            </a>
                                                        </td>

                                                        <td align="right" style="font-size: 11pt;">
                                                            {{$stat->blk}}
                                                        </td>
                                                    </tr>

                                                    <?php $i++; ?>

                                                @endif
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

                @if($league->isShowFgp == 1)
                    {{-- Field Goals % LEADER--}}
                    <div class="league-leader top-stats">
                        <h5 style="font-size: 11pt;line-height: 30px;">Field Goal % Per Game </h5>

                         <?php $i=0; ?>

                        <table cellpadding="8" cellspacing="0" border="0">
                            @if(count($fgpLeader) > 0)
                                @if($fgpLeader[0]->fgp != 0.0)
                                    @foreach ($fgpLeader as $stat)
                                        @if($stat['player']['team']->bracket_id == $bra->bracket_id )
                                            @if($i < $topCount)
                                                @if($stat->fgp > 0)
                                                    <tr>
                                                        <td>
                                                            <a href="{{ route('player.show', $stat['player']->slug) }}">
                                                                [{{$stat['player']['team']->nickname}}] {{$stat['player']->last_name}}, {{$stat['player']->first_name}}
                                                            </a>
                                                        </td>

                                                        <td align="right" style="font-size: 11pt;">
                                                            {{$stat->fgp}}%
                                                        </td>
                                                    </tr>

                                                    <?php $i++; ?>

                                                @endif
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

                @if($league->isShow3pm == 1)
                    {{-- 3-points made LEADER--}}
                    <div class="league-leader top-stats">
                        <h5 style="font-size: 11pt;line-height: 30px;">3-Points Made</h5>

                        <?php $i=0; ?>

                        <table cellpadding="8" cellspacing="0" border="0">
                            @if(count($pm3Leader) > 0)
                                @if($pm3Leader[0]->pm3 != 0.0)
                                    @foreach ($pm3Leader as $stat)
                                        @if($stat['player']['team']->bracket_id == $bra->bracket_id )
                                            @if($i < $topCount)
                                                @if($stat->pm3 > 0)
                                                    <tr>
                                                        <td>
                                                            <a href="{{ route('player.show', $stat['player']->slug) }}">
                                                                [{{$stat['player']['team']->nickname}}] {{$stat['player']->last_name}}, {{$stat['player']->first_name}}
                                                            </a>
                                                        </td>

                                                        <td align="right" style="font-size: 11pt;">
                                                            {{$stat->pm3}}
                                                        </td>
                                                    </tr>

                                                    <?php $i++; ?>

                                                @endif
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

                @if($league->isShowFtm == 1)
                    {{-- Free throws made LEADER--}}
                    <div class="league-leader top-stats">
                        <h5 style="font-size: 11pt;line-height: 30px;">Free Throws Made</h5>

                        <?php $i=0; ?>

                        <table cellpadding="8" cellspacing="0" border="0">
                            @if(count($ftmLeader) > 0)
                                @if($ftmLeader[0]->ftm != 0.0)
                                    @foreach ($ftmLeader as $stat)
                                        @if($stat['player']['team']->bracket_id == $bra->bracket_id )
                                            @if($i < $topCount)
                                                @if($stat->ftm > 0)
                                                    <tr>
                                                        <td>
                                                            <a href="{{ route('player.show', $stat['player']->slug) }}">
                                                                [{{$stat['player']['team']->nickname}}] {{$stat['player']->last_name}}, {{$stat['player']->first_name}}
                                                            </a>
                                                        </td>

                                                        <td align="right" style="font-size: 11pt;">
                                                            {{$stat->ftm}}
                                                        </td>
                                                    </tr>

                                                    <?php $i++; ?>                    

                                                @endif
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

                @if($league->isShowFtp == 1)
                    {{-- Free throws % LEADER--}}
                    <div class="league-leader top-stats">
                        <h5 style="font-size: 11pt;line-height: 30px;">Free Throw % Per Game</h5>

                        <?php $i=0; ?>

                        <table cellpadding="8" cellspacing="0" border="0">
                            @if(count($ftpLeader) > 0)
                                @if($ftpLeader[0]->ftp != 0.0)
                                    @foreach ($ftpLeader as $stat)
                                        @if($stat['player']['team']->bracket_id == $bra->bracket_id )
                                            @if($i < $topCount)
                                                @if($stat->ftp > 0)
                                                    <tr>
                                                        <td>
                                                            <a href="{{ route('player.show', $stat['player']->slug) }}">
                                                                [{{$stat['player']['team']->nickname}}] {{$stat['player']->last_name}}, {{$stat['player']->first_name}}
                                                            </a>
                                                        </td>

                                                        <td align="right" style="font-size: 11pt;">
                                                            {{$stat->ftp}}%
                                                        </td>
                                                    </tr>

                                                    <?php $i++; ?>         

                                                @endif
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
                <br><br><br><br>
            @endforeach
              
    {{---------------------- NO BRACKET ---------------------}}             
        @else
            {{-- POINTS LEADER--}}
            <div class="league-leader top-stats">
                <h5 style="font-size: 11pt;line-height: 30px;">Points Per Game</h5>

                <?php $i=0; ?>
                
                <table cellpadding="8" cellspacing="0" border="0">
                    @if(count($ptsLeader) > 0)
                        @if($ptsLeader[0]->pts != 0.0)
                            @foreach ($ptsLeader as $stat)
                                @if($i < $topCount) 
                                    @if($stat->pts > 0)
                                        <tr>                                    
                                            <td>
                                                <a href="{{ route('player.show', $stat['player']->slug) }}">
                                                    [{{$stat['player']['team']->nickname}}] {{$stat['player']->last_name}}, {{$stat['player']->first_name}}
                                                </a>
                                            </td>

                                            <td align="right" style="font-size: 11pt;">
                                                {{$stat->pts}}
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
                </table>
            </div> 

            @if($league->isShowAst == 1)
                {{-- ASSISTS LEADER--}}
                <div class="league-leader top-stats">
                    <h5 style="font-size: 11pt;line-height: 30px;">Assists Per Game</h5>
                    
                    <?php $i=0; ?>

                    <table cellpadding="8" cellspacing="0" border="0">
                        @if(count($astLeader) > 0)
                            @if($astLeader[0]->ast != 0.0)
                                @foreach ($astLeader as $stat)
                                    @if($i < $topCount) 
                                        @if($stat->ast > 0)
                                            <tr>
                                                <td>
                                                    <a href="{{ route('player.show', $stat['player']->slug) }}">
                                                        [{{$stat['player']['team']->nickname}}] {{$stat['player']->last_name}}, {{$stat['player']->first_name}}
                                                    </a>
                                                </td>

                                                <td align="right" style="font-size: 11pt;">
                                                    {{$stat->ast}}
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
                    </table>
                </div>    
            @endif  

            @if($league->isShowReb == 1)
                {{-- REBOUNDS LEADER--}}
                <div class="league-leader top-stats">
                    <h5 style="font-size: 11pt;line-height: 30px;">Rebounds Per Game</h5>

                    <?php $i=0; ?>
                    
                    <table cellpadding="8" cellspacing="0" border="0">
                        @if(count($rebLeader) > 0)
                            @if($rebLeader[0]->reb != 0.0)
                                @foreach ($rebLeader as $stat)
                                    @if($i < $topCount)
                                        @if($stat->reb > 0)
                                            <tr>
                                                <td>
                                                    <a href="{{ route('player.show', $stat['player']->slug) }}">
                                                        [{{$stat['player']['team']->nickname}}] {{$stat['player']->last_name}}, {{$stat['player']->first_name}}.
                                                    </a>
                                                </td>

                                                <td align="right" style="font-size: 11pt;">
                                                    {{$stat->reb}}
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
                    </table>
                </div>
            @endif

            @if($league->isShowStl == 1)
                {{-- STEALS LEADER--}}
                <div class="league-leader top-stats">
                    <h5 style="font-size: 11pt;line-height: 30px;">Steals Per Game</h5>

                    <?php $i=0; ?>
                    
                    <table cellpadding="8" cellspacing="0" border="0">
                        @if(count($stlLeader) > 0)
                            @if($stlLeader[0]->stl != 0.0)
                                @foreach ($stlLeader as $stat)
                                    @if($i < $topCount)
                                        @if($stat->stl > 0)
                                            <tr>
                                                <td>
                                                    <a href="{{ route('player.show', $stat['player']->slug) }}">
                                                        [{{$stat['player']['team']->nickname}}] {{$stat['player']->last_name}}, {{$stat['player']->first_name}}
                                                    </a>
                                                </td>

                                                <td align="right" style="font-size: 11pt;">
                                                    {{$stat->stl}}
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
                    </table>
                </div> 
            @endif    

            @if($league->isShowBlk == 1)
                {{-- BLOCKS LEADER--}}
                <div class="league-leader top-stats">
                    <h5 style="font-size: 11pt;line-height: 30px;">Blocks Per Game</h5>

                    <?php $i=0; ?>
                    
                    <table cellpadding="8" cellspacing="0" border="0">
                        @if(count($blkLeader) > 0)
                            @if($blkLeader[0]->blk != 0.0)
                                @foreach ($blkLeader as $stat)
                                    @if($i < $topCount)
                                        @if($stat->blk > 0)                    
                                            <tr>
                                                <td>
                                                    <a href="{{ route('player.show', $stat['player']->slug) }}">
                                                        [{{$stat['player']['team']->nickname}}] {{$stat['player']->last_name}}, {{$stat['player']->first_name}}
                                                    </a>
                                                </td>

                                                <td align="right" style="font-size: 11pt;">
                                                    {{$stat->blk}}
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
                    </table>
                </div>
            @endif

            @if($league->isShowFgp == 1)
                {{-- Field Goals % LEADER--}}
                <div class="league-leader top-stats">
                    <h5 style="font-size: 11pt;line-height: 30px;">Field Goal % Per Game </h5>

                    <?php $i=0; ?>
                    
                    <table cellpadding="8" cellspacing="0" border="0">
                        @if(count($fgpLeader) > 0)
                            @if($fgpLeader[0]->fgp != 0.0)
                                @foreach ($fgpLeader as $stat)
                                    @if($i < $topCount)
                                        @if($stat->fgp > 0)
                                            <tr>
                                                <td>
                                                    <a href="{{ route('player.show', $stat['player']->slug) }}">
                                                        [{{$stat['player']['team']->nickname}}] {{$stat['player']->last_name}}, {{$stat['player']->first_name}}
                                                    </a>
                                                </td>

                                                <td align="right" style="font-size: 11pt;">
                                                    {{$stat->fgp}}%
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
                    </table>
                </div>
            @endif

            @if($league->isShow3pm == 1)
                {{-- 3-points made LEADER--}}
                <div class="league-leader top-stats">
                    <h5 style="font-size: 11pt;line-height: 30px;">3-Points Made</h5>

                    <?php $i=0; ?>
                    
                    <table cellpadding="8" cellspacing="0" border="0">
                        @if(count($pm3Leader) > 0)
                            @if($pm3Leader[0]->pm3 != 0.0)
                                @foreach ($pm3Leader as $stat)
                                    @if($i < $topCount)                                    
                                        @if($stat->pm3 > 0)
                                            <tr>
                                                <td>
                                                    <a href="{{ route('player.show', $stat['player']->slug) }}">
                                                        [{{$stat['player']['team']->nickname}}] {{$stat['player']->last_name}}, {{$stat['player']->first_name}}
                                                    </a>
                                                </td>

                                                <td align="right" style="font-size: 11pt;">
                                                    {{$stat->pm3}}
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
                    </table>
                </div>
            @endif
        
            @if($league->isShowFtm == 1)
                {{-- Free throws made LEADER--}}
                <div class="league-leader top-stats">
                    <h5 style="font-size: 11pt;line-height: 30px;">Free Throws Made</h5>

                    <?php $i=0; ?>
                    
                    <table cellpadding="8" cellspacing="0" border="0">
                        @if(count($ftmLeader) > 0)
                            @if($ftmLeader[0]->ftm != 0.0)
                                @foreach ($ftmLeader as $stat)
                                    @if($i < $topCount)
                                        @if($stat->ftm > 0)
                                            <tr>
                                                <td>
                                                    <a href="{{ route('player.show', $stat['player']->slug) }}">
                                                        [{{$stat['player']['team']->nickname}}] {{$stat['player']->last_name}}, {{$stat['player']->first_name}}
                                                    </a>
                                                </td>

                                                <td align="right" style="font-size: 11pt;">
                                                    {{$stat->ftm}}
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
                    </table>
                </div>
            @endif
    
            @if($league->isShowFtp == 1)
                {{-- Free throws % LEADER--}}
                <div class="league-leader top-stats">
                    <h5 style="font-size: 11pt;line-height: 30px;">Free Throw % Per Game</h5>

                    <?php $i=0; ?>
                    
                    <table cellpadding="8" cellspacing="0" border="0">
                        @if(count($ftpLeader) > 0)
                            @if($ftpLeader[0]->ftp != 0.0)
                                @foreach ($ftpLeader as $stat)
                                    @if($i < $topCount)
                                        @if($stat->ftp > 0)
                                            <tr>
                                                <td>
                                                    <a href="{{ route('player.show', $stat['player']->slug) }}">
                                                        [{{$stat['player']['team']->nickname}}] {{$stat['player']->last_name}}, {{$stat['player']->first_name}}
                                                    </a>
                                                </td>

                                                <td align="right" style="font-size: 11pt;">
                                                    {{$stat->ftp}}%
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
                    </table>
                </div>
            @endif
                        
        @endif {{--END IF FOR BRACKET CHECK--}}
    @endif
</center>

@endsection