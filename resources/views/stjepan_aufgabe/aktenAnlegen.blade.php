@extends('master')

@section('page-title')
  Dokumente bearbeiten / anlegen - Grundinfos
@stop


@section('content')
<div class="col-md-12 box-wrapper">
    <div class="box">
        <div class="row">
            <!-- input box-->
            <div class="col-md-4 col-lg-4 "> 
                <div class="form-group">
                    {!! ViewHelper::setInput('name','',old('name'),trans('Name') , 
                           trans('Name') , true  ) !!}
                </div>   
            </div><!--End input box-->
            
            
            <!-- input box-->
            <div class="col-md-4 col-lg-4"> 
                <div class="form-group">
                    <label class="control-label"> {{ trans('Mandant') }} *</label>
                    <select name="user_id" class="form-control select" data-placeholder="{{ strtoupper( trans('documentForm.user') ) }}" required>
                        @foreach( $users as $documentUser )
                            <option value="{{$documentUser->id}}" @if( isset($data->user_id) && $documentUser->id == $data->user_id) selected @endif >
                                {{ $documentUser->last_name }} {{ $documentUser->first_name }}  
                            </option>
                        @endforeach
                    </select>
                </div>
            </div><!--End input box-->
            
            <!-- input box-->
            <div class="col-md-4 col-lg-4"> 
                <div class="form-group">
                    <label class="control-label"> {{ trans('Akten Art') }} *</label>
                    <select name="user_id" class="form-control select" data-placeholder="{{ strtoupper( trans('beratungsDokument.dokumentArt') ) }}" required>
                        @foreach( $documentArts as $documentArt )
                            <option value="{{$documentUser->id}}" @if( isset($data->user_id) && $documentArt->id == $data->user_id) selected @endif >
                                {{ $documentArt->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div><!--End input box-->
            
            
            <div class="col-md-4 col-lg-4">
                <div class="form-group">
                    <select name="users[]" class="form-control select" required multiple data-placeholder="Benutzer">
                    <option value='Alle'
                        @if( count($users) == count($documentArts[0]->juristFileTypeUsers) ) selected @endif >
                        Alle </option>
                        @foreach($users as $user){
                           <option
                        @if( count($users) != count($documentArts[0]->juristFileTypeUsers) )
                           {!! ViewHelper::setMultipleSelect($documentArts[0]->juristFileTypeUsers, $user->id,'user_id') !!}
                        @endif
                           value="{{$user->id}}" multiple>
                            {{ $user->first_name }} {{ $user->last_name }}
                           </option>
                        @endforeach
                    </select> 
                </div>
            </div>
            
            <!-- input box-->
            <div class="col-md-12 col-lg-12">
                <div class="form-group">
                    {!! ViewHelper::setArea('summary','',old('summary'),trans('Kurzinfo') ) !!}
                </div>
            </div><!--End input box-->
            
            
            <div class="clearfix"></div><br/>
                        
            <div class="col-xs-12">
                <button type="submit" class="btn btn-primary">{{ trans('Akte anlegen') }}</button>
            </div> 
        </div>
    </div>
</div>

@stop