@extends('layout.header')

@section('title')
    Passion Sports : About
@stop

@include('layout.navi')

@section('content')

<body class="body-welcome">
    <div class="content-welcome">
        @if(Auth::user()) 
		@if((Auth::user()->account_type == "Admin") && (Auth::user()->handled_league == 0))

        <div class="page-btn-position">
            <br><br>    
                [<a href="{{ route('about.edit', $about[0]->about_id) }}" class="yin">Edit About Page</a>]
        </div>
		 @endif
        @endif
        
        @if(count($about) > 0)
        <div class="title">About Passion Sports</div>

        <p>
            {!! $about[0]->about_website !!}            
        </p>
        @else
        <div class="title">About Passion Sports</div>
        <p>
            Nothing to display
        </p>
        @endif

    </div>
</body>
@endsection
