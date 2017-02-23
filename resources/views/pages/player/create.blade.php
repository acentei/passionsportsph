@extends('layout.master')

@section('title')
    Player : Create
@stop

@include('layout.navi')

@section('content')

{{-- SHOW ERRORS --}}
@include('partials.alerts.error')

{!! Form::open([      
        'method' => 'POST',
        'action' => 'PlayerController@store',
        'files' => 'true'
    ]) !!}

<input type="hidden" value="{{Session::get('selectedLeague')}}" name="league" />
<input type="hidden" value="{{$team->team_id}}" name="team" />

@if($league->hasPhotos == 1)
    <h1>Create Player:</h1>
    
    <div id="errortask">
    </div>

    <div class="col">
        *You are creating a Player for "{{$team->display_name}}"
    </div>

    <div class="col">
        <div class="col-title">First Name: </div>
        <input id="col-border" type="text" name="firstname" maxlength = "40" value='{{old("firstname")}}' required />
    </div>

    <div class="col">
        <div class="col-title">Middle Name: </div>
        <input id="col-border" type="text" name="middle" maxlength = "40" value='{{old("middle")}}'/>
    </div>

    <div class="col">
        <div class="col-title">Last Name: </div>
        <input id="col-border" type="text" name="lastname" maxlength = "40" value='{{old("lastname")}}' required />
    </div>

    <div class="col">
        <div class="col-title">Jersey Number: </div>
        <input id="col-border" type="number" name="number" value='{{old("number")}}'/>
    </div>

    <div class="col">
        <div class="col-title">Role/Position: </div>
        {!! Form::select('position', ['Guard' => "Guard", 'Forward' => "Forward",'Center' => "Center"], null, ['id'=>'dropdown','class' => 'choices','placeholder'=>'--Select Role/Position--','required']) !!} 
    </div>

    <div class="col">
        <div class="col-title">Age: </div>
        <input id="col-border" type="number" name="age" value='{{old("age")}}' />
    </div>

    <div class="col">
        <div class="col-title">Height: </div>
        <input id="col-border" type="number" name="height" value='{{old("height")}}' /> CM
    </div>

    <div class="col">
        <div class="col-title">Weight: </div>
        <input id="col-border" type="number" name="weight" value='{{old("weight")}}' /> LBS
    </div>

    <div class="col">
        <div class="col-title">Player Photo: </div>
            <input id="fileUpload" type="file" name="images">
    </div>

    <div class="col">
        <div class="col-title" style="vertical-align:top;">Preview: </div>
        <div id="preview" style="display:inline;">
        </div>
    </div>

{{--IF LEAGUE HAS NO PHOTOS TO SHOW--}}
@else
    <h1>Create Players:</h1>

    <input type="button" class="btn-add" value="+ New line " onClick="addTaskPanel();"> 
    <br>

    <div id="tp-append">   
        <div id="tp" class="tp-creator">
            <div class="tp-col">
                <div class="tp-col-title">First Name </div>
                <input id="col-border" type="text" name="firstname[]" maxlength = "40" required />
            </div>

            <div class="tp-col">
                <div class="tp-col-title">Middle Name </div>
                <input id="col-border" type="text" name="middle[]" maxlength = "40" />
            </div>

            <div class="tp-col">
                <div class="tp-col-title">Last Name </div>
                <input id="col-border" type="text" name="lastname[]" maxlength = "40" required />
            </div>

            <div class="tp-col">
                <div class="tp-col-title">Jersey Number </div>
                <input id="col-border" type="number" name="number[]" />
            </div>

            <div class="tp-col">
                <div class="tp-col-title">Role/Position </div>
                {!! Form::select('position[]', ['Guard' => "Guard", 'Forward' => "Forward",'Center' => "Center"], null, ['id'=>'dropdown','class' => 'choices','style' => 'width:100%;','placeholder'=>'--Select Role/Position--','required']) !!} 
            </div>

            <div class="tp-col">
                <div class="tp-col-title">Age </div>
                <input id="col-border" type="number" name="age[]"  />
            </div>

            <div class="tp-col">
                <div class="tp-col-title">Height(CM)</div>
                <input id="col-border" type="number" name="height[]" />
            </div>

            <div class="tp-col">
                <div class="tp-col-title">Weight(LBS)</div>
                <input id="col-border" type="number" name="weight[]" /> 
            </div>
            
            <input type="button" id="btnRemoveDD" class="btn-del-tp" value="remove " onClick="delDiv(this.id);">
            
            <br><br><hr><br>
        </div>        
        
    </div> 

@endif

    <div> 
        <br><br>
        <input type="submit" class="btn-flat" value="Save"> 
        <a href="{{ route('team.show', $team->slug) }}" class="button">Cancel</a>
        <br><br>
    </div>

{!! Form::close() !!}

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
        
        var $ddTemp = $(".tp-creator"); 
        var count = 0;
            
        function addTaskPanel()
        {           
            var $newDD = $ddTemp.clone();  
            count++;

            $newDD.attr("id","tp" + count);
            $newDD.find(".btn-del-tp")
                .attr("id","btnRemoveDD" + count);

            $("#tp-append").append($newDD.fadeIn());
        }  

        function delDiv(id)
        {    

            var divID = id.replace("btnRemoveDD","tp"); 
            var element = document.getElementById(divID);

            console.log(element);
            element.parentNode.removeChild(element);

        }
        
    </script>

@endsection