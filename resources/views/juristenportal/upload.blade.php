@extends('master')
    @section('page-title'){{ trans('juristenPortal.upload') }} @stop
    @section('content')
        <div class="col-xs-12 box-wrapper">
            <h3 class="title">{{ trans('juristenPortal.upload') }}</h3>
            <div class="box">
               {!! Form::open([
                   'url' => route('juristenportal.upload'),
                   'method' => 'POST',
                   'enctype' => 'multipart/form-data',
                   'class' => 'horizontal-form'
                   ]) 
               !!}
                    <div class="row">
                        <!-- input box-->
                        <div class="col-lg-6"> 
                            <div class="form-group">
                                
                                <input type="file" name="file[]" class="form-control" multiple required />
                            </div>   
                        </div><!--End input box-->
                        
                        <div class="col-xs-12">
                            <button type="submit" class="btn btn-primary">{{ strtolower(trans('juristenPortal.upload') ) }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="row">
            
            @if( count($documentsMy) )
                <div class="col-xs-12 col-md-6 ">
                    <div class="col-xs-12 box-wrapper box-white home">
                        <h1 class="title">@lang('juristenPortal.documentsWithoutCategoriesMy')</h1>
                        <div class="box home">
                            <div class="tree-view" data-selector="documentsMy">
                                <div class="documentsMy hide">
                                    {{ $documentsMyTree }}
                                </div>
                            </div>
                        </div>
                          <div class="text-center">
                            {!! $documentsMy->render() !!}
                        </div>
                    </div>
                </div><!-- end box -->
            @endif
            
            @if( count($documentsAll) )
                <div class="col-xs-12 col-md-6 ">
                    <div class="col-xs-12 box-wrapper box-white home">
                        <h1 class="title">@lang('juristenPortal.documentsWithoutCategories')</h1>
                        <div class="box home">
                            <div class="tree-view" data-selector="documentsMy">
                                <div class="documentsMy hide">
                                    {{ $documentsAllTree }}
                                </div>
                            </div>
                        </div>
                          <div class="text-center">
                            {!! $documentsAll->render() !!}
                        </div>
                    </div>
                </div><!-- end box -->
            @endif
        </div><!--end .row -->
    @endsection


