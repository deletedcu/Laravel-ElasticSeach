@extends('master')

@section('page-title-class') {{ ucfirst( trans('navigation.tipsAndTricks') ) }} @stop

@section('page-title') {{ ucfirst( trans('navigation.tipsAndTricks') ) }} @stop

@section('bodyClass') contactPage @stop

@section('content')
<div class="box-wrapper ">
    <div class="box box-white">
                <div class="row">
                    <div class="col-sm-8 col-md-9 col-lg-10">
                        <div class="row">
                            <div class="col-xs-12">
    
                                <div class="content">

                                    @if($data)
                                        <div>
                                            {!! ViewHelper::stripTags($data->content, array('div' ) ) !!}
                                        </div>
                                    @else
                                        <div>keine Tipps und Tricks</div>
                                    @endif
    
                                </div><!-- end .content -->
                            </div><!--end col-xs-12-->
                        </div><!--end row-->
                    </div><!-- end .col-sm-8 .col-md-9 .col-lg-10 -->
                </div>
            </div>
</div>
@stop
   