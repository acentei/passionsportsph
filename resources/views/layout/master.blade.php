<!DOCTYPE html>
<html lang="en">
    <head>
        <title> @yield('title') </title> 
    </head>
    <!-- Meta -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">


        <meta name="author" content="Passion Sports PH">
        <meta name="keywords" content="passionsportsph,passion sports events production,passion sports, passion league, basketball">
        
        <meta property="og:url"           content="http://www.passionsportsph.com/" />
        <meta property="og:type"          content="website" />
        <meta property="og:title"         content="Passion Sports PH | We are Fueled by Passion." />
        <meta property="og:description"   content="PASSION SPORTS EVENTS PRODUCTION is an all-inclusive active lifestyle company. Our company is very diversified and reaches into virtually all corners of the active lifestyle market. We offer events production & management, marketing, sports organization, sports training and equipment rentals. We specializes in, event management, marketing and promotional solutions. Our expertise includes basketball tournaments, volleyball tournaments, and soccer tournaments and lots more." />
        <meta property="og:image"         content="http://www.passionsportsph.com/images/PSLogo4.png" />

    
    <!-- CSS -->
    <link rel="stylesheet" href="{{ URL::asset('/css/mystyle.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('/css/mobile-xs.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('/css/mobile-small.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('/css/mobile-med.css') }}">
    <link href="{{ URL::asset('/css/flickity.css') }}" rel="stylesheet">
    <!-- Iphone 5 -->
    <link rel="stylesheet" href="{{ URL::asset('/css/mobile-iphone5.css') }}">
    <!-- Tablet -->
    <link rel="stylesheet" href="{{ URL::asset('/css/tablet.css') }}">
    
    <!-- TINY MCE (WYSIWYG editor)--> 
    <script src="{!! asset('/css/tinymce/js/tinymce/tinymce.min.js') !!}"></script>
    
    <!-- Include jQuery Library -->
    <script src="{{ URL::asset('/js/flickity.pkgd.min.js') }}"></script>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">  
    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>

    
    <!-- Navigation Bar, id=topbar remains on top-->
    <script type='text/javascript'>
        $(window).load(function(){
        $(window).scroll(function () { 
            if(($(".navbar").position().top - $(window).scrollTop()) <= 0)
               {
                   $("#topbar").addClass("stayontop");
               }
               else
               {
                   $("#topbar").removeClass("stayontop");
               }

            });
        });
    
    </script>
       
        <body>
            <div id="topbar">
                @yield('navigation')
            </div>
            
            <div id="team-button">
            @yield('extra')
            </div>
            
            
            <div id="container">

                <div class="content mobile-content">
                    @if(Session::has('flash_message'))
                        <br>
                        <div id="flash_success" class="alert-box-success">
                            <i><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span></i> <b>{{ Session::get('flash_message') }}</b>
                        </div>  
                    
                    @endif

                    @if(Session::has('error_message'))
                        <br>
                        <div id="flash_danger" class="alert-box-error">
                            <i><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span></i> <b>{{ Session::get('error_message') }}</b>
                        </div> 
                    
                    @endif
                    
                     @yield('content')
                </div>
                
            </div>
            
        </body>
    
    <div id="footer">
            © Passion Sports PH
            </div>
</html>