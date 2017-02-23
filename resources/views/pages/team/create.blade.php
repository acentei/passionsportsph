@extends('layout.master')

@section('title')
    Team : Create
@stop

@include('layout.navi')

@section('content')

{{-- SHOW ERRORS --}}
@include('partials.alerts.error')

{!! Form::open([      
        'method' => 'POST',
        'action' => 'TeamController@store',
        'files' => 'true'
    ]) !!}

<h1>Create New Team:</h1>

<div id="errortask">
</div>

<b>Team League: {{$league->display_name}} </b>

<br><br>

<input type="hidden" value="{{Session::get('selectedLeague')}}" name="league" />

{{-- IF LEAGUE HAS PHOTOS--}}
@if($league->hasPhotos == 1)
    <div class="col">
        <div class="col-title">Team Name: </div>
            <input id="col-border" type="text" name="name" maxlength = "40" value='{{old("name")}}' required />
    </div>

    <div class="col">
        <div class="col-title">Team Nickname: </div>
            <input id="col-border" type="text" name="nickname" minlength="3" maxlength = "3" value='{{old("nickname")}}' required />
    </div>
    
    @if($league->hasBracket == 1)
        <div class="col">
            <div class="col-title">Team Bracket: </div>        
            {!!Form:: select('bracket', $bracket,0, ['id' => 'dropdown', 'class' => 'choices','placeholder' => '-- Select --','required']) !!}
        </div>
    @endif

    <div class="col">
        <div class="col-title">Team Logo: </div>
        <input id="fileUpload" type="file" name="images" required>
    </div>

    <div class="col">
        <div class="col-title" style="vertical-align:top;">Preview: </div>
        <div id="preview" style="display:inline;">
        </div>
    </div>

    <div> 
        <br><br>
        <input type="submit" class="btn-flat" value="Save"> 
        <a href="{{ route('league.show', $league->slug) }}" class="button">Cancel</a>
        <br><br>
    </div>

{{--IF LEAGUE HAS NO PHOTOS--}}
@else
    <input type="button" class="btn-add" value="+ New Line " onClick="addTaskPanel();"> 

    <div id="tp-append">
        <div id="tp" class="tp-creator">
            <div class="tp-col">
                <div class="tp-col-title">Team Name: </div>
                    <input id="col-border" type="text" name="name[]" maxlength = "40" value='{{old("name")}}' required />
            </div>

            <div class="tp-col">
                <div class="tp-col-title">Team Nickname: </div>
                    <input id="col-border" type="text" name="nickname[]" minlength="3" maxlength = "3" value='{{old("nickname")}}' required />
            </div>
            @if($league->hasBracket == 1)
                <div class="tp-col">
                        <div class="tp-col-title">Team Bracket: </div>
                        {!!Form:: select('bracket[]', $bracket,0, ['id' => 'dropdown','style' => 'width:100%;', 'class' => 'choices','placeholder' => '-- Select --','required']) !!}
                </div>
            @endif
            
            <input type="button" id="btnRemoveDD" class="btn-del-tp" value="Remove " onClick="delDiv(this.id);">
           

        </div>
    </div>

    <div> 
        <br><br>
        <input type="submit" class="btn-flat" value="Save"> 
        <a href="{{ route('league.show', $league->slug) }}" class="button">Cancel</a>
        <br><br>
    </div>

@endif

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