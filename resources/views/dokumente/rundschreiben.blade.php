{{-- DOKUMENTE RUNDSCHREIBEN --}}

@extends('master')

@section('page-title')
  {{ ucfirst( trans('controller.dokumente')) }} -  Rundschreiben
@stop


@section('content')

{{-- compact('rundEntwurfPaginated', 'rundEntwurfTree', 'rundFreigabePaginated', 'rundFreigabeTree', 'rundAllPaginated', 'rundAllTree') --}}

<div class="row">
    
    @if( 
     ( $docType->document_art == 1 &&  ViewHelper::universalHasPermission( array(13) ) == true )
      ||  ( $docType->document_art == 0 && ( ViewHelper::universalHasPermission( array(11) ) == true) )
     || ViewHelper::universalHasPermission( array(10) ) == true ) 
    <div class="col-xs-12 col-md-6">
        
        <div class="box-wrapper box-white">
            
            <h2 class="title">{{ trans('rundschreiben.rundEntwurf') }}</h2>
            
            @if(count($rundEntwurfPaginated))
                
                <div class="box scrollable">
                    <div class="tree-view" data-selector="rundEntwurfTree">
                        <div class="rundEntwurfTree hide">
                            {{ $rundEntwurfTree }}
                        </div>
                    </div>
                </div>
                <div class="text-center">
                    {!! $rundEntwurfPaginated->render() !!}
                </div>
            @else
                <div class="box">
                    <span class="text">Keine Dokumente gefunden.</span>
                </div>
            @endif
            
        </div>
        
    </div>
    @endif
    
    @if( 
     ( $docType->document_art == 1 &&  ViewHelper::universalHasPermission( array(13) ) == true )
      ||  ( $docType->document_art == 0 && ( ViewHelper::universalHasPermission( array(11) ) == true) )
     || ViewHelper::universalHasPermission( array(10) ) == true ) 
    <div class="col-xs-12 col-md-6">
        
        <div class="box-wrapper box-white">
            
            <h2 class="title">{{ trans('rundschreiben.rundFreigabe') }}</h2>
            
            @if(count($rundFreigabePaginated))
            
                <div class="box scrollable">
                    <div class="tree-view" data-selector="rundFreigabeTree">
                        <div class="rundFreigabeTree hide">
                            {{ $rundFreigabeTree }}
                        </div>
                    </div>
                </div>
                
                <div class="text-center">
                    {!! $rundFreigabePaginated->render() !!}
                </div>
            
            @else
                <div class="box">
                    <span class="text">Keine Dokumente gefunden.</span>
                </div>
            @endif
            
        </div>
        
    </div>
    @endif
    
    
</div>

<div class="clearfix"></div> @if( count($rundEntwurfPaginated) || count( $rundFreigabePaginated ) )<br>@endif

<div class="col-xs-12 box-wrapper box-white">
    <h2 class="title">{{ trans('documentForm.searchTitle') }} {{ $docType->name }}</h2>
    <div class="box">
        <div class="row">
            {!! Form::open(['url' => '/dokumente/suche', 'method'=>'GET']) !!}
                <div class="input-group">
                    <div class="col-md-12 col-lg-12">
                        {!! ViewHelper::setInput('search', '', old('search'), trans('navigation.search_placeholder'), trans('navigation.search_placeholder'), true) !!}
                        <input type="hidden" name="document_type_id" value="{{ $docType->id }}">
                    </div>
                    <div class="col-md-12 col-lg-12">
                        <span class="custom-input-group-btn">
                            <button type="submit" class="btn btn-primary no-margin-bottom">
                                {{ trans('documentForm.searchButton') }} 
                            </button>
                        </span>
                    </div>
                </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>

<div class="clearfix"></div> <br>

<div class="row">
    
    <div class="col-xs-12">
        <div class="box-wrapper box-white">
            
            <h2 class="title">
                Alle Rundschreiben
                <a href="{{ action('DocumentController@rundschreiben', ['documents' => 'alle'  , 'sort' => 'asc']) }}"><i class="fa fa-arrow-up" aria-hidden="true"></i></a>
                <a href="{{ action('DocumentController@rundschreiben', ['documents' => 'alle'  , 'sort' => 'desc']) }}"><i class="fa fa-arrow-down" aria-hidden="true"></i></a>
            </h2>
            
            @if(count($rundAllPaginated))
            
                <div class="box scrollable">
                    <div class="tree-view" data-selector="rundAllPaginated">
                        <div class="rundAllPaginated hide">
                            {{ $rundAllTree }}
                        </div>
                    </div>
                </div>
                
                <div class="text-center">
                    @if(($sort == 'asc' || $sort == 'desc') && ($docs == 'alle')) 
                        {!! $rundAllPaginated->appends(['documents'=>$docs, 'sort'=>$sort])->render() !!}
                    @else
                        {!! $rundAllPaginated->render() !!}
                    @endif
                </div>
                
            @else
                <div class="box">
                    <span class="text">Keine Dokumente gefunden.</span>
                </div>
            @endif
            
        </div>
    </div>
    
</div>

<div class="clearfix"></div> <br>

@stop
