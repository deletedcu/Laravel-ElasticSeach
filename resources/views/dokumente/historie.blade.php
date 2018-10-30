{{-- HISTORIE --}}

@extends('master')

@section('page-title')
     {{ trans('controller.dokumente') }} {{ trans('controller.history') }}
@stop

@section('content')

    <div class="row">
        
        <div class="col-xs-12">
            <div class="col-xs-12 box-wrapper">
                
                <h2 class="title">{{ trans('controller.overview') }}</h2>
                
                <div class="box">
                    <div class="tree-view" data-selector="dokumentHistorie">
                        <div class="dokumentHistorie hide">
                            {{ $documentHistoryTree }}
                        </div>
                    </div>
                </div>
                 <div class="text-center">
                        {{ $documentHistory->render() }}
                </div>
    
                
            </div>
        </div>
        
    </div>
    
    <div class="clearfix"></div> <br>
    
@stop
