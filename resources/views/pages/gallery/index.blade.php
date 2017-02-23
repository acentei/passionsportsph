@extends('layout.header')

@section('title')
    Home: Gallery
@stop

@include('layout.navi')

@section('content')

@if(Auth::user() && Session::get('selectedLeague') > 0) 
    <br><br><br><br>
    <center><a href="{{ route('gallery.create') }}" class="button">UPLOAD IMAGE</a></center>
    <br>
@elseif (empty(Session::get('selectedLeague')))
    <br><br><br>
     <center>Please Select a League from the League Selector.</center>
@endif

<div class="gallery-container">
    @foreach($gallery as $gal)
    <div class="gallery">
        <img src="{{$gal->photo}}" alt="{{$gal->caption}}">
        @if(Auth::user()) 
            <div class="gallery-button">
                    {!! Form::open([
                        'method' => 'DELETE',
                        'route' => ['gallery.destroy', $gal->gallery_id]
                        ]) !!}
                        {!! Form::button('&#128465;', 
                        array('type' => 'submit', 
                        'class' => 'btn-del')) !!}

                        {!! Form::close() !!}
            </div>
        @endif
    </div>
    @endforeach
</div>


@endsection

