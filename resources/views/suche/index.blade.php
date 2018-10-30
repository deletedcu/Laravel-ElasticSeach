{{-- SUCHE --}}

@extends('master')

@section('page-title') {{ trans('sucheForm.search') }} @stop

@section('content')

<h4 class="title">{{ trans('sucheForm.search-results') }}: 0 </h4>

<div class="row">
    <div class="col-xs-12">
        {{$results}}
    </div>
</div>

<div class="clearfix"></div> <br>

@stop
