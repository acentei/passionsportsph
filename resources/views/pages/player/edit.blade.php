@extends('layout.master')

@section('title')
    Player : Edit
@stop

@include('layout.navi')

@section('content')

{{-- SHOW ERRORS --}}
@include('partials.alerts.error')

{!! Form::model($player, [
        'method' => 'PATCH',
        'route' => ['player.update', $player->player_id],
        'files' => 'true'
    ]) !!}

<h1>Edit Player: {{$player->display_name}}</h1>

<div id="errortask">
</div>

<b>Team Name: {{$player["team"]->display_name}} </b>

<br><br>
<input type="hidden" value="{{Session::get('selectedLeague')}}" name="league" />
<input type="hidden" value="{{$player->team_id}}" name="team" />

            <div class="col">
                <div class="col-title">First Name: </div>
                <input id="col-border" type="text" name="firstname" maxlength = "40" value="{{ $player->first_name }}" required />
            </div>
            
            <div class="col">
                <div class="col-title">Middle Name: </div>
                <input id="col-border" type="text" name="middle" maxlength = "40" value="{{ $player->middle_name }}" />
            </div>


            <div class="col">
                <div class="col-title">Last Name: </div>
                <input id="col-border" type="text" name="lastname" maxlength = "40" value="{{ $player->last_name }}" required />
            </div>

            <div class="col">
                <div class="col-title">Jersey Number: </div>
                <input id="col-border" type="number" name="number" value="{{ $player->jersey_number }}"/>
            </div>

            <div class="col">
                <div class="col-title">Role/Position: </div>
                {!! Form::select('position', ['Guard' => "Guard", 'Forward' => "Forward", 'Center' => "Center"], $player->position, ['id'=>'dropdown','class' => 'choices','required']) !!} 
            </div>

            <div class="col">
                <div class="col-title">Age: </div>
                <input id="col-border" type="number" name="age" min="0" value="{{ $player->age }}" />
            </div>

            <div class="col">
                <div class="col-title">Height: </div>
                <input id="col-border" type="number" name="height" min="0" value="{{ $player->height }}" /> CM
            </div>

            <div class="col">
                <div class="col-title">Weight: </div>
                <input id="col-border" type="number" name="weight" min="0" value="{{ $player->weight }}" /> LBS
            </div>

            @if($league->hasPhotos == 1)
                <div class="col">
                    <div class="col-title">Player Photo: </div>
                        <input id="fileUpload" type="file" name="images">
                </div>

                <div class="col">
                    <div class="col-title" style="vertical-align:top;">Preview: </div>
                    <div id="preview" style="display:inline;">
                    </div>
                </div>
            @endif

    <div> 
        <br><br>
        <input type="submit" class="btn-flat" value="Update"> 
        <a href="{{ route('player.show', $player->slug) }}" class="button">Cancel</a>
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