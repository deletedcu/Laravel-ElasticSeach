{{-- ROLLENVERWALTUNG --}}

@extends('master')
@section('page-title') {{ trans('rollenForm.role-management') }} @stop
@section('content')


<fieldset class="form-group">
    <div class="box-wrapper">
        <h4 class="title">{{ trans('rollenForm.roles') }} {{ trans('rollenForm.add') }}</h4>
        <div class="box box-white">
            <div class="row">
                <!-- input box-->
                    {!! Form::open(['route' => 'rollen.store']) !!}
                        <div class="col-xs-12"><div class="add-border-bottom"><strong>Rolle anlegen</strong></div></div>
                        <div class="col-md-5 col-lg-4">
                            <div class="form-group">
                                {!! ViewHelper::setInput('name', '', old('name'), trans('rollenForm.name'), trans('rollenForm.name'), true) !!} 
                            </div>
                        </div>
                        <div class="col-md-5 col-lg-4"> 
                            <div class="form-group">
                                <label>{{ trans('rollenForm.rights') }}</label>
                                <select name="role[]" class="form-control select" data-placeholder="{{ trans('rollenForm.rights') }}" multiple>
                                    <option value="0"></option>
                                    <option value="required">Pflichtfeld</option>
                                    <option value="admin">NEPTUN</option>
                                    <option value="mandant">Partner</option>
                                    <option value="wiki">Wiki</option>
                                    <option value="phone">Telefonliste</option>
                                </select>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        
                        <div class="col-xs-12"><div class="add-border-bottom"><strong>{{ trans('rollenForm.rights') }} {{ trans('rollenForm.copy') }}</strong><br></div></div>
                        <div class="col-md-5 col-lg-4">
                            <div class="form-group">
                                <label>{{ trans('rollenForm.documents') }}</label>
                                <select name="role_copy" class="form-control select" data-placeholder="{{ trans('rollenForm.documents') }}">
                                    <option value=""></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-5 col-lg-4"> 
                            <div class="form-group">
                                <label>{{ trans('rollenForm.wiki') }}</label>
                                <select name="wiki_copy" class="form-control select" data-placeholder="{{ trans('rollenForm.wiki') }}">
                                    <option value=""></option>
                                </select>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        
                        <div class="col-md-3 col-lg-3">     
                            <br><button class="btn btn-primary">{{ trans('rollenForm.add') }} </button>
                        </div>
                    {!! Form::close() !!}
            </div><!--End input box-->
        </div>  
    </div>
    
</fieldset>

<fieldset class="form-group">
    <div class="box-wrapper">
        <h4 class="title">{{ trans('rollenForm.user-defined') }} {{ trans('rollenForm.roles') }}</h4>
        <!--<h4 class="title">{{ trans('rollenForm.overview') }}</h4>-->
         <div class="box box-white">
            <div class="row">
                <div class="col-xs-12">
                    <table class="table">
                             @foreach($roles as $role)
                                @if(!$role->system_role)
                               
                                {!! Form::open(['route' => ['rollen.update', $role->id], 'method'=>'PATCH']) !!}
                                    <tr class="row">
                                        <td class="col-xs-4">
                                            <div class="form-group">
                                                <label class="control-label">{{ trans('rollenForm.name') }}*</label>
                                                <input class="form-control" type="text" name="name" value="{{ $role->name }}" placeholder="{{ trans('rollenForm.name') }}*" required/>
                                            </div>
                                        </td>
                                        <td class="col-xs-4 position-relative">
                                            <label>{{ trans('rollenForm.rights') }}</label>
                                            <select name="role[]" class="form-control select" data-placeholder="{{ trans('rollenForm.rights') }}" multiple>
                                                <option value="0"></option>
                                                <option value="required" @if($role->mandant_required) selected @endif > Pflichtfeld</option>
                                                <option value="admin" @if($role->admin_role) selected @endif > NEPTUN</option>
                                                <option value="mandant" @if($role->mandant_role) selected @endif > Partner</option>
                                                <option value="wiki" @if($role->wiki_role) selected @endif > Wiki</option>
                                                <option value="phone" @if($role->phone_role) selected @endif > Telefonliste</option>
                                            </select>
                                        </td>
                                        <td class="col-xs-2 text-center table-options vertical-center">
                                            @if($role->active)
                                            <button class="btn btn-success" type="submit" name="activate" value="1">{{ trans('rollenForm.active') }}</button>
                                            @else
                                            <button class="btn btn-danger" type="submit" name="activate" value="0">{{ trans('rollenForm.inactive') }}</button>
                                            @endif
                                            <button class="btn btn-primary">{{ trans('rollenForm.save') }}</button>
                                        </td>
                                        <td class="col-xs-2 text-center table-options vertical-center">
                                            <a href="#;" data-toggle="modal" data-target="#role-{{$role->id}}" class="link">{{ trans('rollenForm.active-users') }}: 
                                            {{ count( $role->mandantUserRolesAll->where('role_id',$role->id)->where('deleted_at',null) ) }}
                                            </a>
                                        </td>
                                    </tr>
                                {!! Form::close() !!}
                                
                                @endif
                            @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
</fieldset>

<fieldset class="form-group">
    <div class="box-wrapper">
        <h4 class="title">{{ trans('rollenForm.system') }} {{ trans('rollenForm.roles') }}</h4>
        {{-- <h4 class="title">{{ trans('rollenForm.overview') }}</h4> --}}
         <div class="box box-white">
            <div class="row">
                <div class="col-xs-12">
                    <table class="table">
                        {{--
                        <tr>
                            <th class="col-xs-12 col-md-5">
                               {{ trans('rollenForm.system') }} {{ trans('rollenForm.roles') }}
                            </th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                        --}}
                            @foreach($roles as $role)
                                @if($role->system_role)
                                {!! Form::open(['route' => ['rollen.update', $role->id], 'method'=>'PATCH']) !!}
                                   <tr>
                                        <td class="col-xs-12 col-md-5">
                                            <div class="form-group">
                                                <label class="control-label">{{ trans('rollenForm.name') }}*</label>
                                                <input class="form-control" type="text" name="name" value="{{ $role->name }}" placeholder="{{ trans('rollenForm.name') }}*" required/>
                                            </div>
                                        </td>
                                        {{--
                                        <td class="col-xs-12 col-md-2 vertical-center">
                                             <br> <p>{{ trans('rollenForm.editing') }}</p>
                                        </td> 
                                        --}}
                                         <td class="col-xs-12 col-md-1 vertical-center">
                                            <div class="checkbox pull-left no-margin-top">
                                                <input type="checkbox" name="mandant" id="mandant-{{ $role->id }}" @if($role->mandant_role) checked @endif>
                                                <label for="mandant-{{ $role->id }}">{{ trans('rollenForm.mandant') }}</label>
                                            </div>
                                            <div class="checkbox pull-left no-margin-top">
                                                <input type="checkbox" name="phone" id="phone-{{ $role->id }}" @if($role->phone_role) checked @endif>
                                                <label for="phone-{{ $role->id }}">{{ trans('rollenForm.phone') }}</label>
                                            </div>
                                            <div class="checkbox pull-left no-margin-top">
                                                <input type="checkbox" name="wiki" id="wiki-{{ $role->id }}" @if($role->wiki_role) checked @endif>
                                                <label for="wiki-{{ $role->id }}">{{ trans('rollenForm.wiki') }}</label>
                                            </div>
                                        </td>
                                        <td class=" text-right table-options vertical-center">
                                            <button class="btn btn-primary ">{{ trans('rollenForm.save') }}</button>
                                        </td>
                                         <td class=" text-center table-options vertical-center">
                                            <a href="#;" data-toggle="modal" data-target="#role-{{$role->id}}" class="link">{{ trans('rollenForm.active-users') }}: 
                                            {{ count( $role->mandantUserRolesAll->where('role_id',$role->id)->where('deleted_at',null) ) }}
                                            </a>
                                        </td>
                                   
                                    </tr>
                                 {!! Form::close() !!}
                                @endif
                            @endforeach
                    </table>
                </div>
            </div>
    
            
        </div>
    </div>
</fieldset>

<div class="clearfix"></div> <br>

@foreach($roles as $role)
   
    <div class="modal fade" id="role-{{$role->id}}" tabindex="-1" role="dialog" aria-labelledby="role-{{$role->id}}" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><span class="fa fa-close"></span></button>
                    <h4 class="modal-title" id="myModalLabel">{{ trans('rollenForm.active-users') }} - {{ trans('rollenForm.role') ." ". $role->name }}: 
                        {{ count( $role->mandantUserRolesAll->where('role_id',$role->id)->where('deleted_at',null) )  }}
                 </h4>
                </div>
                
                <div class="modal-body">
                    <div class="row general">
                        <div class="col-xs-12">
                          @if( count( $role->mandantUserRolesAll->where('role_id',$role->id)->where('deleted_at',null) )   )  
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Mandanten</th> 
                                            <th>Benutzer</th> 
                                        </tr>    
                                    </thead>
                                    <tbody>
                                    @foreach($role->mandantUserRolesAll->where('role_id',$role->id)->where('deleted_at',null) as $r)
                                     
                                        <tr>
                                            <td>
                                                @if(  isset($r->mandantUser->mandant->name)  )
                                                    {{ ( $r->mandantUser->mandant->name ) }}
                                                @endif 
                                            </td>
                                            <td>
                                                @if(  isset($r->mandantUser->user->first_name) &&  isset($r->mandantUser->user->last_name) )
                                                    {{ ( $r->mandantUser->user->first_name) }} {{ ( $r->mandantUser->user->last_name ) }}
                                                @endif 
                                            </td>
                                        </tr>
                                             
                                    @endforeach
                                    </tbody>
                                </table>
                          @endif
                        </div>
                    </div>
                    
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal" aria-hidden="true">
                        {{ trans('rollenForm.close') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
@endforeach

@stop
