@section('page-title') {{ trans('controller.create') }} @stop

<!--<input type="hidden" name="user_id" value="{{ Auth::user()->id }}" />-->

<div class="col-md-12 box-wrapper"> 
    <div class="box">
        <div class="row">
            <!-- input box-->
            <div class="col-md-4 col-lg-4"> 
                <div class="form-group document-type-select">
                    {!! ViewHelper::setSelect($documentTypes,'document_type_id',$data,old('document_type_id'),
                            trans('documentForm.type'), trans('documentForm.type'),true ) !!}
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
            <div class="col-md-4 col-lg-4 "> 
                <div class="form-group">
                    {!! ViewHelper::setInput('name',$data,old('name'),trans('documentForm.documentName') , 
                           trans('documentForm.documentName') , true  ) !!}
                </div>   
            </div><!--End input box-->
            
            <!-- input box-->
            <div class="col-md-12 col-lg-12 "> 
                <div class="form-group">
                    {!! ViewHelper::setArea('name_long',$data,old('name_long'),trans('documentForm.subject'),
                    trans('documentForm.subject'), false, array(), array(), false, true ) !!}
                    
                </div>   
            </div><!--End input box-->
            
            <!-- input box-->
            <div class="col-md-4 col-lg-4 "> 
                <div class="form-group">
                    {!! ViewHelper::setInput('search_tags',$data,old('search_tags'),trans('documentForm.searchTags') , 
                           trans('documentForm.searchTags') , true  ) !!} <!-- add later data-role="tagsinput"-->
                </div>   
            </div><!--End input box-->
            
            
            <!-- input box-->
            <div class="col-md-4 col-lg-4"> 
                <div class="form-group">
                  
                        {!! ViewHelper::setInput('date_published',$data,old('date_published'),trans('documentForm.datePublished'), trans('documentForm.datePublished') , true, 'text' , ['datetimepicker']  ) !!}
                    
                </div>   
            </div><!--End input box-->
            
            <!-- input box-->
            <div class="col-md-4 col-lg-4"> 
                <div class="form-group">
                    {!! ViewHelper::setInput('date_expired',$data,old('date_expired'), trans('documentForm.dateExpired') , trans('documentForm.dateExpired') , false ,'text', ['datetimepicker'] ) !!}
                </div>   
            </div><!--End input box-->
            
            <!-- input box-->
            <div class="col-md-4 col-lg-4 "> 
                <div class="form-group">
                    <label class="control-label"> {{ ucfirst(trans('documentForm.owner')) }}*</label>
                    <select name="owner_user_id" class="form-control select" data-placeholder="{{ ucfirst(trans('documentForm.owner')) }}">
                        @foreach($mandantUsers as $mandantUser)
                                <option value="{{$mandantUser->id}}" 
                                @if(isset($data->owner_user_id) ) 
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
                            <option value="{{$documentUser->id}}" 
                            @if( isset($data->user_id) && $documentUser->id == $data->user_id) 
                                selected 
                            @elseif(Request::is('*/create') && Auth::user()->id == $documentUser->id)
                                selected 
                            @endif >
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
            
            
            <!-- input box-->
            <div class="col-md-4 col-lg-4"> 
                <div class="form-group">
                    <label class="control-label"> {{ ucfirst(trans('documentForm.status')) }} </label>
                    <select name="status" class="form-control select" data-placeholder="{{ ucfirst(trans('documentForm.status')) }}" disabled>
                        <option value="0"></option>
                        @foreach($documentStatus as $status)
                            <option value="{{$status->id}}" 
                            @if( isset($data->document_status_id) )
                                @if($status->id == $data->document_status_id) selected @endif 
                            @else
                                @if($status->id == 1) selected @endif 
                            @endif
                            > 
                                {{ $status->name }}
                            
                            </option>
                        @endforeach
                    </select>
                </div>   
            </div><!--End input box-->

            <!-- Document template select -->
            <div class="col-md-4 col-lg-4"> 
                <div class="form-group ">
                    <label class="control-label"> {{ ucfirst(trans('documentForm.documentTemplates')) }}* </label>
                    <select name="document_template" class="form-control select" data-placeholder="{{ ucfirst(trans('documentForm.documentTemplates')) }}" required>
                        <option value="1" @if((isset($data->document_template)) && ($data->document_template == 1)) selected @endif> Vorlage 1</option>
                        <option value="2" @if((isset($data->document_template)) && ($data->document_template == 2)) selected @elseif(isset($data->document_template) == false) selected @endif > Vorlage 2 - 2017</option>
                    </select>
                </div>   
            </div>
            
            <!-- input box-->
            <div class="col-md-4 col-lg-4 pdf-checkbox"> 
                <div class="form-group no-margin-bottom">
                    <br>
                    {!! ViewHelper::setCheckbox('pdf_upload',$data,old('pdf_upload'),trans('documentForm.pdfUpload') ) !!}
                </div>   
            </div><!--End input box-->
            
            @if( isset($data->document_type_id) && $data->document_type_id != 5)
                <!-- input box-->
                <div class="col-md-4 col-lg-4">
                    <br>
                    <div class="form-group no-margin-bottom">
                        {!! ViewHelper::setCheckbox('landscape',$data,old('landscape'),trans('documentForm.landscape') ) !!}
                    </div>   
                </div><!--End input box-->
            @else
             <!-- input box-->
                <div class="col-md-4 col-lg-4"> 
                    <br>
                    <div class="form-group no-margin-bottom">
                        {!! ViewHelper::setCheckbox('landscape',$data,old('landscape'),trans('documentForm.landscape') ) !!}
                    </div>
                </div><!--End input box-->
            @endif
            <div class="clearfix"></div>

            <!-- input box-->
            <div class="col-md-12 col-lg-12">
                <div class="form-group">
                    {!! ViewHelper::setArea('summary',$data,old('summary'),trans('documentForm.summary') ) !!}
                </div>
            </div><!--End input box-->  
        </div>
    </div>


<div class="clearfix"></div> <br/>
