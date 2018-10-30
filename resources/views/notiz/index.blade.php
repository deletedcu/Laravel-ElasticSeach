{{-- ISO DOKUMENTE --}}

@extends('master')

@section('page-title')
    @lang('juristenPortal.notes')
@stop
@section('content')

<div class="clearfix"></div> 
   
         <div class="col-xs-12 box-wrapper box-white">
            <h2 class="title">@lang('juristenPortal.notes')</h2>
            <div class="search box">
                <div class="row">
                    {!! Form::open(['url' => '/notiz/suche', 'method'=>'GET']) !!}
                        <input type="hidden" name="document_type_id" value="{{ \App\DocumentType::NOTIZEN }}">
                        <div class="input-group">
                            <div class="col-md-12 col-lg-12">
                                {!! ViewHelper::setInput('search', '', old('search',$searchString), trans('navigation.search_placeholder'), trans('navigation.search_placeholder'), true) !!}
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
        
        <div class="clearfix"></div> <br>
        
        <div class="row">
            <div class="col-xs-12">
                <div class="box-wrapper box-white">
                    <h2 class="title">
                        Alle notizen
                        <!--<a href="{{-- action('DocumentController@isoCategoriesBySlug', ['slug' => str_slug($category->name), 'documents' => 'alle'  , 'sort' => 'asc']) --}}"><i class="fa fa-arrow-up" aria-hidden="true"></i></a>-->
                        <!--<a href="{{-- action('DocumentController@isoCategoriesBySlug', ['slug' => str_slug($category->name), 'documents' => 'alle'  , 'sort' => 'desc']) --}}"><i class="fa fa-arrow-down" aria-hidden="true"></i></a>-->
                    </h2>
                        @if( isset($documents) && count($documents))
                            <div class="box scrollable">
                                <div class="tree-view" data-selector="isoAllTree">
                                    <div class="isoAllTree hide">
                                        {{ $documentsTree }}
                                    </div>
                                </div>
                            </div>
                            <div class="text-center">
                                @if( isset($sort) && ($sort == 'asc' || $sort == 'desc') && ($docs == 'alle') ) 
                                    {!! $documents->appends(['documents'=>$docs, 'sort'=>$sort])->render() !!}
                                @else
                                    {!! $documents->render() !!}
                                @endif
                            </div>
                        @else
                            <div class="box">
                                <span>Keine Dokumente gefunden.</span>
                            </div>
                        @endif
                     </div>   
                </div>
            </div>
        
  
    <!--</div>-->
    
    
<div class="clearfix"></div> <br>

@stop