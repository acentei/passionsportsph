@extends('layout.master')

@section('title')
    Team : Edit
@stop

@include('layout.navi')

@section('content')

{{-- SHOW ERRORS --}}
@include('partials.alerts.error')

{!! Form::model($team, [
        'method' => 'PATCH',
        'route' => ['team.update', $team->team_id],
        'files' => 'true'
    ]) !!}

<h1>Edit Team: {{$team->display_name}}</h1>

<div id="errortask">
</div>

<b>Team League: {{$team["league"]->display_name}} </b>

<br><br>


<input type="hidden" value="{{Session::get('selectedLeague')}}" name="league" />


    <div class="col">
        <div class="col-title">Team Name: </div>
            <input id="col-border" type="text" name="name" maxlength = "40" value="{{$team->display_name}}" />
    </div>

    <div class="col">
        <div class="col-title">Team Nickname: </div>
            <input id="col-border" type="text" name="nickname" minlength="3" maxlength = "3" required value="{{$team->nickname}}" />
    </div>

    @if($league->hasBracket == 1)
        <div class="col">
            <div class="col-title">Team Bracket: </div>        
            {!!Form:: select('bracket',$bracket,$team->bracket_id, ['id' => 'dropdown', 'class' => 'choices','placeholder' => '-- Select --']) !!}
        </div>
    @endif

    @if($league->hasPhotos == 1)
        <div class="col">
            <div class="col-title">Team Logo: </div>
                <input id="fileUpload" type="file" name="images">
        </div>

        <div class="col">
            <div class="col-title" style="vertical-align:top;">Preview: </div>
            <div id="preview" style="display:inline;">
            </div>
        </div>
    @endif

    <div class="col">
        <div class="col-title">Team Status: </div>
        {!! Form::select('status', ['Normal' => "Normal", 'Fail' => "Fail"], $team->league_status, ['id'=>'dropdown','class' => 'choices','required']) !!} 
    </div>

    <div> 
        <br><br>
        <input type="submit" class="btn-flat" value="Update"> 
        <a href="{{ route('team.show', $team->slug) }}" class="button">Cancel</a>
        <br><br>
    </div>


<script>
$("#fileUpload").on('change', function () {

     //Get count of selected files
     var countFiles = $(this)[0].files.length;

     var imgPath = $(this)[0].value;
     var extn = imgPath.substring(imgPath.lastIndexOf('.') + 1).toLowerCase();
     var image_holder = $("#preview");
     image_holder.empty();

     if (extn == "gif" || extn == "png" || extn == "jpg" || extn == "jpeg") {
         if (typeof (FileReader) != "undefined") {

             //loop for each file selected for uploaded.
             for (var i = 0; i < countFiles; i++) {

                 var reader = new FileReader();
                 reader.onload = function (e) {
                     $("<img />", {
                         "src": e.target.result
                     }).appendTo(image_holder);
                 }

                 image_holder.show();
                 reader.readAsDataURL($(this)[0].files[i]);
             }

         } else {
             alert("This browser does not support FileReader.");
         }
     } else {
         alert("Pls select only images");
     }
 });
</script>
@endsection