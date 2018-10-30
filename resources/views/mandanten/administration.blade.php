@extends('master')

@section('page-title')
    {{  ucfirst( trans('controller.administration') ) }} 
@stop
    @section('bodyClass')
    mandant-administration 
    @stop
    @section('content')
    <div class="col-xs-12 box-wrapper">
        <h2 class="title">{{ trans('mandantenForm.search') }} </h2>
        <div class="box box-white">
            {!! Form::open([
                   'url' => 'mandanten/search',
                   'method' => 'POST',
                   'class' => 'horizontal-form' ]) !!}
                <div class="row">
                    <!-- input box-->
                    <div class="col-md-6 col-lg-6"> 
                        <div class="form-group no-margin-bottom">
                            {!! ViewHelper::setInput('search', $searchParameter, $searchParameter,'', 
                                   trans('mandantenForm.search')   ) !!}
                        </div>   
                    </div><!--End input box-->
                    <!-- input box-->
      
                    <div class="col-md-6 col-lg-6"> 
                        <div class="form-group label-form-group  no-margin-bottom">
                                {!! ViewHelper::setCheckbox('deleted_users', $deletedUsers, $deletedUsers, trans('mandantenForm.showDeletedUsers') ) !!}
                                {!! ViewHelper::setCheckbox('deleted_mandants', $deletedMandants, $deletedMandants, trans('mandantenForm.showDeletedClients') ) !!}
                        </div>   
                    </div><!--End input box-->
                    
                        <div class="clearfix"></div>
                    
                    <!-- button div-->    
                    <div class="col-xs-12">
                        <div class="form-group no-margin-bottom custom-input-group-btn">
                           <button type="submit" class="btn btn-primary no-margin-bottom">{{ trans('benutzerForm.search') }}</button>
                            <!--<button type="reset" class="btn btn-info">{{ trans('benutzerForm.reset') }}</button>-->
                        </div>
                    </div><!-- End button div-->    
                </div>           
            </form>
        </div>
    </div>

    <div class="clearfix"><br></div>
  
    @if( !empty($mandants)  ) 
        
        @if( !empty($search) && $search == true )
            <h2 class="title">{{trans('mandantenForm.search-result-mandants')}} ({{count($mandants)}})</h2>
        @else
            <h2 class="title">{{ trans('mandantenForm.overview')}}</h2>
        @endif
        
        <div class="panel-group">
            
            @foreach( $mandants as $mandant)
            
                {{-- @if(count($mandant->mandantUsers) > 0) --}}
                
                <div class="panel panel-primary" id="panelMandant{{$mandant->id}}">
                    
                    <div class="panel-heading">
                        <h4 class="panel-title col-xs-12 transform-normal">
                                <a data-toggle="collapse" data-target="#collapseMandant{{$mandant->id}}" class="collapsed transform-normal" 
                                   href="#collapseMandant{{$mandant->id}}">
                                  ({{$mandant->mandant_number}}) {{$mandant->kurzname}}
                                  @if($mandant->hauptstelle) [Hauptstelle] 
                                  @else [Filiale - {{ViewHelper::getHauptstelle($mandant)->mandant_number}}@if( strtolower(ViewHelper::getHauptstelle($mandant)->name) == "neptun") - NEPTUN] @else] @endif @endif
                                  @if($mandant->edited_by) 
                                  <span class="editing text-danger">[In Bearbeitung: 
                                  {{ ViewHelper::getUser($mandant->edited_by)->title}} 
                                  {{ ViewHelper::getUser($mandant->edited_by)->last_name}}]</span>
                                  @endif
                                </a>
                            
                        </h4>
                        
                        <span class="panel-options col-xs-12">
                                 <span class="panel-title transform-normal">
                                      {!! ViewHelper::showUserCount($mandant->usersActive, $mandant->usersInactive) !!}
                                 </span>
                                 <span class="pull-right">
                                {!! Form::open(['action' => 'MandantController@mandantActivate', 
                                'method'=>'PATCH']) !!}
                                    <input type="hidden" name="mandant_id" value="{{ $mandant->id }}">
                                    @if($mandant->active)
                                        <button class="btn btn-primary" type="submit" name="active" value="1"></span>{{trans('mandantenForm.active')}}</button>
                                    @else
                                        <button class="btn btn-danger" type="submit" name="active" value="0"></span>{{trans('mandantenForm.inactive')}}</button>
                                    @endif
                                {!! Form::close() !!}
                                
                                {!! Form::open(['route'=>['mandanten.destroy', 'id'=> $mandant->id], 'method'=>'DELETE']) !!}
                                    <button type="submit" class="btn btn-primary delete-prompt">{{trans('mandantenForm.remove')}}</button>
                                {!! Form::close() !!}
                                
                                @if($mandant->edited_by == 0 || $mandant->edited_by == Auth::user()->id)
                                    <a href="{{ url('/mandanten/'. $mandant->id. '/edit') }}" class="btn btn-primary no-arrow">{{trans('mandantenForm.edit')}}</a> 
                                @endif
                                </span>
                        </span>
                    </div>
                   
                    <div id="collapseMandant{{$mandant->id}}" class="panel-collapse collapse  
                    @if(Session::has('mandantChanged'))
                        @if( Session::get('mandantChanged') == $mandant->id )
                            in
                        @endif
                    @endif">
                        <div class="panel-body box-white">
                             @if(Session::has('mandantChanged'))
                                @if( Session::get('mandantChanged') == $mandant->id )
                                    <input type="hidden" class="scrollTo" value="#panelMandant{{ $mandant->id }}" />
                                @endif
                            @endif
                            
                            
                            
                            <table class="table @if( count($mandant->mandantUsers) > 1) data-table @endif ">
                            <thead>
                                <th class="defaultSort">Name</th>
                                <th class="col-md-8 no-sort">Rollen</th>
                                <th class="no-sort">Mandanten</th>
                                <th class="text-center no-sort">Optionen</th>
                            </thead>
                            <tbody>
                            
                                @if(count($mandant->mandantUsers) > 0)
                                    
                                    @foreach( $mandant->mandantUsers as $mandantUser )
                                        <tr>
                                            <td class="valign">
                                                {{ $mandantUser->user->last_name ." ". $mandantUser->user->first_name }} 
                                                </td>
                                            <td class="col-md-8 valign">
                                                @foreach( $mandantUser->mandantUserRoles as $mandantUserRole)
                                                       {{ $mandantUserRole->role->name }};
                                                    @endforeach

                                            </td>
                                            <td class="text-center valign">{{ count($mandantUser->user->countMandants) }}</td>
                                            <td class="valign table-options text-center">
                                                {{-- NEPTUN-751 --}}
                                                
                                                @if($mandant->active || count($mandantUser->user->countMandants) > 1)
                                                {!! Form::open(['action' => 'UserController@userActivate', 'method'=>'PATCH']) !!}
                                                    <input type="hidden" name="user_id" value="{{ $mandantUser->user->id }}">
                                                    <input type="hidden" name="mandant_id" value="{{ $mandant->id }}">
                                                    
                                                    @if($mandantUser->user->id != 1)
                                                        @if($mandantUser->user->active)
                                                            <button class="btn btn-xs btn-success" type="submit" name="active" value="1"></span>{{trans('mandantenForm.active')}}</button><br>
                                                        @else
                                                            <button class="btn btn-xs btn-danger" type="submit" name="active" value="0"></span>{{trans('mandantenForm.inactive')}}</button><br>
                                                        @endif
                                                    @endif
                                                {!! Form::close() !!}
                                                @endif
                                                
                                                @if($mandantUser->user->id != 1)
                                                    {{-- Form::open(['route'=>['benutzer.destroy', 'id'=> $mandantUser->user->id], 'method'=>'DELETE']) --}}
                                                    {!! Form::open(['action' => 'MandantController@destroyMandantUser', 'method'=>'POST']) !!}
                                                        <input type="hidden" name="user_id" value="{{ $mandantUser->user->id }}">
                                                        <input type="hidden" name="mandant_id" value="{{ $mandant->id }}">
                                                        <button type="submit" class="btn btn-xs btn-warning delete-prompt"
                                                        data-text="Wollen Sie diesen Benutzer wirklich löschen?"
                                                        >{{trans('mandantenForm.remove')}}</button><br>
                                                    {!! Form::close() !!}
                                                @endif
                                                
                                                <a href="{{route('benutzer.edit', ['id'=> $mandantUser->user->id])}}" class="btn btn-xs btn-primary">{{trans('mandantenForm.edit')}}</a>
                                            </td>
                                        </tr>
                                        
                                    @endforeach
                                    
                                    
                                @else
                                    <tr>
                                        <td> Keine Daten vorhanden. </td>
                                        <!--fix for Cannot set property '_DT_CellIndex' of undefined-->
                                        <td></td> <td></td> <td></td>
                                        <!-- end fix -->
                                    </tr>
                                @endif
                            
                            </tbody>
                            </table>

                        </div>
                    </div>
                    
                </div>
                
                {{-- @endif --}}
                
            @endforeach
            
        </div>
        
    @endif
    
    @if( !empty($search) && $search == true )
    
        <h2 class="title">Suchergebnisse für Benutzer ({{count($users)}})</h2>
        
        @if( !empty($users)  ) 
            
            <div class="panel-group">
                
                @foreach( $users as $user)
                    
                    <div class="panel panel-primary" id="panelUsers">
                        
                        <div class="panel-heading">
                            <h4 class="panel-title pull-left">
                                <span class="panel-title transform-normal">
                                    {{$user->last_name}} @if($user->short_name)({{$user->short_name}})@endif {{$user->first_name}}
                                </span>
                            </h4>
                        
                            <span class="pull-right">
                                 <a href="{{route('benutzer.edit', ['id'=> $user->id])}}" class="btn btn-xs btn-primary no-arrow no-margin-bottom">{{trans('mandantenForm.edit')}}</a>
                            </span>
                            
                        </div>
                        
                    </div>
                    
                @endforeach
                
            </div>
            
        @endif
        
    @endif
    
    
    @if(!empty($unassignedUsers))
    
        <div class="panel-group">
                
            <div class="panel panel-primary" id="noMandant">
                <div class="panel-heading">
                     <h4 class="panel-title col-xs-12">
                         <a data-toggle="collapse" data-target="#collapseNoMandant" class="collapsed transform-normal" href="#collapseNoMandant">
                            Kein Mandant
                         </a>
                     </h4>
                      <span class="panel-options col-xs-12">
                            <span class="panel-title transform-normal">
                                {!! ViewHelper::showUserCount($unassignedActiveUsers, $unassignedInactiveUsers) !!}
                            </span>
                       </span>             
                </div>
                <div id="collapseNoMandant" class="panel-collapse collapse ">
                    <div class="panel-body">
                        
                            @if(count($unassignedUsers) > 0)
                                    <table class="table @if(count($unassignedUsers) > 1 ) data-table @endif">
                                    <thead>
                                        <th class="col-md-10 defaultSort">Name</th>
                                        <th class="col-md-2 text-center no-sort">Optionen</th>
                                    </thead>
                                    <tbody>
                                        
                                        @foreach( $unassignedUsers as $unassignedUser )
                                             {{-- @if( $mandantUser->deleted_at == null ) --}}
                                                <tr>
                                                    <td class="valign">{{ $unassignedUser->last_name ." ". $unassignedUser->first_name }} </td>
                                                    <td class="valign table-options text-center no-sort">
                                                        {!! Form::open(['action' => 'UserController@userActivate', 'method'=>'PATCH']) !!}
                                                            <input type="hidden" name="user_id" value="{{ $unassignedUser->id }}">
                                                            
                                                            @if($unassignedUser->active)
                                                                <button class="btn btn-xs btn-success" type="submit" name="active" value="1"></span>{{trans('mandantenForm.active')}}</button><br>
                                                            @else
                                                                <button class="btn btn-xs btn-danger" type="submit" name="active" value="0"></span>{{trans('mandantenForm.inactive')}}</button><br>
                                                            @endif
                                                        {!! Form::close() !!}
                                                        
                                                        {!! Form::open(['route'=>['benutzer.destroy', 'id'=> $unassignedUser->id], 'method'=>'DELETE']) !!}
                                                            <button type="submit" class="btn btn-xs btn-warning">entfernen</button><br>
                                                        {!! Form::close() !!}
                                                        <a href="{{route('benutzer.edit', ['id'=> $unassignedUser->id])}}" class="btn btn-xs btn-primary">{{trans('mandantenForm.edit')}}</a>
                                                    </td>
                                                    
                                                    
                                                </tr>
                                            {{-- @endif --}}
                                        @endforeach
                                    </tbody>
                                </table>
                            @endif
                        
                    </div>
                </div>
            </div>
                
        </div>
        
    @endif
    
    
      
    
@stop