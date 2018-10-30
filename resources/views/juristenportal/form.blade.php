@section('page-title') {{ trans('controller.create') }} CHECK THIS @stop

<!--<input type="hidden" name="user_id" value="{{ Auth::user()->id }}" />-->

<div class="col-md-12 box-wrapper"> 
    <div class="box">
        <div class="row">
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
            <div class="col-md-4 col-lg-4 "> 
                <div class="form-group">
                    {!! ViewHelper::setInput('name',$data,old('name'),trans('documentForm.documentName') , 
                           trans('documentForm.documentName') , true  ) !!}
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
            
            
        </div><!--end .row -->
    </div><!--end .box -->


<div class="clearfix"></div> <br/>
