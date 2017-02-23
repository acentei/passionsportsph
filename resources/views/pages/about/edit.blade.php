@extends('layout.master')

@section('title')
    About : Edit
@stop

@include('layout.navi')

@section('content')


{!! Form::model($about, [
        'method' => 'PATCH',
        'route' => ['about.update', $about->about_id]
    ]) !!}


<h1>About Passion Sports:</h1>

{{-- SHOW ERRORS --}}
@include('partials.alerts.error')


    <textarea id="details" name="about_website" rows="50" placeholder="About This Website" required>{{$about->about_website}}</textarea>

    <div> 
        <br>
        <input type="submit" class="btn-flat" value="Update"> 
        <a href="{{ route('about.index') }}" class="button">Cancel</a>
        <br>
    </div>
    <br>

<script>

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
                   '//www.tinymce.com/css/codepen.min.css',   
                                                   
               ],   
               forced_root_block : 'div',
               fontsize_formats: "8pt 10pt 12pt 14pt 18pt 24pt 36pt",                      
             });

        // ------- END TINY MCE ----------- //

</script>

@endsection