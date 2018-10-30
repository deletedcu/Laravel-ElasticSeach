@extends('master')

@section('page-title') {{ ucfirst( trans('controller.dokumente')) }} - Formulare  @stop

@section('content')

{{-- compact('docType', 'formulareAllPaginated', 'formulareAllTree', 'formulareEntwurfPaginated', 'formulareEntwurfTree', 'formulareFreigabePaginated', 'formulareFreigabeTree') --}}

<div class="row">
    
    @if( 
     ( $docType->document_art == 1 &&  ViewHelper::universalHasPermission( array(13) ) == true )
      ||  ( $docType->document_art == 0 && ( ViewHelper::universalHasPermission( array(11) ) == true) )
     || ViewHelper::universalHasPermission( array(10) ) == true ) 
     
    <div class="col-xs-12 col-md-6">
        <div class="box-wrapper box-white">
            <h2 class="title">{{ trans('documentTemplates.templateEntwurf') }}</h2>
            @if(count($formulareEntwurfPaginated))
                <div class="box scrollable">
                    <div class="tree-view" data-selector="formulareEntwurfTree">
                        <div class="formulareEntwurfTree hide">
                            {{ $formulareEntwurfTree }}
                        </div>
                    </div>
                </div>
                <div class="text-center">
                    {!! $formulareEntwurfPaginated->render() !!}
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
            <h2 class="title">{{ trans('documentTemplates.templateFreigabe') }}</h2>
                @if(count($formulareFreigabePaginated))
                    <div class="box scrollable">
                        <div class="tree-view" data-selector="formulareFreigabeTree">
                            <div class="formulareFreigabeTree hide">
                                {{ $formulareFreigabeTree }}
                            </div>
                        </div>
                    </div>
                    <div class="text-center">
                        {!! $formulareFreigabePaginated->render() !!}
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

<div class="clearfix"></div> @if( count($formulareEntwurfPaginated) || count( $formulareFreigabePaginated ) )<br>@endif

<div class="row">
    <div class="col-xs-12">
        <div class="box-wrapper box-white">
            <h2 class="title">{{ trans('documentForm.searchTitle') }} {{ $docType->name }}</h2>
            <div class="box">
                <div class="row">
                    {!! Form::open(['url' => '/dokumente/suche', 'method'=>'GET']) !!}
                    <input type="hidden" name="document_type_id" value="{{ $docType->id }}">
                        <div class="input-group">
                            <div class="col-md-12 col-lg-12">
                                {!! ViewHelper::setInput('search', '', old('search'), trans('navigation.newsSearchPlaceholder'), trans('navigation.newsSearchPlaceholder'), true) !!}
                            </div>
                            <div class="col-md-12 col-lg-12">
                                <span class="custom-input-group-btn">
                                    <button type="submit" class="btn btn-primary no-margin-bottom">
                                        {{ trans('documentForm.searchButton') }} 
                                    </button>
                                </span>
                            </div>
                        </div>
                   </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="clearfix"></div> <br>

<div class="row">
    <div class="col-xs-12">
        <div class="box-wrapper box-white">
            <h2 class="title">
                Alle {{ trans('documentTemplates.allDocuments') }}
                <a href="{{ action('DocumentController@documentTemplates', ['documents' => 'alle'  , 'sort' => 'asc']) }}"><i class="fa fa-arrow-up" aria-hidden="true"></i></a>
                <a href="{{ action('DocumentController@documentTemplates', ['documents' => 'alle'  , 'sort' => 'desc']) }}"><i class="fa fa-arrow-down" aria-hidden="true"></i></a>
            </h2>
            
            @if(count($formulareAllPaginated))
                <div class="box scrollable">
                    <div class="tree-view" data-selector="formulareAllPaginated">
                        <div class="formulareAllPaginated hide">
                            {{ $formulareAllTree }}
                        </div>
                    </div>
                </div>
                <div class="text-center">
                    @if(($sort == 'asc' || $sort == 'desc') && ($docs == 'alle')) 
                        {!! $formulareAllPaginated->appends(['documents'=>$docs, 'sort'=>$sort])->render() !!}
                    @else
                        {!! $formulareAllPaginated->render() !!}
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

<div class="clearfix"></div><br/>

@stop
