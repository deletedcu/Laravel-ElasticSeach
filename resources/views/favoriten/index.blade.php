{{-- FAVORITEN --}}

@extends('master')
@section('page-title') {{ trans('navigation.favorites') }} @stop

@section('content')

@if(!$hasFavorites && !$hasFavoriteCategories)
<p>Sie haben noch keine Favoriten angelegt.</p>
@endif

<div class="row">
<div class="flexbox-wrapper">
    
{{-- DOCUMENT TYPE FAVORITES --}}
@if($hasFavorites)
    
    @foreach($favoritesAll as $favorites)
        
        @if(count($favorites['favoritesPaginated']))
                <div class="col-xs-12 col-md-6">
                    <div class="box-wrapper {{ 'favorites-box-' . $favorites['document_type_id'] }}">
                        
                        <h4 class="title">
                            {{ $favorites['document_type_name'] }}
                            <a href="{{ action('FavoritesController@index', ['type' => $favorites['document_type_id'], 'sort' => 'asc']) }}"><i class="fa fa-arrow-up" aria-hidden="true"></i></a>
                            <a href="{{ action('FavoritesController@index', ['type' => $favorites['document_type_id'], 'sort' => 'desc']) }}"><i class="fa fa-arrow-down" aria-hidden="true"></i></a>
                        </h4>

                        <div class="box box-white box-linklist">
                            <div class="box box-white box-treeview">
                                <div class="tree-view" data-selector="{{ 'favorites-' . $favorites['document_type_id'] }}">
                                    <div class="{{ 'favorites-' . $favorites['document_type_id'] }} hide">{{ $favorites['favoritesTreeview'] }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="text-center box-white box box-pagination">
                            {{ $favorites['favoritesPaginated']->appends(['type' => $favorites['document_type_id'], 'sort' => Request::get('sort')])->render() }}
                        </div>

                    </div>
                </div>
            
        @endif

    @endforeach
    
@endif


{{-- USER DEFINED FAVORITES CATEGORIES --}}
@if($hasFavoriteCategories)
    
    @foreach($favoritesCategorised as $favorites)
        @if(count($favorites['favoritesPaginated']))
                <div class="col-xs-12 col-md-6">
                    <div class="box-wrapper {{ 'favorites-box-' . $favorites['category']->id }}">
                        
                        <h4 class="title">
                            {{ $favorites['category']->name }}
                            <a href="{{ action('FavoritesController@index', ['category' => $favorites['category']->id, 'sort' => 'asc']) }}"><i class="fa fa-arrow-up" aria-hidden="true"></i></a>
                            <a href="{{ action('FavoritesController@index', ['category' => $favorites['category']->id, 'sort' => 'desc']) }}"><i class="fa fa-arrow-down" aria-hidden="true"></i></a>
                        </h4>
                        
                        <div class="box box-white box-linklist">
                             <div class="box box-white box-treeview">
                                <div class="tree-view" data-selector="{{ 'favorites-' . $favorites['category']->id }}">
                                    <div class="{{ 'favorites-' . $favorites['category']->id }} hide">{{ $favorites['favoritesTreeview'] }}</div>
                                </div>
                            </div> 
                        </div>
                        
                        <div class="text-center box-white box box-pagination">
                            {{ $favorites['favoritesPaginated']->appends(['category' => $favorites['category']->id, 'sort' => Request::get('sort')])->render() }}
                        </div>
                        
                    </div>
                </div>
            
        @endif
    @endforeach
    
@endif

</div>
</div>

@stop