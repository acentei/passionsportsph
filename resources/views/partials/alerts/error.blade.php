@if($errors->any())
    <br>
        <div class="alert-box-error">  
           <center> <strong>Whoops!</strong> There were some problems with your input.<br></center>
            <ul>
                @foreach ($errors->all() as $error)
                <p><b>~</b> {{ $error }}</p>
                @endforeach
            </ul>	
        </div>
    
@endif