{{-- Metainfo index --}}

@extends('master')

@section('page-title') @lang('juristenPortal.metaFieldsRechtsablageTitle') @stop

@section('content')
<!-- add row-->
<div class="row">
    <div class="col-sm-12 ">
        <div class="box-wrapper">
            <h2 class="title"> @lang('juristenPortal.metaFieldsAddTitle')</h2>
            <div class="box  box-white">
                <div class="row">
                    {!! Form::open(['action' => 'JuristenPortalController@storeMetaInfoRechtsablage', 'method'=>'POST']) !!}
                      
                            <div class="col-md-6 col-lg-6">
                                {!! ViewHelper::setInput('name', '',old('name'), 
                                trans('inventoryList.name'), trans('inventoryList.name'), true) !!}
                            </div>
                        <div class="clearfix"></div><br/>
                          <div class="col-xs-12 parent-div">
                                <a href="#" class="btn btn-primary add-single-field pull-left">{{ trans('juristenPortal.addField') }} </a> 
                                <div class="clearfix"></div>
                                <div class="col-xs-6 add-wrapper">
                                    
                                </div> 
                                <div class="clearfix"></div>
                               
                        </div><!-- end .row -->  
                         <div class="submit-div">
                            <br/>
                            <div class="col-xs-6">
                                 <button class="btn btn-primary" type="submit" name="save" value="1">{{ trans('adressatenForm.save') }}</button>
                            </div> 
                        </div>  
                    {!! Form::close() !!}
                </div>
            </div><!-- end box -->
        </div><!-- end box wrapper-->
    </div>
</div><!-- end dd-->

    <fieldset class="form-group">
    
    <!--<h4 class="title">{{ trans('adressatenForm.adressats') }} {{ trans('adressatenForm.overview') }}</h4> <br>-->
     <div class="box-wrapper">    
        <div class="row">
            <div class="col-xs-12">
                <h4 class="title">@lang('juristenPortal.metaFieldsAddTitle')</h4>
                 <div class="box box-white">
                    @foreach($categories as $category)
                    <div class="row">
                        {!! Form::open(['url' => ['rechtsablage/meta-info/'.$category->id.'/update'], 'method' => 'patch']) !!}
                        <div class="col-xs-12 col-md-6 col-lg-5">
                             <input type="text" class="form-control" name="name" value="{{ $category->name }}" placeholder="Name"/>
                        </div>
                        <div class="col-xs-12 col-md-6 col-lg-5">
                            @if($category->active)
                                <button class="btn btn-success" type="submit" name="active" value="0">{{ trans('adressatenForm.active') }}</button>
                            @else
                                <button class="btn btn-danger" type="submit" name="active" value="1">{{ trans('adressatenForm.inactive') }}</button>
                            @endif
                            <button class="btn btn-primary" type="submit" name="save" value="1">{{ trans('adressatenForm.save') }}</button>
                            @if( !count($category->metaInfos )  && !count($category->documents) )
                                <a href="{{url('rechtsablage/destroy-juristen-category-meta/'.$category->id)}}" class="btn btn-xs btn-warning delete-prompt">
                                    entfernen
                                </a><br>
                            @endif
                        </div>
                        {!! Form::close() !!}
                        <div class="clearfix"></div>
                        <br/>
                        
                        @if( count($category->metaInfos) )
                            @foreach( $category->metaInfos as $metaInfo )
                            {!! Form::open(['url' => ['rechtsablage/meta-info/'.$metaInfo->id.'/update-meta-filed'] , 'method' => 'patch']) !!}
                                <!--<input type="hidden" name="jurist_category_meta_id" value="{{$category->id}}" />-->
                                <div class="col-md-10 meta-info-margin">
                                    <div class="col-xs-12 col-md-6 col-lg-5">
                                         <input type="text" class="form-control" name="name" value="{{ $metaInfo->name }}" placeholder="Name"/>
                                    </div>
                                    <div class="col-xs-12 col-md-6 col-lg-5">
                                        @if($metaInfo->active)
                                            <button class="btn btn-success" type="submit" name="active" value="0">{{ trans('adressatenForm.active') }}</button>
                                        @else
                                            <button class="btn btn-danger" type="submit" name="active" value="1">{{ trans('adressatenForm.inactive') }}</button>
                                        @endif
                                        <button class="btn btn-primary" type="submit" name="save" value="1">{{ trans('adressatenForm.save') }}</button>
                                        <a href="{{url('rechtsablage/destroy-juristen-category-meta-field/'.$metaInfo->id)}}" class="btn btn-xs btn-warning delete-prompt">
                                            entfernen
                                        </a> <!-- delete meta field value --> <br> 
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                            {!! Form::close() !!}     
                            @endforeach
                            
                        @endif
                        
                        <div class="col-xs-12 parent-div">
                            {!! Form::open(['url' => ['rechtsablage/add-juristen-category-meta-fiels/'.$category->id] , 'method' => 'POST']) !!}
                                <a href="#" class="btn btn-primary add-single-field pull-left">{{ trans('juristenPortal.addField') }} </a> 
                                <div class="clearfix"></div>
                                <div class="col-xs-6 add-wrapper">
                                    
                                </div> 
                                <div class="clearfix"></div>
                                <div class="submit-div hidden">
                                    <br/>
                                     <button class="btn btn-primary" type="submit" name="save" value="1">{{ trans('adressatenForm.save') }}</button>
                                </div>  
                            {!! Form::close() !!}
                        </div><!-- end .row -->    
                            
                    </div><!--end .row (category row) -->
                    <hr/><br>
                    @endforeach
                    
                    
                   
                    
                </div>
            </div>
        </div>
    </div>
</fieldset>


@stop
