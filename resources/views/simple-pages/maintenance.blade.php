{{-- Client managment--}}

@extends('master')

@section('page-title')
    {{ ucfirst(trans('maintenance.databaseCleanup')) }}
@stop


@section('content')

<div class="row">
    
    <div class="col-xs-12">
        
        <a href="{{url('neptun-verwaltung/datenbank-bereinigen/delete-sending-published')}}" class="btn btn-primary delete-prompt" 
        data-text="Wollen Sie die Versand Daten wirklich löschen?"> {{ trans('maintenance.dbCleanSendingPublished') }} </a> <br>
            
    </div>
    
</div>

<div class="clearfix"></div> <br>

@stop