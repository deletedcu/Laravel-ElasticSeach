{{-- BENUTZER EDIT --}}

@extends('master')

@section('page-title')
Benutzer anlegen
@stop

@section('content')

<fieldset class="form-group">
    
    {!! Form::open(['route' => ['benutzer.create-partner-store'], 'method'=>'POST', 'enctype' => 'multipart/form-data']) !!}
    <div class="box-wrapper">
        <h4 class="title">{{ trans('benutzerForm.baseInfo') }}</h4>
        <div class="box">
            <div class="row">
        
                {{--
                <div class="col-md-4 col-lg-3"> 
                    <div class="form-group">
                       {!! ViewHelper::setInput('username', '', old('username'), trans('benutzerForm.username'), trans('benutzerForm.username'), true) !!}
                    </div>   
                </div>
                --}}
                
                <div class="col-md-4 col-lg-3"> 
                    <div class="form-group">
                       {!! ViewHelper::setInput('password', '', '', trans('benutzerForm.password'), trans('benutzerForm.password'), true, 'password') !!}
                    </div>   
                </div>
                
                <div class="col-md-4 col-lg-3"> 
                    <div class="form-group">
                       {!! ViewHelper::setInput('password_repeat', '', '', trans('benutzerForm.password_repeat'), trans('benutzerForm.password_repeat'), true, 'password') !!}
                    </div>   
                </div>
                
                <div class="col-md-4 col-lg-3"> 
                    <div class="form-group">
                        <div class="checkbox">
                           {!! ViewHelper::setCheckbox('active', '', old('active'), trans('benutzerForm.active') ) !!}
                        </div>
                    </div>   
                </div>
                
                <div class="clearfix visible-lg-block"></div>
                
                <div class="col-md-4 col-lg-3"> 
                    <div class="form-group">
                        <label class="control-label">{{trans('benutzerForm.title')}}</label>
                        <select name="title" class="form-control select" placeholder="{{trans('benutzerForm.title')}}">
                            <option value="Frau" @if(old('title') == "Frau") selected @endif >Frau</option>
                            <option value="Herr" @if(old('title') == "Herr") selected @endif >Herr</option>
                        </select>
                    </div>   
                </div><!--End input box-->
        
                <!-- input box-->
                <div class="col-md-4 col-lg-3"> 
                    <div class="form-group">
                       {!! ViewHelper::setInput('first_name', '', old('first_name'), trans('benutzerForm.first_name'), trans('benutzerForm.first_name'), true) !!}
                    </div>   
                </div><!--End input box-->
        
                <!-- input box-->
                <div class="col-md-4 col-lg-3"> 
                    <div class="form-group">
                       {!! ViewHelper::setInput('last_name', '', old('last_name'), trans('benutzerForm.last_name'), trans('benutzerForm.last_name'), true) !!}
                    </div>   
                </div><!--End input box-->
        
                <!-- input box-->
                <div class="col-md-4 col-lg-3"> 
                    <div class="form-group">
                       {!! ViewHelper::setInput('birthday', '', old('birthday'), trans('benutzerForm.birthday'), trans('benutzerForm.birthday'), false, 'text', ['datetimepicker']) !!}
                    </div>   
                </div><!--End input box-->
                 
                <div class="col-md-4 col-lg-3"> 
                    <div class="form-group">
                        {!! ViewHelper::setInput('position', '', old('position'), trans('benutzerForm.position'), trans('benutzerForm.position'), true) !!}
                    </div>   
                </div>
                
                <div class="col-md-4 col-lg-3"> 
                    <div class="form-group">
                        {!! ViewHelper::setInput('abteilung', '', old('abteilung'), trans('benutzerForm.abteilung'), trans('benutzerForm.abteilung'), false) !!}
                    </div>   
                </div>
                
                <div class="col-md-4 col-lg-3"> 
                    <div class="form-group">
                        {!! ViewHelper::setInput('informationen', '', old('informationen'), trans('benutzerForm.informationen'), trans('benutzerForm.informationen'), false) !!}
                    </div>   
                </div>
                
                <!-- input box-->
                <div class="col-md-4 col-lg-3"> 
                    <div class="form-group">
                       {!! ViewHelper::setInput('short_name', '', old('short_name'), trans('benutzerForm.short_name'), trans('benutzerForm.short_name'), false) !!}
                    </div>   
                </div><!--End input box-->
                
                <!-- input box-->
                <div class="col-md-4 col-lg-3"> 
                    <div class="form-group">
                       {!! ViewHelper::setInput('username_sso', '', old('username_sso'), trans('benutzerForm.username_sso'), trans('benutzerForm.username_sso'), false) !!}
                    </div>   
                </div><!--End input box-->
                
                <div class="col-md-4 col-lg-3"> 
                    <div class="form-group">
                        <!-- Telefon -->
                        {!! ViewHelper::setInput('phone', '', old('phone'), trans('benutzerForm.phone'), trans('benutzerForm.phone'), false) !!}
                    </div>
                </div>
                
                <div class="col-md-4 col-lg-3"> 
                    <div class="form-group">
                        <!-- Telefon Mobil-->
                        {!! ViewHelper::setInput('phone_mobile', '', old('phone_mobile'), trans('benutzerForm.phone_mobile'), trans('benutzerForm.phone_mobile'), false) !!}
                    </div>
                </div>
                
                <div class="col-md-4 col-lg-3"> 
                    <div class="form-group">
                        <!-- Kurzwahl -->
                        {!! ViewHelper::setInput('phone_short', '', old('phone_short'), trans('benutzerForm.phone_short'), trans('benutzerForm.phone_short'), false) !!}
                    </div>
                </div>
                
                <!-- input box-->
                <div class="col-md-4 col-lg-3"> 
                    <div class="form-group">
                       {!! ViewHelper::setInput('email', '', old('email'), trans('benutzerForm.email'), trans('benutzerForm.email'), true, 'email') !!}
                    </div>   
                </div><!--End input box-->
                
                <div class="col-md-4 col-lg-3"> 
                    <div class="form-group">
                       {!! ViewHelper::setInput('email_private', '', old('email_private'), trans('benutzerForm.email_private'), trans('benutzerForm.email_private'), false, 'email') !!}
                    </div>   
                </div>
                
                <div class="col-md-4 col-lg-3"> 
                    <div class="form-group">
                       {!! ViewHelper::setInput('email_work', '', old('email_work'), trans('benutzerForm.email_work'), trans('benutzerForm.email_work'), false, 'email') !!}
                    </div>   
                </div>
                
                <div class="col-md-4 col-lg-3"> 
                    <div class="form-group">
                        <div class="checkbox">
                            {!! ViewHelper::setCheckbox('email_reciever','',old('email_reciever'),trans('benutzerForm.email_reciever') ) !!}
                        </div>
                    </div>   
                </div>
                
                <div class="clearfix visible-lg-block"></div>

                <div class="col-md-4 col-lg-3"> 
                    <div class="form-group">
                        {!! ViewHelper::setInput('active_from', '', old('active_from'), trans('benutzerForm.active_from'), trans('benutzerForm.active_from'), false, 'text', ['datetimepicker']) !!}
                    </div>   
                </div>
                
                <div class="col-md-4 col-lg-3"> 
                    <div class="form-group">
                        {!! ViewHelper::setInput('active_to', '', old('active_to'), trans('benutzerForm.active_to'), trans('benutzerForm.active_to'), false, 'text', ['datetimepicker']) !!}
                    </div>   
                </div>
                
                <div class="clearfix"></div>
                
                <div class="col-md-4 col-lg-3">
                    <label for="mandant_id">{{ trans('benutzerForm.mandant') }}*</label>
                    <select name="mandant_id" class="form-control select" data-placeholder="{{ strtoupper(trans('benutzerForm.mandant')) }}*" required>
                        <option></option>
                        @foreach($mandantsAll as $mandant)
                            <option value="{{$mandant->id}}">{{ $mandant->mandant_number }} - {{ $mandant->kurzname }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="col-md-4 col-lg-3">
                    <label for="mandant_id">{{ trans('benutzerForm.roles') }}*</label>
                    
                    <select name="role_id[]" class="form-control select" data-placeholder="{{ strtoupper(trans('benutzerForm.roles')) }}*" multiple>
                        @foreach($defaultRoles as $def)
                            <option selected disabled> {{ $def->name }} <option>
                        @endforeach
                        @foreach($rolesAll as $role)
                            <option value="{{$role->id}}"> 
                                {{$role->name}}
                            </option>
                        @endforeach
                    </select>
                    @foreach($defaultRoles as $def)
                        <input type="hidden" name="role_id[]" value="{{$def->id}}">
                    @endforeach
                </div>
                
                <div class="clearfix"></div> <br>
                
            </div>
            
            <div class="row">
                <!-- input box-->
                <div class="col-md-2 col-lg-2"> 
                    <div class="form-group">
                        <label>{{ trans('benutzerForm.picture') }}</label>
                        <input type="file" id="image-upload" name="picture" /><br/>
                        <img id="image-preview" class="img-responsive" src="{{url('/img/user-default.png')}}"/>
                    </div>   
                </div><!--End input box-->
        
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

<div id="mandants-roles" class="clearfix"></div> <br>

@stop