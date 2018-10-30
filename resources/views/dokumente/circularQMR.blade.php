@extends('master')

@section('page-title')
    {{ ucfirst( trans('controller.dokumente')) }} - QM-Rundschreiben 
@stop

    @section('content')
    <div class="row">
            @if( 
             ( $docType->document_art == 1 &&  ViewHelper::universalHasPermission( array(13) ) == true )
              ||  ( $docType->document_art == 0 && ( ViewHelper::universalHasPermission( array(11) ) == true) )
             || ViewHelper::universalHasPermission( array(10) ) == true ) 
            <div class="col-xs-12 col-md-6 ">
                <div class="box-wrapper box-white">
                    <h4 class="title">{{ trans('rundschreibenQmr.qmrEntwurf')}}</h4>
                    @if(count($qmrEntwurfPaginated))
                        <div class="box scrollable">
                            <div class="tree-view" data-selector="qmrEntwurfTree">
                                 <div class="qmrEntwurfTree hide" >{{ $qmrEntwurfTree }}</div>
                            </div>
                        </div>
                        <div class="text-center ">
                            {!! $qmrEntwurfPaginated->render() !!}
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
            <div class="col-xs-12 col-md-6 ">
                <div class="box-wrapper  box-white">
                    <h4 class="title">{{ trans('rundschreibenQmr.qmrFreigabe')}}</h4>
                    
                    @if(count($qmrFreigabePaginated))
                        <div class="box scrollable">
                            <div class="tree-view" data-selector="qmrFreigabeTree">
                                 <div class="qmrFreigabeTree hide" >{{ $qmrFreigabeTree }}</div>
                            </div>
                        </div>
                        <div class="text-center ">
                            {!! $qmrFreigabePaginated->render() !!}
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
        <div class="clearfix"></div>@if( count($qmrEntwurfPaginated) || count( $qmrFreigabePaginated ) )<br>@endif
        
        <div class="row">
            <div class="col-xs-12">
                <div class="box-wrapper box-white">
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
            </div>
         </div>
        <div class="clearfix"></div>
        <br/>
            
      
        <div class="col-xs-12 box-wrapper box-white">
            
            <h4 class="title">
                Alle {{ trans('rundschreibenQmr.allQmr')}}
                <a href="{{ action('DocumentController@rundschreibenQmr', ['documents' => 'alle'  , 'sort' => 'asc']) }}"><i class="fa fa-arrow-up" aria-hidden="true"></i></a>
                <a href="{{ action('DocumentController@rundschreibenQmr', ['documents' => 'alle'  , 'sort' => 'desc']) }}"><i class="fa fa-arrow-down" aria-hidden="true"></i></a>
            </h4>
            
            @if(count($qmrAllPaginated))
                <div class="box scrollable">
                    <div class="tree-view" data-selector="qmrAllTree">
                         <div  class="qmrAllTree hide" >{{ $qmrAllTree }}</div>
                    </div>
                </div>
            
                <div class="text-center ">
                    @if(($sort == 'asc' || $sort == 'desc') && ($docs == 'alle')) 
                        {!! $qmrAllPaginated->appends(['documents'=>$docs, 'sort'=>$sort])->render() !!}
                    @else
                        {!! $qmrAllPaginated->render() !!}
                    @endif
                </div>
            @else
                <div class="box">
                    <span class="text">Keine Dokumente gefunden.</span>
                </div>
            @endif
        </div>
        <div class="clearfix"></div>
    @stop
    