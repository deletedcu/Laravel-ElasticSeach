@extends('master')

@section('page-title')
     {{ trans('controller.documentUpload') }}
@stop

    @section('content')

        <div class="col-xs-12 col-md-6 box">
            <h2>QMR 223 <small>({{ trans('dataUpload.version') }}: 2 )</small> <small><b>{{ trans('dataUpload.status') }}:</b> Veröffentlicht</small></h2>
        </div>
        <div class="col-xs-12 col-md-6 box">
            
                <a href="#" class="btn btn-primary">
                    <!--<span class="fa fa-edit"></span> -->
                    {{ trans('dataUpload.edit') }}</a> 
                
                <a href="#" class="btn btn-danger">
                    <!--<span class="fa fa-trash"></span> -->
                    {{ trans('dataUpload.delete') }}</a>
            
                <a href="#" class="btn btn-info">
                    <!--<span class="fa fa-files-o"></span> -->
                    {{ trans('dataUpload.newVersion') }}</a>
           
                <a href="#" class="btn btn-info">
                    <!--<span class="fa fa-history"></span> -->
                {{ trans('dataUpload.history') }}</a>
          
                <a href="#" class="btn btn-info">
                    <!--<span class="fa fa-star"></span> -->
                {{ trans('dataUpload.favorite') }}</a>
        </div>
        <div class="clearfix"></div>
         <div class="col-xs-12 col-md-12 box">
             <h3>Betreff </h3>
             <a href="#" class="btn btn-primary"> 
             <!--<span class="fa fa-download"></span> -->
             {{ trans('dataUpload.download') }}</a>
         </div>
         <div class="clearfix"></div>
        <br/>
        <div class="col-xs-12 col-md-12 box">
            <p>Datei ist angehängt an den folgenden Rundschreiben/ ISO-Dokumenten</p>
            <p>#1 Runschreiben QMR: QMR 213 - Mindestlohn Änderungen - 06.04.2016 [STATUS -> V oder Archiv]
                 Lorem Ispum... <a href="#" class="">mehr <span class="fa fa-angle-right"></span></a> </p>
        </div>
        <div class="clearfix"></div>
        <br/>
        <div class="col-xs-12 col-md-6 box">
            <div class="tree-view" data-selector="test">
             <div  class="test hide" >{{$data}}</div>
            </div>
        </div>
        
        
        <div class="clearfix"></div>
        <br/>
            
      
        <div class="col-xs-12 col-md-12 box">
            <h4>{{ trans('dataUpload.userComments')}} 
            <span class="pull-right">{!! ViewHelper::setCheckbox('deletedComments', $data, old('deletedComments'), trans('dataUpload.deletedComments'), trans('dataUpload.deletedComments'), false) !!}
             </span>
            </h4>
            
            <div class="tree-view" data-selector="test2">
                <div class="test2 hide">{{$data2}}</div>
            </div>
        </div>
        <div class="clearfix"></div>
        <br/>
        <div class="col-xs-12 col-md-6 box">
            <a href="#" class="btn btn-primary">
                <span class="fa fa-comment-o"></span>  
                {{ trans('dataUpload.comment') }}</a>
            <a href="#" class="btn btn-primary">
                <!--<span class="fa fa-share-alt"></span> -->
            {{ trans('dataUpload.release') }}</a>
            <a href="#" class="btn btn-danger">
                <!--<span class="fa fa-share-alt-square"></span> -->
            {{ trans('dataUpload.noRelease') }}</a>
        </div>
    @stop
    