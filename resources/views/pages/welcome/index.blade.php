@extends('layout.header')

@section('title')
    Passion Sports : Welcome
@stop

@include('layout.navi')

@section('content')

<body class="body-welcome">
    <div class="content-welcome">
        
        @if(Auth::user()) 
        <div class="page-btn-position">
            <br><br>      
                [<a href="#" class="yin">Edit Welcome Page</a>]
        </div>
         @endif
        
        <div class="title">Welcome to Passion Sports</div>

    <p>
        Here lies the content to welcome the people who view Passion Sports. Please dont forget to edit this page. Thank you! Here lies the content to welcome the people who view Passion Sports. Please dont forget to edit this page. Thank you!
        Here lies the content to welcome the people who view Passion Sports. Please dont forget to edit this page. Thank you!
        Here lies the content to welcome the people who view Passion Sports. Please dont forget to edit this page. Thank you!
        Here lies the content to welcome the people who view Passion Sports. Please dont forget to edit this page. Thank you!
    </p>
    </div>
</body>
@endsection