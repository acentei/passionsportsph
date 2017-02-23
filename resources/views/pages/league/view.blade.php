@extends('layout.master')

@section('title')
    League : {{strtoupper($league->display_name)}}
@stop

@include('layout.navi')

@section('extra')
    <a href="#team" class="team-nav" rel="team">Team</a>
    <a href="#rules-and-regulation" class="team-nav" rel="rules">Rules</a>
    <a href="#league-standing" class="team-nav" rel="standing">Standing</a>
@stop

@section('content')


<div id="team" class="page active">
    {!! view ('pages.league.teamview', ['league' => $league, 'team' => $team, 'finalPtsLeader' => $finalPtsLeader, 'finalRebLeader' => $finalRebLeader, 'finalAstLeader' => $finalAstLeader, 'finalStlLeader' => $finalStlLeader, 'finalBlkLeader' => $finalBlkLeader, 'spLeader' => $spLeader, 'dspLeader' => $dspLeader,'bracket' => $bracket,'allbracket' => $allbracket]) !!}   
</div>

<div id="rules" class="page"> 
	{!! view ('pages.league.rulesandregulation', ['regulation' => $regulation]) !!}
</div>

<div id="standing" class="page">  
    {!! view ('pages.league.standings', ['leagueStanding' => $leagueStanding, 'league' => $league, 'bracket' => $bracket]) !!}
</div>



<script>
    
$(function() {
$('.team-nav').on('click', function()
    {	
        var panelToShow = $(this).attr('rel');
        $('.page.active').removeClass('active');
        $('#'+panelToShow).addClass('active');
	
    });
    	
    });
    
</script>

@endsection