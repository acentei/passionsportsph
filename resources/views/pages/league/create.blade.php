@extends('layout.master')

@section('title')
    League : Create
@stop

@include('layout.navi')

@section('content')

{!! Form::open([      
        'method' => 'POST',
        'action' => 'LeagueController@store',
        'files' => 'true'
    ]) !!}


<h1>Create New League:</h1>

{{-- SHOW ERRORS --}}
@include('partials.alerts.error')

<div id="errortask">
</div>

    <div class="col">
        <div class="col-title">League Name: </div>
                       
        <input id="col-border" type="text" name="name" maxlength = "40" value='{{old("name")}}' required />
    </div>

<div class="col">
    <div class="col-title">League Logo: </div>
    <input id="fileUpload" type="file" name="images" required>
</div>

<div class="col">
    <div class="col-title" style="vertical-align:top;">Preview: </div>
    <div id="preview" style="display:inline;">
    </div>
</div>

<div class="col">
    <div class="col-title">Enable Brackets: </div>
    <input type="checkbox" name="hasBracket" value="1">     
</div>

<div class="col">
    <div class="col-title">Show Photos: </div>
    <input type="checkbox" name="hasPhoto" value="1">     
</div>

<h1>Stats to show</h1>

<div class="col-inline">
            <div class="col-inline-label">Field Goals Made: </div>
                <input type="checkbox" name="c_fgm" value="1">     
        </div>

        <div class="col-inline">
            <div class="col-inline-label">Field Goals Attempted: </div>
                    <input type="checkbox" name="c_fga" value="1">
        </div>

        <div class="col-inline">
            <div class="col-inline-label">Field Goals Percentage: </div>
                    <input type="checkbox" name="c_fgp" value="1">
        </div>

        <div class="col-inline">
            <div class="col-inline-label">3-Points Made: </div>
                    <input type="checkbox" name="c_pm3" value="1">
        </div>

        <div class="col-inline">
            <div class="col-inline-label">3-Points Attempted: </div>
                    <input type="checkbox" name="c_pa3" value="1">
        </div>

        <div class="col-inline">
            <div class="col-inline-label">3-Points Percentage: </div>
                    <input type="checkbox" name="c_pp3" value="1">
        </div>

        <div class="col-inline">
            <div class="col-inline-label">Free Throws Made: </div>
                    <input type="checkbox" name="c_ftm" value="1">
        </div>

        <div class="col-inline">
            <div class="col-inline-label">Free Throws Attempted: </div>
                    <input type="checkbox" name="c_fta" value="1">
        </div>

        <div class="col-inline">
            <div class="col-inline-label">Free Throw Percentage: </div>
                    <input type="checkbox" name="c_ftp" value="1">
        </div>

	<div class="col-inline">
            <div class="col-inline-label">Rebounds: </div>
                    <input type="checkbox" name="c_reb" value="1">
        </div>

	<div class="col-inline">
            <div class="col-inline-label">Offensive Rebounds: </div>
                    <input type="checkbox" name="c_oreb" value="1">
        </div>

	<div class="col-inline">
            <div class="col-inline-label">Defensive Rebounds: </div>
                    <input type="checkbox" name="c_dreb" value="1">
        </div>

	<div class="col-inline">
            <div class="col-inline-label">Steals: </div>
                    <input type="checkbox" name="c_stl" value="1">
        </div>
	
	<div class="col-inline">
            <div class="col-inline-label">Blocks: </div>
                    <input type="checkbox" name="c_blk" value="1">
        </div>

	<div class="col-inline">
            <div class="col-inline-label">Assists: </div>
                    <input type="checkbox" name="c_ast" value="1">
        </div>

	<div class="col-inline">
            <div class="col-inline-label">Turnovers: </div>
                    <input type="checkbox" name="c_tov" value="1">
        </div>


<br>

<h1>Rules and Regulations</h1>

<div class="col">
    <div class="col-title">Rules and Regulations: </div>

    <div class="forced-side">
    <textarea id="details" name="details" rows="50" placeholder="Rules and Regulations" value='{{old("details")}}'></textarea>
        </div>
</div>

        

    <div style="clear:both;"> 
        <br>
        <input type="submit" value="Save" class="btn-flat">
        <a href="{{ route('league.index') }}" class="button">Cancel</a>
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
    
   

        // ------- TINY MCE, WYSIWYG TEXT EDITOR ----------- //
        tinymce.init({ selector:'textarea#details',                           
               theme: 'modern',
               plugins: [
                    'advlist autolink lists link image charmap print preview hr anchor pagebreak',
                    'searchreplace wordcount visualblocks visualchars code fullscreen',
                    'insertdatetime media nonbreaking save table contextmenu directionality',
                    'emoticons template paste textcolor colorpicker textpattern imagetools'
               ],
               toolbar1: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link ',
               toolbar2: 'print preview | forecolor backcolor emoticons | fontselect |  fontsizeselect',
               image_advtab: true,
               templates: [
                     { title: 'Test template 1', content: 'Test 1' },
                     { title: 'Test template 2', content: 'Test 2' }
               ],
               content_css: [
                    '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',                              
               ],   
               forced_root_block : 'div',
               fontsize_formats: "8pt 10pt 12pt 14pt 18pt 24pt 36pt",                      
             });

        // ------- END TINY MCE ----------- //

</script>

@endsection