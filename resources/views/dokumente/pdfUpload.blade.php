@section('page-title') {{ trans('controller.create') }} @stop
<h3 class="title">{{ trans('controller.editor') }}</h3>
@if($data->landscape == true)
    <input type="hidden" class="document-orientation" name="document-orientation" value="landscape" />
@endif
<div class="row">  
    
    <!-- input box-->
    <div class="col-lg-5"> 
        <div class="form-group">
            {!! ViewHelper::setSelect($adressats,'adressat_id',$data,old('adressat_id'),
                    trans('documentForm.adressat'), trans('documentForm.adressat') ) !!}
        </div>   
    </div><!--End input box-->
    
    <!-- input box-->
    <!--<div class="col-lg-5"> 
        <div class="form-group">
           {!! ViewHelper::setArea('betreff',$data,old('betreff'),trans('documentForm.subject'), trans('documentForm.subject'), false,
                    array(), array(), false, true ) !!}
        </div>   
    </div><!--End input box-->
    
    <div class="clearfix"></div>
    <!-- input box-->
    <div class="col-lg-3"> 
        <div class="form-group">
            {!! ViewHelper::setCheckbox('show_name',$data,old('show_name'),trans('documentForm.showName')) !!}
        </div>   
    </div><!--End input box-->
    
      
    
</div>
    
<div class="clearfix"></div>

<div class="row">
    <div class="col-xs-12">
        <div class="pull-right">
            <button href="#" class="btn btn-primary preview" name="preview" value="preview" type="submit">Seiten Vorschau</button>
            <button href="#" class="btn btn-primary preview" name="pdf_preview" value="pdf_preview" type="submit">PDF Vorschau</button>
            <input type="hidden" name="current_variant" value="1" />
        </div>
    </div>
</div>


<input type="hidden" name="model_id" value="{{$data->id}}" />
<div class="col-xs-12 editable" data-id='inhalt'>
    @foreach($data->editorVariant as $variant)
        {!! $variant->inhalt  !!}
    @endforeach
</div>
<div class="clearfix"></div>
<br/>
<div class="row">
    <!-- input box-->
    <div class="col-lg-6"> 
        <div class="form-group">
            <input type="file" name="files[]" class="form-control"  
            @if( $data->documentUploads()->count() < 1 )
                required
            @endif
            />
        </div>   
    </div><!--End input box-->
    @if( $data->documentUploads()->count() > 0 )
        <div class="col-lg-6 "> 
        <span class="lead"> Hochgeladene Dateien</span>
        @foreach($data->documentUploads as $doc)
           <p class="text-info">
               <!--<span class="fa fa-file-o"></span> -->
               {{ $doc->file_path }}</p>
        @endforeach
      
        </div><!--End input box-->
    @endif
</div><br>

@section('script')
        <script type="text/javascript">
            $(document).ready(function(){
                  @if( isset($previewUrl) && $previewUrl != '')
                    var url="{{$previewUrl}}", win = window.open(url, '_blank');
                        win.focus();
                  @endif
            });//end document ready
        </script>
      @stop
      @if( isset( $data->document_type_id ) )
           @section('preScript')
               <!-- variable for expanding document sidebar-->
               <script type="text/javascript">
                    var documentType = "{{ $data->documentType->name}}";
                   
                      
               </script>
               
               <!--patch for checking iso category document-->
                @if( isset($data->isoCategories->name) )
                
                    <script type="text/javascript">   
                        if( documentType == 'ISO-Dokumente')
                            var isoCategoryName = '{{ $data->isoCategories->name}}';
                    </script>
                @endif
               <!-- End variable for expanding document sidebar-->
           @stop
       @endif