@extends('layout.master')

@section('title')
    Home: Moderator
@stop

@include('layout.navi')

@section('content')

<br>
    <a href="{{ route('moderator.create') }}" class="button">Create New Moderator</a>
<br><br>

<h1>Moderators:</h1>
			 
<table cellpadding="0" cellspacing="0" border="0">
    <thead>
        <tr>
            <th>Username</th>
            <th>First Name</th>
            <th>Middle Name</th>
            <th>Last Name</th>
	    <th>Handled League</th>
            <th>Actions</th>
            <th></th>
            <th></th>
        </tr>
    </thead>
    
    <tbody>
			@foreach($moderator as $mod)
        <tr>
            <td>{{ $mod->email }}</td>
            <td>{{ $mod->first_name }}</td>
            <td>{{ $mod->middle_name }}</td>
            <td>{{ $mod->last_name }}</td>
	    @if(!empty($mod['league']))
                <td>{{ $mod['league']->display_name }}</td>
            @else
                <td>All</td>
            @endif
            <td>
                <a href="{{ route('moderator.edit', $mod->account_id) }}" class="btn-flat" role="button" style="max-width: 50px;"> Edit </a>  
            </td>
            <td>
                      {!! Form::open([
			            'method' => 'DELETE',
			            'route' => ['moderator.destroy', $mod->account_id]
                      ]) !!}
                      
                      {!! Form::button('Delete', array('type' => 'submit', 'class' => 'btn-flat')) !!}
                      
                      {!! Form::close() !!}
                  </td> 
            <td>
                @if ($mod->active == 1)
                <a href="{{ route('deactivate', $mod->account_id) }}" class="btn-mod-deactive">Deactivate</a>
                @else
                <a href="{{ route('activate', $mod->account_id) }}" class="btn-mod-active">Activate</a>
                @endif
            </td>
        </tr>
            @endforeach
    </tbody>
</table>

@endsection