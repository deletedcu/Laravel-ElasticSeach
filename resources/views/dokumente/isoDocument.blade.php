{{-- ISO DOKUMENTE --}}

@extends('master')

@section('page-title')
    Dokumente - ISO-Dokumente 
    @if($isoCategoryParent)- {{$isoCategoryParent->name}}@endif
    @if($isoCategory)- {{$isoCategory->name}}@endif
@stop

@section('content')

<div class="clearfix"></div> 
    {{-- if category dosen't have documents and has subcategories --}}
    @if( (count($isoEntwurfPaginated) < 1 || count($isoFreigabePaginated) < 1 || count($isoAllPaginated)  < 1 ) 
    && count($categoryIsParent) )
        <div class="row">
            <div class="col-xs-12">
                <div class="col-xs-12 box-wrapper box-white">
            
            <h2 class="title">Alle Kategorien</h2>
            
            <div class="box iso-category-overview">
                
                @if(count($categoryIsParent))
                    
                    <ul class="level-1">
                        @foreach($categoryIsParent as $isoCategory)
                            @if($isoCategory->active)
                                <li>
                                    <a href="{{ url('iso-dokumente/'. $isoCategory->slug) }}">{{ $isoCategory->name }}</a>
                                    <ul class="level-2">
                                    @foreach($categoryIsParent as $isoCategoryChild)
                                        @if($isoCategoryChild->iso_category_parent_id == $isoCategory->id)
                                            <li><a href="{{ url('iso-dokumente/'. $isoCategoryChild->slug ) }}">{{$isoCategoryChild->name}}</a></li>
                                        @endif
                                    @endforeach
                                    </ul>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                @else
                    Keine Einträge gefunden.
                @endif
                
            </div>

        </div>
    </div><!-- end .col-xs-12-->
    
        </div><!--end .row-->
    
    @else
        <div class="row">
            @if(( $docType->document_art == 1 &&  ViewHelper::universalHasPermission( array(13) ) == true )
              || ( $docType->document_art == 0 && ( ViewHelper::universalHasPermission( array(11) ) == true) ))
                <div class="col-xs-12 col-md-6">
                    <div class="box-wrapper box-white">
                        <h2 class="title">{{ trans('isoDokument.isoEntwurf') }}</h2>
                        @if(count($isoEntwurfPaginated))
                            <div class="box scrollable">
                                <div class="tree-view" data-selector="isoEntwurfTree">
                                    <div class="isoEntwurfTree hide">
                                        {{ $isoEntwurfTree }}
                                    </div>
                                </div>
                            </div>
                            <div class="text-center ">
                                {!! $isoEntwurfPaginated->render() !!}
                            </div>
                        @else
                            <div class="box">
                                <span class="text">Keine Dokumente gefunden.</span>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
            
            @if(( $docType->document_art == 1 &&  ViewHelper::universalHasPermission( array(13) ) == true )
                || ($docType->document_art == 0 && ( ViewHelper::universalHasPermission( array(11) ) == true) ))
                <div class="col-xs-12 col-md-6">
                    <div class="box-wrapper box-white">
                        <h2 class="title">{{ trans('isoDokument.isoFreigabe') }}</h2>
                        @if(count($isoFreigabePaginated))
                            <div class="box scrollable">
                                <div class="">
                                    <div class="tree-view" data-selector="isoFreigabeTree">
                                        <div class="isoFreigabeTree hide">
                                            {{ $isoFreigabeTree }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="text-center ">
                                {!! $isoFreigabePaginated->render() !!}
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
        
        <div class="clearfix"></div> @if( count($isoEntwurfPaginated) || count( $isoFreigabePaginated ) )<br>@endif
        
        <div class="col-xs-12 box-wrapper box-white">
            <h2 class="title">{{ trans('documentForm.searchTitle') }}  @if($isoCategoryParent) {{$isoCategoryParent->name}} -@endif
        @if($isoCategory) {{$isoCategory->name}}@endif</h2>
            <div class="search box">
                <div class="row">
                    {!! Form::open(['url' => '/dokumente/suche', 'method'=>'GET']) !!}
                        <input type="hidden" name="document_type_id" value="{{ $docType->id }}">
                        @if(isset($iso_category_id)) <input type="hidden" name="iso_category_id" value="{{ $iso_category_id }}"> @endif
                        <div class="input-group">
                            <div class="col-md-12 col-lg-12">
                                {!! ViewHelper::setInput('search', old('search'), old('search'), trans('navigation.search_placeholder'), trans('navigation.search_placeholder'), true) !!}
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
                        Alle @if($isoCategory) {{$isoCategory->name}} @endif
                        <a href="{{ action('DocumentController@isoCategoriesBySlug', ['slug' => str_slug($isoCategory->name), 'documents' => 'alle'  , 'sort' => 'asc']) }}"><i class="fa fa-arrow-up" aria-hidden="true"></i></a>
                        <a href="{{ action('DocumentController@isoCategoriesBySlug', ['slug' => str_slug($isoCategory->name), 'documents' => 'alle'  , 'sort' => 'desc']) }}"><i class="fa fa-arrow-down" aria-hidden="true"></i></a>
                    </h2>
                        @if(count($isoAllPaginated))
                            <div class="box scrollable">
                                <div class="tree-view" data-selector="isoAllTree">
                                    <div class="isoAllTree hide">
                                        {{ $isoAllTree }}
                                    </div>
                                </div>
                            </div>
                            <div class="text-center">
                                @if(($sort == 'asc' || $sort == 'desc') && ($docs == 'alle')) 
                                    {!! $isoAllPaginated->appends(['documents'=>$docs, 'sort'=>$sort])->render() !!}
                                @else
                                    {!! $isoAllPaginated->render() !!}
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
    @endif
    <!--</div>-->
    
    
<div class="clearfix"></div> <br>

@stop
@section('afterScript')
            <!--patch for checking iso category document-->
            @if( isset($isoCategory->name) )
                <script type="text/javascript">
                    var isoCategoryName =  '{{str_slug($isoCategory->name)}}';
                    var detectHref = $('#side-menu').find('a:contains("'+isoCategoryName+'")');
                      
                        
                     setTimeout(function(){
                         $('a[href$="'+detectHref+'"]').addClass('active').attr('class','active').parents("ul").not('#side-menu').addClass('in');
                        $('#side-menu').find('a.active').parent('li').find('ul').addClass('in');
                              $('a[href$="'+detectHref+'"]').parent("li").find('ul').addClass('in');
                        
                     },1000 );
                </script>
            @endif
                    <!-- End variable for expanding document sidebar-->
        @stop