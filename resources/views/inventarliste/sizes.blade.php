{{-- Invetarliste index --}}

@extends('master')

@section('page-title') {{ trans('navigation.inventoryList') }} {{ trans('navigation.inventarSizes') }} @stop

@section('content')
<!--add row-->
<div class="row">
    <div class="col-sm-12 ">
        <div class="box-wrapper">
            <h2 class="title"> @lang('inventoryList.addSize')</h2>
            <div class="box  box-white">
                <div class="row">
                    {!! Form::open(['action' => 'InventoryController@postSizes', 'method'=>'POST']) !!}
                        <div class="input-group">
                            <div class="col-md-12 col-lg-12">
                                {!! ViewHelper::setInput('name', '',old('name'), 
                                trans('inventoryList.name'), trans('inventoryList.name'), true) !!}
                            </div>
                            <div class="col-md-12 col-lg-12">
                                <span class="custom-input-group-btn">
                                    <button type="submit" class="btn btn-primary no-margin-bottom">
                                        {{ trans('inventoryList.add') }} 
                                    </button>
                                </span>
                            </div>
                        </div>
                    {!! Form::close() !!}
                </div>
            </div><!-- end box -->
        </div><!-- end box wrapper-->
    </div>
</div><!-- end add row -->

<fieldset class="form-group">
    <div class="box-wrapper">    
        <div class="row">
            <div class="col-xs-12">
                <h4 class="title">{{ trans('adressatenForm.overview') }}</h4>
                 <div class="box box-white">
                    @foreach($sizes as $size)
                    <div class="row">
                        {!! Form::open(['url' => ['inventarliste/sizes/'.$size->id.'/update'], 'method' => 'POST']) !!}
                        <div class="col-xs-12 col-md-6 col-lg-5">
                             <input type="text" class="form-control" name="name" value="{{ $size->name }}" 
                             placeholder="@lang('inventoryList.name')"/>
                        </div>
                        <div class="col-xs-12 col-md-6 col-lg-5">
                            
                            @if($size->active )
                                <button class="btn btn-success" type="submit" name="active" value="0">{{ trans('adressatenForm.active') }}</button>
                            @else
                                <button class="btn btn-danger" type="submit" name="active" value="1">{{ trans('adressatenForm.inactive') }}</button>
                            @endif
                            
                            <button class="btn btn-primary" type="submit" name="save" value="1">{{ trans('adressatenForm.save') }}</button>
                            @if( !count($size->items ) )
                                <a href="{{url('inventarliste/destroy-size/'.$size->id)}}" class="btn btn-xs btn-warning delete-prompt">
                                    entfernen
                                </a><br>
                            @endif
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


@stop
