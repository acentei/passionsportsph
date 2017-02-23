@extends('layout.master')

@section('title')
    EDIT PAGE
@stop

@include('layout.navi')


<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
<div class="navbar">
    
        <a href="#" id="btnLeague" class="active">League</a>
        <a href="#" id="btnSchedule" >Schedules</a>
        <a href="#" id="btnVenue" >Venue</a>
        <a href="#" id="btnGallery" >Gallery</a>
    </div>

@section('content')

$('.navbar a').on('click', function() {
            var navPrev = $('.navbar').find('.active');
            $(navPrev).removeClass('active');
            
            var navNow = $(this).attr('id'); 
            $('#' + navNow).addClass('active');
        });

@endsection