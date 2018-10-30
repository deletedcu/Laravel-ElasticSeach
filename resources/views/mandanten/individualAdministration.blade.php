@extends('master')

@section('page-title')
    {{  ucfirst( trans('controller.userManagment') ) }}  
@stop
    @section('bodyClass')
    mandant-administration 
    @stop
    @section('content')
    <div class="col-xs-12 box-wrapper">
        <h2 class="title">{{ trans('mandantenForm.searchUsers') }} </h2>
        <div class="box box-white">
            {!! Form::open([
                   'url' => 'mandanten/search-single',
                   'method' => 'POST',
                   'class' => 'horizontal-form' ]) !!}
                <div class="row">
                    <!-- input box-->
                    <div class="col-md-6 col-lg-6"> 
                        <div class="form-group no-margin-bottom">
                            {!! ViewHelper::setInput('search', $searchParameter, $searchParameter, '', trans('mandantenForm.searchUsers')   ) !!}
                        </div>   
                    </div><!--End input box-->
                    <!-- input box-->
      
                    {{--
                    <div class="col-md-6 col-lg-6"> 
                        <div class="form-group label-form-group  no-margin-bottom">
                                {!! ViewHelper::setCheckbox('deleted_users', $deletedUsers, $deletedUsers, trans('mandantenForm.showDeletedUsers') ) !!}
                                {!! ViewHelper::setCheckbox('deleted_mandants', $deletedMandants, $deletedMandants, trans('mandantenForm.showDeletedClients') ) !!}
                        </div>   
                    </div><!--End input box-->
                    --}}
                    
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
            <h2 class="title">Suchergebnisse für Mandanten ({{count($mandants)}})</h2>
        @else
            <h2 class="title">Übersicht</h2>
        @endif
        
        <div class="panel-group ">
            
            @foreach( $mandants as $mandant)
            
                <div class="panel panel-primary" id="panelMandant{{$mandant->id}}">
                    
                    <div class="panel-heading">
                        <h4 class="panel-title col-xs-12 transform-normal">
                                <a data-toggle="collapse" data-target="#collapseMandant{{$mandant->id}}" class="collapsed transform-normal" 
                                   href="#collapseMandant{{$mandant->id}}">
                                  ({{$mandant->mandant_number}}) {{$mandant->kurzname}}
                                </a>
                            
                        </h4>
                        
                        <span class="panel-options col-xs-12">
                             <span class="panel-title transform-normal">
                                  {!! ViewHelper::showUserCount($mandant->usersActive, $mandant->usersInactive) !!}
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
                            
                            
                            
                            <table class="table data-table box-white">
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
                                            <td class="valign"> {{ $mandantUser->user->last_name ." ". $mandantUser->user->first_name }} </td>
                                            <td class="col-md-8 valign">
                                                @foreach( $mandantUser->mandantUserRoles as $mandantUserRole)
                                                   {{ $mandantUserRole->role->name }};
                                                @endforeach
                                              
                                            </td>
                                            <td class="text-center valign"> {{ count($mandantUser->user->countMandants) }} </td>
                                            <td class="valign table-options text-center">
                                                {!! Form::open(['action' => 'UserController@userActivate', 'method'=>'PATCH']) !!}
                                                    <input type="hidden" name="user_id" value="{{ $mandantUser->user->id }}">
                                                    <input type="hidden" name="mandant_id" value="{{ $mandant->id }}">
                                                    @if($mandantUser->user->id != 1)
                                                        @if($mandantUser->user->active)
                                                            <button class="btn btn-xs btn-success" type="submit" name="active" value="1">{{ trans('benutzerForm.active') }}</button><br>
                                                        @else
                                                            <button class="btn btn-xs btn-danger" type="submit" name="active" value="0">{{ trans('benutzerForm.inactive') }}</button><br>
                                                        @endif
                                                    @endif
                                                {!! Form::close() !!}
                                                
                                                @if($mandantUser->user->id != 1)
                                                    {!! Form::open(['action' => 'UserController@destroyMandantUser', 'method'=>'POST']) !!}
                                                        <input type="hidden" name="user_id" value="{{ $mandantUser->user->id }}">
                                                        <input type="hidden" name="mandant_id" value="{{ $mandant->id }}">
                                                        <button type="submit" class="btn btn-xs btn-warning delete-prompt"
                                                        data-text="Wollen Sie diesen Benutzer wirklich löschen?"
                                                        >{{ trans('benutzerForm.remove') }}</button><br>
                                                    {!! Form::close() !!}
                                                @endif
                                                
                                                <a href="{{url('/benutzer/'. $mandantUser->user->id .'/partner/edit')}}" class="btn btn-xs btn-primary">
                                                    {{ trans('benutzerForm.edit') }}
                                                </a>
                                                
                                            </td>
                                        </tr>
                                        
                                    @endforeach
                                    
                                    
                                @else
                                    <tr>
                                        <td colspan="4"> Keine Daten vorhanden. </td>
                                        <!--fix for Cannot set property '_DT_CellIndex' of undefined-->
                                        <td></td><td></td><td></td>
                                        <!-- end fix -->
                                    </tr>
                                @endif
                            
                            </tbody>
                            </table>

                        </div>
                    </div>
                    
                </div>
                
            @endforeach
            
        </div>
        
    @endif
    
    @if( !empty($search) && $search == true )
    
        <h2 class="title ">Suchergebnisse für Benutzer ({{count($mandantUsersSearch)}})</h2>
        
        @if( !empty($mandantUsersSearch)  ) 
            
            <div class="panel-group">
                
                @foreach( $mandantUsersSearch as $mandantUser)
                        
                    <div class="panel panel-primary" id="panelUsers">
                        
                        <div class="panel-heading">
                            
                            <h4 class="panel-title pull-left">
                                <span class="panel-title transform-normal">
                                    {{$mandantUser->user->first_name}} @if($mandantUser->user->short_name)({{$mandantUser->user->short_name}})@endif {{$mandantUser->user->last_name}} 
                                    [{{$mandantUser->mandant->kurzname}}]
                                </span>
                                {{--
                                <br>
                                <span class="panel-options transform-normal">
                                    ({{$mandantUser->mandant->kurzname}})
                                </span>
                                --}}
                            </h4>
                        
                            <span class="pull-right">
                                {!! Form::open(['url' => '/benutzer/'. $mandantUser->user->id .'/partner/'. $mandantUser->mandant->id .'/edit', 'method'=>'POST']) !!}
                                     <button type="submit" class="btn btn-xs btn-primary no-margin-bottom">{{ trans('benutzerForm.edit') }}</button>
                                {!! Form::close() !!}
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
                     <h4 class="panel-title col-xs-12 transform-normal">
                         <a data-toggle="collapse" data-target="#collapseNoMandant" class="collapsed" href="#collapseNoMandant">
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
                                    <table class="table data-table">
                                    <thead>
                                        <th class="col-md-10 defaultSort">Name</th>
                                        <th class="col-md-2 text-center no-sort">Optionen</th>
                                    </thead>
                                    <tbody>
                                        
                                        @foreach( $unassignedUsers as $unassignedUser )
                                             {{-- @if( $mandantUser->deleted_at == null ) --}}
                                                <tr>
                                                    <td class="valign">{{ $unassignedUser->last_name ." ". $unassignedUser->first_name }} </td>
                                                    <td class="valign table-options text-center">
                                                        {!! Form::open(['action' => 'UserController@userActivate', 'method'=>'PATCH']) !!}
                                                            <input type="hidden" name="user_id" value="{{ $unassignedUser->id }}">
                                                            
                                                            @if($unassignedUser->active)
                                                                <button class="btn btn-xs btn-success" type="submit" name="active" value="1">{{ trans('benutzerForm.active') }}</button><br>
                                                            @else
                                                                <button class="btn btn-xs btn-danger" type="submit" name="active" value="0">{{ trans('benutzerForm.inactive') }}</button><br>
                                                            @endif
                                                        {!! Form::close() !!}
                                                        
                                                        {!! Form::open(['route'=>['benutzer.destroy', 'id'=> $unassignedUser->id], 'method'=>'DELETE']) !!}
                                                            <button type="submit" class="btn btn-xs btn-warning">{{ trans('benutzerForm.remove') }}</button><br>
                                                        {!! Form::close() !!}
                                                        <a href="{{route('benutzer.edit', ['id'=> $unassignedUser->id])}}" class="btn btn-xs btn-primary">{{ trans('benutzerForm.edit') }}</a>
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

@section('script')
    @if( count($mandants) == 1 )
       <script type="text/javascript" >
        $(document).ready(function(){
            $('a.collapsed').click();
        });
       </script>
    @endif
@stop