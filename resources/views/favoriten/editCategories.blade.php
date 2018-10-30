{{-- FAVORITEN KATEGORIEVERWALTUNG--}}

@extends('master')
@section('page-title') {{ trans('favoriten.favorite-categories') }} @stop

@section('content')

<fieldset class="form-group">
    <div class="box-wrapper">
        <h4 class="title">{{ trans('favoriten.category') }} {{ trans('favoriten.add') }}</h4>
        <div class="box box-white">
            <div class="row">
                <div class="col-md-5">
                    {!! Form::open(['action' => 'FavoritesController@storeFavoriteCategory']) !!}
                        <div>
                            <label>{{ trans('favoriten.name') }}*</label>
                            <input type="text" class="form-control" name="name" placeholder="{{ trans('favoriten.name') }}*" required/>
                            <div class="custom-input-group-btn"><button class="btn btn-primary no-margin-bottom" type="submit">{{ trans('favoriten.add') }}</button></div>
                        </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</fieldset>

@if(count($favoriteCategories))

<fieldset class="form-group">
    <div class="box-wrapper">
        <div class="row">
            <div class="col-xs-12">
                <h4 class="title">{{ trans('favoriten.overview') }}</h4>
                <div class="box box-white">
                    @foreach($favoriteCategories as $category)
                    <div class="row">
                        {!! Form::open(['action' => 'FavoritesController@updateFavoriteCategory', 'method' => 'PATCH']) !!}
                            <div class="col-xs-12 col-md-6 col-lg-5">
                                 <input type="text" class="form-control" name="name" value="{{ $category->name }}" placeholder="{{ trans('favoriten.name') }}*" required/>
                            </div>
                            <div class="col-xs-12 col-md-6 col-lg-5">
                                <input type="hidden" name="category_id" value="{{ $category->id }}">
                                <button class="btn btn-primary" type="submit" name="save" value="1">{{ trans('favoriten.save') }}</button>
                                <button class="btn btn-danger delete-prompt" type="submit" name="delete" value="1">{{ trans('favoriten.remove') }}</button>
                            </div>
                        {!! Form::close() !!}
                    </div>
                    <br>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</fieldset>

@else

<p>Sie haben noch keine Favoriten Kategorien angelegt.</p>

@endif

<div class="clearfix"></div><br>

@stop