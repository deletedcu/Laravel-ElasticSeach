{{-- ERWEITERTE SUCHE --}}

@extends('master')

@section('page-title') {{ trans('sucheForm.extended') }} {{ trans('sucheForm.search') }} @stop

@section('content')

<fieldset class="form-group">
    <div class="box-wrapper">
        <h4 class="title">{{ trans('sucheForm.options') }}</h4>
        
        {!! Form::open(['action' => 'SearchController@searchAdvanced', 'method'=>'GET']) !!}
            <div class="box">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="form-group">
                            {!! ViewHelper::setInput('name', '', old('name'), trans('sucheForm.name'), trans('sucheForm.name'), false) !!} 
                        </div>
                    </div>
                
                    <div class="col-lg-4">
                        <div class="form-group">
                            {!! ViewHelper::setInput('beschreibung', '', old('beschreibung'), trans('sucheForm.description'), trans('sucheForm.description'), false) !!} 
                        </div>
                    </div>
                
                    <div class="col-lg-4">
                        <div class="form-group">
                            {!! ViewHelper::setInput('betreff', '', old('betreff'), trans('sucheForm.subject'), trans('sucheForm.subject'), false) !!} 
                        </div>
                    </div>
                  
                </div>
                    
                <div class="row">
                    
                    <div class="col-lg-4">
                        <div class="form-group">
                            {!! ViewHelper::setInput('inhalt', '', old('inhalt'), trans('sucheForm.content'), trans('sucheForm.content'), false) !!} 
                        </div>
                    </div>
                    
                    <div class="col-lg-4">
                        <div class="form-group">
                            {!! ViewHelper::setInput('tags', '', old('tags'), trans('sucheForm.tags'), trans('sucheForm.tags'), false) !!} 
                        </div>
                    </div>
                    
                    <div class="col-lg-2">
                        <div class="form-group">
                            {!! ViewHelper::setInput('datum_von', '', old('datum_von'), trans('sucheForm.date_from'), trans('sucheForm.date_from'), false, '', ['datetimepicker']) !!} 
                        </div>
                    </div>
                    
                    <div class="col-lg-2">
                        <div class="form-group">
                            {!! ViewHelper::setInput('datum_bis', '', old('datum_bis'), trans('sucheForm.date_to'), trans('sucheForm.date_to'), false, '', ['datetimepicker']) !!} 
                        </div>
                    </div>
                  
                </div>
                    
            
                <div class="row">
                    <div class="col-lg-4">
                        <div class="form-group document-type-select">
                            {{-- ViewHelper::setSelect($documentTypes, 'document_type', '', old('document_type'), trans('sucheForm.document-type'), trans('sucheForm.document-type'), false) --}}
                            <div class="form-group">
                                <label class="control-label"> {{ trans('sucheForm.document-type') }}</label>
                                <select name="document_type" class="form-control select" data-placeholder="{{ strtoupper( trans('sucheForm.document-type') ) }}">
                                    <!--<option value=""></option>-->
                                    <option value="">Alle</option>
                                    @foreach($documentTypes as $documentType)
                                        <option value="{{$documentType->id}}" @if(old('document_type') == $documentType->id) selected @endif > 
                                            {{ $documentType->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                        
                    <div class="col-md-2 col-lg-2 qmr-select"> 
                        <div class="form-group">
                            {!! ViewHelper::setInput('qmr_number', '', old('qmr_number'), trans('documentForm.qmr') , 
                                   trans('documentForm.qmr'), false, 'number', array(), array() )!!}
                        </div>   
                    </div>
                    
                    <!-- input box-->
                    <div class="col-md-2 col-lg-2 iso-category-select"> 
                        <div class="form-group">
                            {!! ViewHelper::setInput('iso_category_number', '', old('iso_category_number'), trans('documentForm.isoNumber') , 
                                   trans('documentForm.isoNumber') , false, 'number', array(), array() ) !!}
                        </div>   
                    </div>
                    
                    <div class="col-md-2 col-lg-2 additional-letter"> 
                        <div class="form-group">
                            {!! ViewHelper::setInput('additional_letter', '', old('additional_letter'), trans('documentForm.additionalLetter') , 
                                   trans('documentForm.additionalLetter')  ) !!}
                        </div>   
                    </div>
                    
                    <div class="col-md-4 col-lg-4"> 
                        <div class="form-group">
                            <label class="control-label"> {{ trans('documentForm.user') }}</label>
                            <select name="user_id" class="form-control select" data-placeholder="{{ strtoupper( trans('documentForm.user') ) }}">
                                <!--<option value=""></option>-->
                                <option value="">Alle</option>
                                @foreach($mandantUsers as $mandantUser)
                                    <option value="{{$mandantUser->user->id}}" @if(old('user_id') == $mandantUser->user->id) selected @endif > 
                                        {{ $mandantUser->user->first_name }} {{ $mandantUser->user->last_name }} 
                                    </option>
                                @endforeach
                            </select>
                        </div>   
                    </div>
                </div>
                
                <div class="row">
                    @if( ViewHelper::universalHasPermission( array(15,16) ) == true ) 
                    <div class="col-lg-2 col-sm-6">
                        <!--<br class="hidden-xs hidden-sm">   -->
                        <div class="checkbox">
                            <input type="checkbox" @if((old('wiki'))) checked @endif name="wiki" id="wiki">
                            <label for="wiki"> {{ trans('sucheForm.wiki') }} <br class="hidden-lg"> {{ trans('sucheForm.entries') }} </label>
                        </div>
                    </div>
                    @endif
                    
                    @if( ViewHelper::universalHasPermission( array(14) ) == true ) 
                    <div class="col-lg-2 col-sm-6">
                        <!--<br class="hidden-xs hidden-sm">   -->
                        <div class="checkbox">
                            <input type="checkbox" @if((old('history'))) checked @endif name="history" id="history">
                            <label for="history"> {{ trans('sucheForm.history') }} <br class="hidden-lg"> {{ trans('sucheForm.archive') }} </label>
                        </div>
                    </div>
                    @endif
                    
                    <div class="col-lg-4">
                        <label>&nbsp;</label><br>   
                        <button class="btn btn-primary">{{ trans('sucheForm.search') }} </button>
                    </div>
                </div>
               
            </div>
            
        {!! Form::close() !!}
    
    </div>    
    
</fieldset>


<div class="search-results">
    <div class="box-wrapper">
        <h4 class="title">{{ trans('sucheForm.search-results') }} @if(isset($parameter)) für "{{$parameter}}"@endif: <span class="text"> {{count($searchResultsPaginated)}} Ergebnisse gefunden</span></h4> <br>
        
            @if(count($searchResultsPaginated))
                <div class="box search scrollable">
                    <div class="tree-view" data-selector="searchResultsTree">
                        <div class="searchResultsTree hide">
                            {{ $searchResultsTree }}
                        </div>
                    </div>
                </div>
                <div class="text-center">
                    @if(isset($parameter))
                        {!! $searchResultsPaginated->appends(['parameter'=>$parameter])->render() !!}
                    @else
                        {!! $searchResultsPaginated->appends(Request::all())->render() !!}
                    @endif
                </div>
            @else
                <div class="box">
                    <span class="text">Keine Dokumente gefunden.</span>
                </div>
            @endif
        
    </div>
</div>

<div class="clearfix"></div> <br>



@if(isset($resultsWikiPagination) && count($resultsWikiPagination))

<div class="search-results-wiki">
    <div class="box-wrapper">
        <h4 class="title">{{ trans('sucheForm.search-results') }} Wiki: <span class="text"> {{count($resultsWikiPagination)}} Ergebnisse gefunden</span></h4> <br>
            @if(count($resultsWikiPagination))
                <div class="box search scrollable">
                    <div class="tree-view" data-selector="resultsWikiTree">
                        <div class="resultsWikiTree hide">
                            {{ $resultsWikiTree }}
                        </div>
                    </div>
                </div>
                <div class="text-center">
                    {!! $resultsWikiPagination->appends(Request::all())->render() !!}
                </div>
            @else
                <div class="box">
                    <span class="text">Keine Wiki Einträge gefunden.</span>
                </div>
            @endif
    </div>
</div>

<div class="clearfix"></div> <br>

@endif

@stop



    

    
    
