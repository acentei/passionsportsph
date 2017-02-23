@extends('layout.master')

@section('title')
    Home: Team Brackets
@stop

@include('layout.navi')

@section('content')

@if(Auth::user())
    <br>
    <a href="{{ route('bracket.create') }}" class="button">Create New Bracket</a>
    <br><br>
@endif

<table cellpadding="0" cellspacing="0" border="0">
    <thead>
        <tr>
            <th>Bracket Name</th>
            <th>Actions</th>
            <th></th>
        </tr>
    </thead>
    
    <tbody>
        @foreach($bracket as $bra)
        <tr>
            <td>{{$bra->display_name}}</td>
            <td style="width: 80px;">
                <a href="{{ route('bracket.edit',$bra->bracket_id)}}" class="btn-flat" role="button" style="max-width: 50px; margin-top: 5px;"> Edit </a>  
            </td>
            <td>
                  {!! Form::open([
                    'method' => 'DELETE',
                    'route' => ['bracket.destroy', $bra->bracket_id]
                  ]) !!}

                  {!! Form::button('Delete', array('type' => 'submit', 'class' => 'btn-flat', 'style' => 'margin-top: 5px;')) !!}

                  {!! Form::close() !!}
            </td> 
        </tr>
        @endforeach
    </tbody>
</table>

@endsection