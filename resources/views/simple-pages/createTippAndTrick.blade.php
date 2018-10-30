@extends('master')

@section('page-title-class') {{ ucfirst( trans('navigation.tipsAndTricks') ) }} @stop

@section('page-title') {{ ucfirst( trans('navigation.tipsAndTricks') ) }} @stop

@section('bodyClass') contactPage @stop

@section('content')
<div class="row">
    
    <div class="col-xs-12 col-md-12 ">
        <div class="col-xs-12 box-wrapper">
            
            <div class="box">
                <div class="row">
                    {!! Form::model( $data, [
                        'route'=> $data->exists ? ['tipps-und-tricks.update', $data->id] : 'tipps-und-tricks.store',
                        'method'=> $data->exists ? 'PUT' : 'POST' ]) !!}
                    {!! Form::hidden('id') !!}    
                        <!-- editor box-->
                        <div class="clearfix"></div>
                        <div class="col-xs-12">
                            <div class="variant" data-id='content'>
                                @if( isset($data->content) )
                                    {!! $data->content !!}
                                @endif
                            </div>
                        </div>
                        <div class="clearfix"></div>
                </div>
               
            </div>  
            
             <!-- speichern button -->
             <div class="clearfix"></div><br>
                <div class="row">
                    <div class="col-xs-12 form-buttons">
                        {{ Form::submit('speichern', array('class' => 'btn btn-primary no-margin-bottom')) }}
                    </div>
                </div>
                <!-- end speichern button -->
            
        </div>
    </div>
    
</div>

@stop