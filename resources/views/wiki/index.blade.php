{{-- TELEFONLISTE --}}

@extends('master')

@section('page-title') {{ trans('wiki.wikiStart') }} @stop

@section('content')

<div class="row">
    <div class="col-sm-6 ">
        <div class="box-wrapper">
            <h2 class="title">Suche Wiki</h2>
            <div class="box  box-white">
                <div class="row">
                    {!! Form::open(['action' => 'WikiController@search', 'method'=>'POST']) !!}
                        <div class="input-group">
                            <div class="col-md-12 col-lg-12">
                                @if( isset($searchInput) ) 
                                    {!! ViewHelper::setInput('search', '',$searchInput, trans('navigation.wikiSearchPlaceholder'), trans('navigation.wikiSearchPlaceholder'), true) !!}
                                @else
                                    {!! ViewHelper::setInput('search', '',old('search'), trans('navigation.wikiSearchPlaceholder'), trans('navigation.wikiSearchPlaceholder'), true) !!}
                                @endif
                            </div>
                            <div class="col-md-12 col-lg-12">
                                <span class="custom-input-group-btn">
                                    <button type="submit" class="btn btn-primary no-margin-bottom">
                                        {{ trans('navigation.search') }} 
                                    </button>
                                </span>
                            </div>
                        </div>
                    {!! Form::close() !!}
                </div>
            </div><!-- end box -->
        </div><!-- end box wrapper-->
    </div>
    
    @if( count( $topCategories ) )
    <!-- top categorie box-->
    <div class="col-xs-6">
        <div class="box-wrapper">
            <h2 class="title">Top Kategorien</h2>
            <div class="box box-white">
                <div class="row">
                    <div class="col-md-12">
                        @foreach( $topCategories as $cat )
                            <!--link box-->
                            <div class="col-sm-4">
                                <a href="{{ url('wiki-kategorie/'.$cat->id) }}"><h4>{{$cat->name}}</h4></a>
                            </div><!--end link box-->
                        @endforeach
                    </div><!-- end col-md-12-->
                </div><!-- end row-->
            </div><!-- end box -->
        </div>
    </div><!--end  top categorie box wrapper-->
    @endif
    
    @if( isset($search) && count( $search ) )
    <!-- top categorie box-->
    <div class="col-sm-12">
        <div class="box-wrapper">
            <h2 class="title">{{ trans('wiki.searchResults') }}</h2>
            <div class="box  box-white wiki-search">
                <div class="row">
                    <div class="col-md-12">
                        @foreach( $search as $s )
                            <!--link box-->
                            <div class="col-md-2">
                                <a href="{{ url('wiki/'.$s->id) }}"><h4>{!! ViewHelper::highlightKeyword($searchInput, $s->name) !!} <small>{!! ViewHelper::highlightKeyword($searchInput, $s->subject) !!}</small></h4></a>
                            </div><!--end link box-->
                        @endforeach
                    </div><!-- end col-md-12-->
                </div><!-- end row-->
            </div><!-- end box -->
             <div class="text-center">
                    {!! $search->render() !!}
            </div>
        </div><!--end  top categorie box wrapper-->
    </div>
    @endif
    
</div>

<div class="row">
    
    {{--
    @if( $newestWikiEntries )
    
    <!-- top categorie box-->
    <div class="col-xs-6 ">
        <div class="box-wrapper">
            <h2 class="title">Neueste Beiträge (Änderungen)</h2>
            <div class="box box-white">
                <div class="row">
                    <div class="col-md-12">
                        @foreach( $newestWikiEntries as $entry )
                            <!--link box-->
                            <div class="col-md-12">
                                <a href="{{ url('wiki/'.$entry->id) }}">
                                    {{ $entry->user->first_name }} {{ $entry->user->last_name }} - {{ $entry->created_at }} - {{$entry->name}} 
                                </a>
                            </div><!--end link box-->
                        @endforeach
                    </div><!-- end col-md-12-->
                </div><!-- end row-->
                
                <!-- pagination box -->
                <div class="text-ceter">
                    {!! $newestWikiEntries->render() !!}
                </div><!-- end pagination box -->
            
            </div><!-- end box -->
        </div><!--end  top categorie box wrapper-->
    </div>
    
    @endif
    --}}
    @if( $searchResults )
        <div class="col-xs-12 col-md-6 ">
        <div class="col-xs-12 box-wrapper home">
            <h1 class="title">@lang('sucheForm.search-results')</h1>
                @if( count( $searchResults ) )
                <div class="box home">
                    <div class="tree-view" data-selector="newestWikiEntries">
                        <div class="newestWikiEntries hide">
                            {{ $searchResultsTree }}
                        </div>
                    </div>
                </div>
              <div class="text-center">
                {!! $searchResults->render() !!}
            </div>
            @else
            <div class="box home">
                Es wurde kein passender Eintrag gefunden.
            </div>
            @endif
        </div>
    </div>
    @endif
    
    @if( count( $newestWikiEntriesPagination ) )
    <div class="col-xs-12 col-md-6 ">
        <div class="col-xs-12 box-wrapper home">
            <h1 class="title">Alle Wiki-Einträge</h1>
            <div class="box home">
                <div class="tree-view" data-selector="newestWikiEntries">
                    <div class="newestWikiEntries hide">
                        {{ $newestWikiEntries }}
                    </div>
                </div>
            </div>
              <div class="text-center">
                {!! $newestWikiEntriesPagination->render() !!}
            </div>
        </div>
    </div>
    @endif
    
    <!-- fix so the grid dosen't mess up -->
    @if( $searchResults )
        <div class="clearfix"></div>
    @endif
    
    @if( ViewHelper::universalHasPermission(array(15)) )
        @if( count( $myWikiPagesPagination ) )
            <div class="col-xs-12 col-md-6 ">
            <div class="col-xs-12 box-wrapper home">
                <h1 class="title">Meine Beiträge</h1>
                <div class="box home">
                    <div class="tree-view" data-selector="myWikiPages">
                        <div class="myWikiPages hide">
                            {{ $myWikiPages }}
                        </div>
                    </div>
                </div>
                  <div class="text-center">
                    {!! $myWikiPagesPagination->render() !!}
                </div>
            </div>
        </div>
        @endif
    @endif
    
    
</div><!-- end main row-->


@stop
