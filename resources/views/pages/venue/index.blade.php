@extends('layout.master')

@section('title')
    Home: Venue
@stop

@include('layout.navi')

@section('content')

@if(Auth::user())
<br>
<a href="{{ route('venue.create') }}" class="button">Create New Venue</a>
<br><br>
@endif
<table cellpadding="0" cellspacing="0" border="0">
    <thead>
        <tr>
            <th>Venue Name</th>
            <th>Venue Address</th>
            <th>Actions</th>
            <th></th>
        </tr>
    </thead>
    
    <tbody>
			@foreach($venue as $venue) 
        <tr>
            <td>{{ $venue->display_name }}</td>
            <td>{{ $venue->address }}</td>
            <td>
                <a href="{{ route('venue.edit', $venue->venue_id) }}" class="button">Edit</a>
            </td>
                            <td>
                      {!! Form::open([
			            'method' => 'DELETE',
			            'route' => ['venue.destroy', $venue->venue_id]
                      ]) !!}
                      
                      {!! Form::button('Delete', array('type' => 'submit', 'class' => 'btn-flat')) !!}
                      
                      {!! Form::close() !!}
                  </td> 
        </tr>
        @endforeach
    </tbody>
    
    
</table>

@endsection