{{-- DOKUMENTE SUCHERGEBNISSE --}}

@extends('master')

@section('page-title')
    @if($docTypeName)
        Dokumente - {{$docTypeName}}
    @else
        Dokumente - Übersicht
    @endif
@stop


@section('content')


<div class="row">
    
    {{--
    <div class="col-xs-12">
        <div class="col-xs-12 box-wrapper">
            
            <h2 class="title"> Meine @if($docTypeName) {{$docTypeName}} @else Dokumente @endif </h2>
            
            <div class="box">
                @if(isset($resultMyTree))
                    <div class="tree-view" data-selector="rundschreibenMeine">
                        <div class="rundschreibenMeine hide">
                            {{ $resultMyTree }}
                        </div>
                    </div>
                @else
                    Keine Daten gefunden.        
                @endif
            </div>
            <div class="text-center">
                @if(isset($resultMyPaginated))
                    {!! $resultMyPaginated->render() !!}
                @endif
            </div>
            
        </div>
    </div>
    --}}
    
     @if( 
     ( $docTypeSearch->document_art == 1 &&  ViewHelper::universalHasPermission( array(13) ) == true )
      ||  ( $docTypeSearch->document_art == 0 && ( ViewHelper::universalHasPermission( array(11) ) == true) )
     )
    <div class="col-xs-12 col-md-6">
        
        <div class="box-wrapper box-white">
            
            <h2 class="title">{{ trans('rundschreiben.rundEntwurf') }}</h2>
            
            @if(count($searchEntwurfPaginated))
                
                <div class="box scrollable">
                    <div class="tree-view" data-selector="searchEntwurfTree">
                        <div class="searchEntwurfTree hide">
                            {{ $searchEntwurfTree }}
                        </div>
                    </div>
                </div>
                
                <div class="text-center ">
                    {!! $searchEntwurfPaginated->render() !!}
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
     ( $docTypeSearch->document_art == 1 &&  ViewHelper::universalHasPermission( array(13) ) == true )
      ||  ( $docTypeSearch->document_art == 0 && ( ViewHelper::universalHasPermission( array(11) ) == true) )
     )
    <div class="col-xs-12 col-md-6">
        
        <div class="box-wrapper box-white">
            
            <h2 class="title">{{ trans('rundschreiben.rundFreigabe') }}</h2>
            
            @if(count($searchFreigabePaginated))
            
                <div class="box scrollable">
                    <div class="tree-view" data-selector="searchFreigabeTree">
                        <div class="searchFreigabeTree hide">
                            {{ $searchFreigabeTree }}
                        </div>
                    </div>
                </div>
                
                <div class="text-center ">
                    {!! $searchFreigabePaginated->render() !!}
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

<div class="clearfix"></div> <br>


<div class="row">
    <div class="col-xs-12">
        <div id="search-form" class="col-xs-12 box-wrapper">
            <h2 class="title">{{ trans('documentForm.searchTitle') }} {{ $docTypeName }}</h2>
            <div class="box">
                <div class="row">
                    {!! Form::open(['action' => 'DocumentController@search', 'method'=>'GET']) !!}
                        <div class="input-group">
                            <div class="col-md-12 col-lg-12">
                                {!! ViewHelper::setInput('search', '', $search, trans('navigation.search_placeholder'), trans('navigation.search_placeholder'), true) !!}
                                @if(isset($docType)) <input type="hidden" name="document_type_id" value="{{ $docType }}"> @endif
                                @if(isset($iso_category_id)) <input type="hidden" name="iso_category_id" value="{{ $iso_category_id }}"> @endif
                                @if(isset($docs)) <input type="hidden" name="documents" value="{{ $docs }}"> @endif
                                @if(isset($sort)) <input type="hidden" name="sort" value="{{ $sort }}"> @endif
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

<div class="clearfix"></div> <br>

<div class="row">
    
    <div class="col-xs-12">
        <div class="col-xs-12 box-wrapper box-white">
            
            <h2 class="title">
                Alle @if($docTypeName) {{$docTypeName}} @else Dokumente @endif
                <a href="{{ action('DocumentController@search', ['search' => $search, 'document_type_id' => $docType, 'iso_category_id' => $iso_category_id, 'documents' => 'alle'  , 'sort' => 'asc']) }}"><i class="fa fa-arrow-up" aria-hidden="true"></i></a>
                <a href="{{ action('DocumentController@search', ['search' => $search, 'document_type_id' => $docType, 'iso_category_id' => $iso_category_id,'documents' => 'alle'  , 'sort' => 'desc']) }}"><i class="fa fa-arrow-down" aria-hidden="true"></i></a>
            </h2>
            
            @if(count($resultAllPaginated))
                <div class="box scrollable">
                    <div class="tree-view" data-selector="rundschreibenMeine">
                        <div class="rundschreibenMeine hide">
                            {{ $resultAllTree }}
                        </div>
                    </div>
                </div>
                <div class="text-center ">
                    {{ $resultAllPaginated->appends(['search' => $search, 'document_type_id' => $docType, 'iso_category_id' => $iso_category_id, 'documents'=>$docs, 'sort'=>$sort])->render() }}
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

   
    @section('afterScript')
    
        @if($docTypeSearch)
            <!--patch for checking iso category document-->
            <script type="text/javascript">   
                var slug = '{{$docTypeSlug}}';   
            
             </script>
        @endif
    @stop
