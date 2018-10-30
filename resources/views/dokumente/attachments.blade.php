@extends('master')

@section('page-title')
     {{ trans('controller.attachment') }}
@stop
    @section('content')
        <div class="box-wrapper col-md-12">
            @if( $data->name != null)   
                <div class="row">
                   <div class="col-md-12"><h3 class="title doc-title">{{ $data->name }}</h3></div>
                </div>
            @endif
            <div class="box">
                <div class="row">
                    <div class="col-xs-12">
                        <ul class="nav nav-tabs" id="tabs">
                           @if( count($data->editorVariant) > 0) 
                               @foreach( $data->editorVariant as $hv => $variant)
                                   <li><a href="#variant{{$variant->variant_number}}" data-toggle="tab">{{ trans('documentAnlegen.anlageZuVariante') }} {{$variant->variant_number}}</a></li>
                                   @if( $data->pdf_upload )
                                       @break 
                                   @endif
                               @endforeach
                           @endif
                        </ul>
                        
                        <div class="tab-content">
                            @if( count($data->editorVariant) > 0) 
                                @foreach( $data->editorVariant as $cv =>  $variant)
                                    <div class="tab-pane" id="variant{{$variant->variant_number}}">
                                        <div class="row">
                                            <div class="col-xs-6 ">
                                                <h2 class="title">{{ trans('documentAnlegen.anlageZuVariante') }} {{$variant->variant_number}}:</h2>
                                                <div class="">
                                                    {{-- @if( array_key_exists( $variant->id,$attachmentArray ) && $attachmentArray[$variant->id] != '[]'  ) --}}
                                                    @if(count($attachmentArray[$variant->id]))
                                                    
                                                        {{--  
                                                        <div class="tree-view hide-icons" data-selector="variant-tree-{{$variant->variant_number}}">
                                                            <div class="variant-tree-{{$variant->variant_number}} hide">
                                                                {{ ($attachmentArray[$variant->id]) }}
                                                            </div>
                                                        </div>
                                                        --}}
                                                    
                                                        @foreach($attachmentArray[$variant->id] as $attachedDoc)
                                                            @if(isset($attachedDoc->files))
                                                                <div class="attached-document">
                                                                    <a class="attached-document-download " href="{{$attachedDoc->files[0]->downloadUrl}}"> 
                                                                    &nbsp;{{$attachedDoc->name}} </a>
                                                                    <a class="attached-document-download pull-left" href="{{$attachedDoc->deleteUrl}}"> 
                                                                    <span class="icon-trash display-block"></span></a>
                                                                </div> 
                                                                <div class="clearfix"></div> <br>
                                                            @endif
                                                        @endforeach
                                                    
                                                    @else
                                                        <p class="text-danger">Es wurden keine Anhänge gefunden {{--$variant->variant_number --}}</p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                
                                    <div class="clearfix"></div>  
                                    
                                    <!--Select existing document-->         
                                    <div class="row">
                                        {!! Form::open([
                                        'url' => 'dokumente/anlagen/'.$data->id.'/'. $variant->variant_number,
                                        'method' => 'POST',
                                        'class' => 'horizontal-form' ]) !!}
                                        <div class="col-xs-12">
                                            <h3>{{trans('documentForm.existingDocument')}}</h3>
                                        </div>
                                        
                                            <input type="hidden" name="variant_id" value="{{ $variant->id }}" />
                                            
                                            <!--option box-->
                                            <div class="col-xs-8 col-md-6">
                                                <div class="form-group">
                                                    {!! ViewHelper::setSelect($documentsFormulare,'document_id',$data,old('document_id'),
                                                            trans('documentForm.documents'), trans('documentForm.documents'),true ) !!}
                                                </div>   
                                            </div>
                                            <!--end option box-->
                                            
                                            <!--button box-->
                                            <div class="col-xs-8 col-md-6">
                                                <div class="form-group custom-input-group-btn">
                                                  <button class="btn btn-primary" type="submit" name="attach" value="attach">Hinzufügen</button>
                                                </div>   
                                            </div>
                                            <!--end button box-->
                                            
                                        </form>
                                    </div><!--end Select existing document-->
                                    
                                    
                                    <!--create new document-->
                                    <div class="row">
                                        {!! Form::open([
                                        'url' => 'dokumente/anlagen/'.$data->id.'/'.$variant->variant_number,
                                        'method' => 'POST',
                                        'enctype' => 'multipart/form-data',
                                        'class' => 'horizontal-form form-check' ]) !!}
                                        <div class="col-xs-12">
                                            <h3>{{trans('documentForm.newDocument')}}</h3>
                                        </div>
                                            
                                            <input type="hidden" name="user_id" value="{{ Auth::user()->id }}" />
                                            <input type="hidden" name="document_id" value="{{ $data->id }}" />
                                            <input type="hidden" name="variant_id" value="{{ $variant->id }}" />
                            
                                            <!-- input box-->
                                            <div class="col-lg-4"> 
                                                <div class="form-group">
                                                <label class="control-label">{{ ucfirst( trans('documentForm.type')) }}*</label>     
                                                <select name="document_type_id" class="form-control select" 
                                                data-placeholder="{{ ucfirst(trans('documentForm.type')) }}*" required >
                                                <option></option>
                                                @if( count($documentTypes) >0 ){
                                                   @foreach($documentTypes as $collection){
                                                       <option value="{{$collection->id}}" @if($collection->id == 6) selected @endif>
                                                           {{$collection->name}}
                                                       </option>
                                                    @endforeach
                                                @endif
                                            </select>
                                                </div>   
                                            </div><!--End input box-->
                                            
                                            <!-- input box-->
                                            <div class="col-md-2 col-lg-2 qmr-select"> 
                                                <div class="form-group">
                                                    {!! ViewHelper::setInput('qmr_number',$data,$incrementedQmr,trans('documentForm.qmr') , 
                                                           trans('documentForm.qmr') , true, 'number', array(), array() )!!}
                                                </div>   
                                            </div><!--End input box-->
                                            
                                            <!-- input box-->
                                            <div class="col-md-2 col-lg-2 iso-category-select"> 
                                                <div class="form-group">
                                                    {!! ViewHelper::setInput('iso_category_number',$data,$incrementedIso,trans('documentForm.isoNumber') , 
                                                           trans('documentForm.isoNumber') , true, 'number', array(), array() ) !!}
                                                </div>   
                                            </div><!--End input box-->
                                            
                                            <!-- input box-->
                                            <div class="col-md-2 col-lg-2 additional-letter"> 
                                                <div class="form-group">
                                                    {!! ViewHelper::setInput('additional_letter',$data,old('additional_letter'),trans('documentForm.additionalLetter') , 
                                                           trans('documentForm.additionalLetter')  ) !!}
                                                </div>   
                                            </div><!--End input box-->
                                            
                                             
                                            <!-- input box-->
                                            <div class="col-md-4 col-lg-4 iso-category-select"> 
                                                <div class="form-group">
                                                    {!! ViewHelper::setSelect($isoDocuments,'iso_category_id',$data,old('iso_category_id'),
                                                            trans('documentForm.isoCategory'), trans('documentForm.isoCategory') ) !!}
                                                </div>   
                                            </div><!--End input box-->
                                            
                                            <!-- input box-->
                                            <div class="col-lg-4"> 
                                                <div class="form-group">
                                                    {!! ViewHelper::setInput('name',$data,old('name'),trans('documentForm.documentName') , 
                                                           trans('documentForm.documentName') , true  ) !!}
                                                </div>   
                                            </div><!--End input box-->
                                            
                                            
                                            <!-- input box-->
                                            <div class="col-md-12 col-lg-12 "> 
                                                <div class="form-group">
                                                    {!! ViewHelper::setArea('name_long',$data,old('name_long'),trans('documentForm.documentNameLong') ) !!}
                                                </div>   
                                            </div><!--End input box-->
                                            
                                            <!-- input box-->
                                            <div class="col-lg-4"> 
                                                <div class="form-group">
                                                    {!! ViewHelper::setInput('search_tags',$data,old('search_tags'),trans('documentForm.searchTags') , 
                                                           trans('documentForm.searchTags') , true  ) !!} <!-- add later data-role="tagsinput"-->
                                                </div>   
                                            </div><!--End input box-->
                                            
                                            <!-- input box date_published-->
                                            <div class="col-lg-4"> 
                                                <div class="form-group">
                                                    {!! ViewHelper::setInput('date_published',$data,old('date_published'),trans('documentForm.datePublished'), trans('documentForm.datePublished') , false, 'text' , ['datetimepicker']  ) !!}
                                                </div>   
                                            </div><!--End input box-->
                                            
                                            <!-- input box date_expired-->
                                            <div class="col-lg-4"> 
                                                <div class="form-group">
                                                    {!! ViewHelper::setInput('date_expired',$data,old('date_expired'), trans('documentForm.dateExpired') , trans('documentForm.dateExpired') , false ,'text', ['datetimepicker'] ) !!}
                                                </div>   
                                            </div><!--End input box-->
                                            
                                            <!-- input box owner owner-->
                                            <div class="col-lg-4"> 
                                                <div class="form-group">
                                                    <label class="control-label">{{ ucfirst( trans('documentForm.owner') ) }}*</label> 
                                                    <select name="owner_user_id" class="form-control select" data-placeholder="{{ ucfirst(trans('documentForm.owner')) }}">
                                                        @foreach($mandantUsers as $mandantUser)
                                                            <option value="{{$mandantUser->id}}" 
                                                            @if(isset($data->owner_user_id)) 
                                                                @if($mandantUser->id == $data->owner_user_id) 
                                                                    selected  
                                                                @endif 
                                                            @elseif( isset( Auth::user()->id )  )
                                                                @if($mandantUser->id ==  Auth::user()->id ) selected @endif 
                                                            @endif> 
                                                               {{ $mandantUser->last_name }} {{ $mandantUser->first_name }} 
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>   
                                            </div><!--End input box-->
                                            
                                            <!-- input box-->
                                            <div class="col-md-4 col-lg-4"> 
                                                <div class="form-group">
                                                    <label class="control-label"> {{ trans('documentForm.user') }} *</label>
                                                    <select name="user_id" class="form-control select" data-placeholder="{{ strtoupper( trans('documentForm.user') ) }}" required>
                                                        @foreach( $documentUsers as $documentUser )
                                                            <option value="{{$documentUser->id}}" @if( isset($data->user_id) && $documentUser->id == $data->user_id) selected @endif >
                                                                {{ $documentUser->last_name }} {{ $documentUser->first_name }}  
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div><!--End input box-->
                                            
                                            <div class="col-md-4 col-lg-4">
                                                <div class="form-group">
                                                    <label class="control-label"> {{ trans('documentForm.coauthor') }} </label>
                                                    <select name="document_coauthor[]" class="form-control select empty-select" data-placeholder="{{ strtoupper( trans('documentForm.coauthor') ) }}">
                                                        <option value="" @if( Request::is('*/create') ) select @endif >&nbsp;</option>
                                                        @foreach($mandantUsers as $mandantUser)
                                                            <option value="{{$mandantUser->id}}"
                                                            @if( Request::is('*/edit') )
                                                                @if(isset($documentCoauthors)) {!! ViewHelper::setMultipleSelect($documentCoauthors, $mandantUser->id, 'user_id') !!} @endif
                                                            @endif
                                                            > 
                                                                {{ $mandantUser->last_name }} {{ $mandantUser->first_name }}  
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div><!--End input box-->
                                            
                                            <!-- input box status-->
                                            <div class="col-lg-4"> 
                                                <div class="form-group">
                                                    <label class="control-label"> {{ ucfirst(trans('documentForm.status')) }} </label>
                                                    <select name="status" class="form-control select" data-placeholder="{{ ucfirst(trans('documentForm.status')) }}" disabled>
                                                        <option value="0"></option>
                                                        @foreach($documentStatus as $status)
                                                            <option value="{{$status->id}}"  @if($status->id == $data->document_status_id) selected @endif > 
                                                                {{ $status->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>   
                                            </div><!--End input box--> 
                                            
                                            <!-- input box-->
                                            <div class="col-lg-4 pdf-checkbox"> 
                                                <div class="form-group">
                                                    <br>
                                                    {!! ViewHelper::setCheckbox('pdf_upload',$data,old('pdf_upload'),trans('documentForm.pdfUpload') ) !!}
                                                </div>   
                                            </div><!--End input box-->
                                            
                                            <!-- input box-->
                                            <!--<div class="col-lg-4"> -->
                                            <!--    <div class="form-group">-->
                                            <!--        <label class="control-label"> {{ trans('documentForm.coauthor') }} </label>-->
                                            <!--        <select name="document_coauthor[]" class="form-control select" data-placeholder="{{ strtoupper( trans('documentForm.coauthor') ) }}">-->
                                            <!--            <option value="0"></option>-->
                                            <!--            @foreach($mandantUsers as $mandantUser)-->
                                            <!--                <option value="{{$mandantUser->id}}" @if(isset($documentCoauthor)) {!! ViewHelper::setMultipleSelect($documentCoauthor, $mandantUser->id, 'user_id') !!} @endif > -->
                                            <!--                   {{ $mandantUser->last_name }}  {{ $mandantUser->first_name }} -->
                                            <!--                </option>-->
                                            <!--            @endforeach-->
                                            <!--        </select>-->
                                            <!--    </div>   -->
                                            <!--</div><!--End input box-->
                                            
                                            <!-- input box-->
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    {!! ViewHelper::setArea('summary',$data,old('summary'),trans('documentForm.summary') ) !!}
                                                </div>   
                                            </div><!--End input box-->  
                                            
                                            <div class="clearfix"></div>
                                            
                                            <!-- input box-->
                                                <div class="col-lg-6"> 
                                                    <div class="form-group">
                                                        <label>Dokumentenupload</label>
                                                        <input type="file" name="file[]" class="form-control" required />
                                                    </div>   
                                                </div><!--End input box-->
                                            <div class="clearfix"></div>
                                            <div class="col-xs-12 form-buttons">
                                                @if( isset($backButton) )
                                                    <a href="{{$backButton}}" class="btn btn-info">
                                                        <!--<span class="fa fa-chevron-left"></span> -->
                                                        Zurück</a>
                                                @endif
                                                <button class="btn btn-primary" type="submit" name="save" value="save">
                                                    <!--<span class="fa fa-floppy-o"></span> -->
                                                    Speichern
                                                </button>
                                                
                                                {{-- NEPTUN-815, NEPTUN-817 --}}
                                                {{-- @if(in_array($data->document_status_id, [2, 6]) == false) --}}
                                                    <button class="btn btn-primary" type="button" name="next" value="next"
                                                    @if( isset($nextButton) ) data-link="{{$nextButton}}" @endif  > 
                                                        <!--<span class="fa fa-chevron-right"></span>-->
                                                        Weiter
                                                    </button>
                                                {{-- @endif --}}
                                                
                                            </div>
                                            
                                            <div class="clearfix"></div> <br/>
                            
                                        </form>
                                    </div><!--end create new document-->
                  </div><!--end tab pane -->
                                @endforeach
                            @endif
                        </div>
                    </div>
                 </div>
             </div>      
         </div>           
    @stop
    
    
    
  @if( count($data->editorVariant) ) 
      @section('script')
        <script type="text/javascript">
            $(document).ready(function(){
               	if( $('.nav-tabs li.active').length < 1 ){
              	    $('.nav-tabs li').first().addClass('active'); 
              	    $('.tab-content .tab-pane').first().addClass('active'); 
      	        }
            });//end document ready
        </script>
      @stop
  @endif
  
   @if( isset( $data->document_type_id ) )
           @section('preScript')
               <!-- variable for expanding document sidebar-->
               <script type="text/javascript">
                    var documentType = "{{ $data->documentType->name}}";
                   
                      
               </script>
               
               <!--patch for checking iso category document-->
                @if( isset($data->isoCategories->name) )
                    <script type="text/javascript">   
                        if( documentType == 'ISO Dokument')
                            var isoCategoryName = '{{ $data->isoCategories->name}}';
                    </script>
                @endif
               <!-- End variable for expanding document sidebar-->
           @stop
       @endif
       
        @if( Request::segment(4) || isset($preparedVariant) )
            @if( Request::segment(4) )
                @section('afterScript')
                    <script type="text/javascript">
                            if( $('a[href*="#variant{{ Request::segment(4) }}"]') )
                                $('a[href*="#variant{{ Request::segment(4) }}"]').click(); //#variant2
                              
                   </script>
                @stop
            @else
                @section('afterScript')
                    <script type="text/javascript">
                            if( $('a[href*="#variant{{ $preparedVariant }}"]') )
                                $('a[href*="#variant{{ $preparedVariant }}"]').click(); //#variant2
                              
                   </script>
                @stop
            @endif
       @endif