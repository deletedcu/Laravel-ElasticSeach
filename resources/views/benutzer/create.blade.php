{{-- BENUTZER CREATE --}}

@extends('master')

@section('page-title')
Mandantenverwaltung - Benutzer anlegen
@stop

@section('content')

<fieldset class="form-group">
    <div class="box-wrapper">
        {!! Form::open(['route' => 'benutzer.store', 'enctype' => 'multipart/form-data']) !!}
        
        <h4 class="title">{{ trans('benutzerForm.baseInfo') }}</h4>
        <div class="box box-white">
            <div class="row">
        
                <div class="col-md-4 col-lg-3"> 
                    <div class="form-group">
                       {!! ViewHelper::setInput('username', '', old('username'), trans('benutzerForm.username'), trans('benutzerForm.username'), true) !!}
                    </div>   
                </div>
        
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
                    {{-- NEPTUN-751 --}}
                    
                    <div class="form-group">
                    {{-- !! ViewHelper::setCheckbox('active', '', old('active'), trans('benutzerForm.active'), false) !! --}}
                    {{--
                        <div class="checkbox">
                            <input type="checkbox" value="1" name="active" id="active" checked><label for="active">{{ trans('benutzerForm.active') }}</label>
                        </div>
                    --}}
                    </div>
                    
                </div>
                
                <div class="clearfix visible-lg-block"></div>
            
                <div class="col-md-4 col-lg-3"> 
                    <div class="form-group">
                        <label class="control-label">{{trans('benutzerForm.title')}}</label>
                        <select name="title" class="form-control select">
                            <option @if(old('title')) selected @endif value="Frau">Frau</option>
                            <option @if(old('title')) selected @endif value="Herr">Herr</option>
                        </select>
                        
                    </div>   
                </div>
        
                <div class="col-md-4 col-lg-3"> 
                    <div class="form-group">
                       {!! ViewHelper::setInput('first_name', '', old('first_name'), trans('benutzerForm.first_name'), trans('benutzerForm.first_name'), false) !!}
                    </div>   
                </div>
        
                <div class="col-md-4 col-lg-3"> 
                    <div class="form-group">
                       {!! ViewHelper::setInput('last_name', '', old('last_name'), trans('benutzerForm.last_name'), trans('benutzerForm.last_name'), false) !!}
                    </div>   
                </div>
        
                <div class="col-md-4 col-lg-3"> 
                    <div class="form-group">
                       {!! ViewHelper::setInput('birthday', '', old('birthday'), trans('benutzerForm.birthday'), trans('benutzerForm.birthday'), false, 'text', ['datetimepicker']) !!}
                    </div>   
                </div>
                                
                <div class="col-md-4 col-lg-3"> 
                    <div class="form-group">
                        {!! ViewHelper::setInput('position', '', old('position'), trans('benutzerForm.position'), trans('benutzerForm.position'), false) !!}
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
                
                <div class="col-md-4 col-lg-3"> 
                    <div class="form-group">
                       {!! ViewHelper::setInput('short_name', '', old('short_name'), trans('benutzerForm.short_name'), trans('benutzerForm.short_name'), false) !!}
                    </div>   
                </div>
                
                <div class="col-md-4 col-lg-3"> 
                    <div class="form-group">
                       {!! ViewHelper::setInput('username_sso', '', old('username_sso'), trans('benutzerForm.username_sso'), trans('benutzerForm.username_sso'), false) !!}
                    </div>   
                </div>
                
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
                
                <div class="col-md-4 col-lg-3"> 
                    <div class="form-group">
                       {!! ViewHelper::setInput('email', '', old('email'), trans('benutzerForm.email'), trans('benutzerForm.email'), false, 'email') !!}
                    </div>   
                </div>
                
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
                       <!--{!! ViewHelper::setCheckbox('email_reciever', '', old('email_reciever'), trans('benutzerForm.email_reciever')) !!}-->
                        <div class="checkbox">
                            <input type="checkbox" value="1" name="email_reciever" id="email_reciever"  checked><label for="email_reciever">{{ trans('benutzerForm.email_reciever') }}</label>
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
                    <div class="form-group">
                        <label>{{ trans('benutzerForm.picture') }}</label>
                        <input type="file" id="image-upload" name="picture"/><br/>
                        <img id="image-preview" class="img-responsive" src="{{url('/img/user-default.png')}}"/>
                    </div>   
                </div>
        
             </div><!--end class row-->
            
            <div class="clearfix"></div> <br>
            
            <div class="row">
                <div class="col-md-6 col-lg-6">
                    <button class="btn btn-white no-margin-bottom" type="reset">{{ trans('benutzerForm.reset') }}</button>
                    <button class="btn btn-primary no-margin-bottom" type="submit">{{ trans('benutzerForm.save') }}</button>
                </div>
            </div>
        </div>
        {!! Form::close() !!}
    </div> <!-- end box-wrapper -->
</fieldset>


<div class="clearfix"></div> <br>

@stop
@section('script')
<script type="text/javascript">
    $(document).ready(function(){
       $('input[type=password]').val('') 
    });
</script>
@stop