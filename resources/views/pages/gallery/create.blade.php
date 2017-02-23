@extends('layout.master')

@section('title')
    Gallery : Upload
@stop

@include('layout.navi')

@section('content')

{{-- SHOW ERRORS --}}
@include('partials.alerts.error')

<h1>Upload New Image:</h1>

<div id="preview">
</div>

{!! Form::open([      
        'method' => 'POST',
        'action' => 'GalleryController@store',
        'files' => true,
        'enctype' => 'multipart/form-data'
]) !!}

<input type="hidden" value="{{Session::get('selectedLeague')}}" name="league" />

{{--<input id="fileUpload" type="file" name="images[]" multiple="true">--}}
{!! Form::file('images[]', array('id' => 'fileUpload','multiple'=>true)) !!}

<input type="submit" value="Upload" class="btn-flat">

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
</script>

@endsection