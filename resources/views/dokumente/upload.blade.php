@section('page-title') {{ trans('controller.create') }} @stop
<input type="hidden" name="model_id" value="{{$data->id}}" />
<br/>
    <h3 class="title">{{ trans('controller.uploads') }}</h3>
   
        <div class="row">
            <!-- input box-->
            <div class="col-lg-6"> 
                <div class="form-group">
                    
                    <input type="file" name="file[]" class="form-control" 
                    @if( $data->documentUploads()->count() < 1 )
                        required
                    @endif />
                </div>   
            </div><!--End input box-->
            @if( $data->documentUploads()->count() > 0 )
                <div class="col-lg-6 "> 
                <span class="lead"> Hochgeladene Dateien</span>
                @foreach($data->documentUploads as $doc)
                   <p class="text-info"><span class="fa fa-file-o"></span> {{ $doc->file_path }}</p>
                @endforeach
              
                </div><!--End input box-->
            @endif
            
        </div>


