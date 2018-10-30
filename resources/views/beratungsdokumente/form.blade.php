@extends('master')

@section('page-title')
  Dokumente bearbeiten / anlegen - Grundinfos
@stop


@section('content')
<div class="col-md-12 box-wrapper">
    <div class="box">
        <div class="row">
            <!-- input box-->
            <div class="col-md-4 col-lg-4 "> 
                <div class="form-group">
                    {!! ViewHelper::setInput('name',$data,old('name'),trans('documentForm.documentName') , 
                           trans('documentForm.documentName') , true  ) !!}
                </div>   
            </div><!--End input box-->
            
              <!-- input box-->
            <div class="col-md-4 col-lg-4"> 
                <div class="form-group">
                    <label class="control-label"> {{ ucfirst(trans('documentForm.status')) }} </label>
                    <select name="status" class="form-control select" data-placeholder="@lang('documentForm.status')"
                    required >
                        <option value="0"></option>
                        @foreach($documentFilteredStatus as $status)
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
             
           
            <div class="col-md-4 col-lg-4">
                <div class="form-group">
                    <label class="control-label"> @lang('documentForm.kategorieJuristenDocument') </label>
                    <select name="document_type_id" class="form-control select jurist-switch"
                    data-placeholder="@lang('documentForm.kategorieJuristenDocument')" required>
                        <option value="" @if( Request::is('*/create') ) select @endif >&nbsp;</option>
                        @foreach($documentTypes as $type)
                            <option value="{{$type->id}}"
                            @if( Request::is('*/edit') )
                                @if($data->document_type_id == $type->id) selected @endif
                            @endif
                            data-beratung-category="@if(strpos($type->name, 'Beratung') !== false) 1 @else 0 @endif"
                            > 
                                {{ $type->name }}
                            </option>
                        @endforeach
                    </select>
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
                    <label class="control-label"> {{ trans('documentForm.user') }} *</label>
                    <select name="user_id" class="form-control select" data-placeholder="{{ strtoupper( trans('documentForm.user') ) }}" required>
                        @foreach( $users as $documentUser )
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
                        @foreach($users as $mandantUser)
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
                        {!! ViewHelper::setInput('date_published',$data,old('date_published'),trans('beratungsDokument.datum'), trans('beratungsDokument.datum') , true, 'text' , ['datetimepicker']  ) !!}
                </div>   
            </div>
            <!--End input box-->
            
          
            <!-- input box-->
            <div class="col-md-12 col-lg-12">
                <div class="form-group">
                    {!! ViewHelper::setArea('summary',$data,old('summary'),trans('documentForm.summary') ) !!}
                </div>
            </div><!--End input box-->
            
            <!-- input box-->
            <div class="col-md-4 col-lg-4"> 
                <div class="form-group">
                    <label class="control-label"> {{ trans('beratungsDokument.dokumentArt') }} *</label>
                    <select name="jurist_category_meta_id" class="form-control select jurist-switch-triggered"
                        data-placeholder="{{ strtoupper( trans('beratungsDokument.dokumentArt') ) }}" required>
                        @foreach( $documentArts as $documentArt )
                            <option value="{{$documentArt->id}}" @if( isset($data->jurist_category_meta_id) && $documentArt->id == $data->jurist_category_meta_id) selected @endif 
                            data-jurist-type="{{$documentArt->beratung}}" 
                            >
                                {{ $documentArt->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div><!--End input box-->
            <div class="clearfix"></div>
           
        </div>
    </div>
    
    <div class="clearfix"></div> <br>
            
            <div class="row">
                <div class="col-md-6 col-lg-6">
                    <button class="btn btn-primary no-margin-bottom" type="submit">{{ trans('benutzerForm.save') }}</button>
                </div>
            </div>
</div>

@stop