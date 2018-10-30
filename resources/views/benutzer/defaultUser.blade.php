{{-- STANDARD BENUTZER --}}

@extends('master')

@section('page-title')
{{ trans('benutzerForm.defaultUser') }}
@stop

@section('content')

<fieldset class="form-group">
    
    {!! Form::open(['action' => 'UserController@defaultUserSave']) !!}
    <div class="box-wrapper">
        <h4 class="title">{{ trans('benutzerForm.defaultUserRoles') }}</h4>
        
        <div class="box">
            <div class="row">
                <div class="col-md-6"> 
                    <div class="form-group">
                        
                        <select name="role_id[]" id="role_id" class="form-control select" data-placeholder="{{ trans('benutzerForm.roles') }} *" multiple required>
                            <option></option>
                            @foreach($rolesAll as $role)
                                <option value="{{$role->id}}" @if(in_array($role->id, $defaultRoles)) selected @endif>
                                    {{$role->name}}
                                </option>
                            @endforeach
                        </select>
                    
                    </div>   
                </div>
            </div>
            
            <div class="clearfix"></div> <br>
            
            <div class="row">
                <div class="col-md-6 col-lg-6">
                    <button class="btn btn-primary no-margin-bottom" type="submit">{{ trans('benutzerForm.save') }}</button>
                </div>
            </div>
        </div><!--end box-->
    </div>
    {!! Form::close() !!}
    
    <div class="clearfix"></div> <br>
    
</fieldset> 

@stop