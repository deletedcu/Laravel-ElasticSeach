@extends('master')
    @section('page-title'){{ trans('juristenPortal.createNotes') }} @stop
    @section('content')
        <div class="col-xs-12 box-wrapper">
            <h3 class="title">Notiz anlegen</h3>
            <div class="col-xs-12">
                <br/>
                <div class="clearfix"></div>
                    <button type="submit" class="btn btn-primary">{{ trans('juristenPortal.print') }}</button>
                    <button type="submit" class="btn btn-primary">{{ trans('juristenPortal.juristTemplates') }}</button>
                    <button type="submit" class="btn btn-primary">{{ trans('juristenPortal.toAkten') }}</button>
                    <button type="submit" class="btn btn-primary">{{ trans('juristenPortal.createAkten') }}</button>
                    <button type="submit" class="btn btn-primary">{{ trans('juristenPortal.deactivateAkten') }}</button>
            </div>
            
            <div class="box">
               {!! Form::open([
                   'url' => url('notice/upload'),
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
                            <a href="{{url('notice/'.$note->id.'/edit')}}" class="btn btn-info">{{ trans('juristenPortal.back') }}</a>
                            <button type="submit" class="btn btn-primary">{{ strtolower(trans('juristenPortal.upload') ) }}</button>
                            <button type="submit" class="btn btn-primary">{{ trans('juristenPortal.save') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
    @endsection


