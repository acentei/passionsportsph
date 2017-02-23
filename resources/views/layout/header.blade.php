<!DOCTYPE html>
<html lang="en">
    <head>
        <title> @yield('title') </title> 
    </head>
    <!-- Meta -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
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
    
    <!-- Include jQuery Library -->   
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script src="{{ URL::asset('/js/flickity.pkgd.min.js') }}"></script>
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
            
            <div id="container-gallery">
                    @yield('content')
            </div>
        </body>
    
        <div id="footer">
            © Passion Sports PH
        </div>
</html>