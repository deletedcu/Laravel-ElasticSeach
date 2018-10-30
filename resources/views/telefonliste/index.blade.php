{{-- TELEFONLISTE --}}

@extends('master')

@section('page-title') {{ trans('telefonListeForm.phone-list') }} @stop

@section('bodyClass')
    phonelist
@stop

@section('content')

    <fieldset class="telefonliste forms">

        <div class="row">

            {{ Form::open(['action' => 'SearchController@searchPhoneList', 'method' => 'POST']) }}

            <div class="col-xs-12">
                <div class="box-wrapper">
                    <h4 class="title">{{ trans('telefonListeForm.search') }} {{ trans('telefonListeForm.phone-list') }}</h4>
                    <div class="box box-white">
                        <div class="clearfix"></div>
                        <div class="row">
                            {{--
                            <div class="col-xs-12 col-md-8 col-lg-4 form-group no-margin-bottom">
                                <input type="text" class="form-control" name="search"
                                       placeholder="{{ trans('telefonListeForm.search').' '.trans('telefonListeForm.searchTextOptions') }}"
                                       required
                                       @if(isset($searchParameter)) value="{{$searchParameter}}" @endif>
                            </div>
                            --}}
                            <div class="clearfix"></div><br>
                            <div class="col-xs-12 col-md-8 col-lg-4 form-group no-margin-bottom">
                                <select name="search" class="form-control select" data-placeholder="{{ strtoupper(trans('telefonListeForm.search').' '.trans('telefonListeForm.searchTextOptions')) }}" required>
                                    <option></option>
                                    @foreach($searchSuggestions as $suggestion)
                                        <option @if(isset($searchParameter) && ($searchParameter == $suggestion)) selected @endif value="{{$suggestion}}">
                                            {{$suggestion}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-md-12 col-lg-12 form-group no-margin-bottom">


                                    <div class="col-md-4 col-lg-2 hidden-sm hidden-xs">
                                        <span class="custom-input-group-btn">
                                            <button type="submit" class="btn btn-primary no-margin-bottom"
                                                    title="{{ trans('telefonListeForm.search') }}">
                                                <!--<i class="fa fa-search"></i>-->{{ trans('telefonListeForm.search') }}
                                            </button>


                                        </span>
                                    </div>
                                    @if(isset($searchParameter))
                                        <div class="col-md-4 col-lg-2 hidden-sm hidden-xs">
                                                <span class="custom-input-group-btn">
                                                    <a href="{{url('telefonliste')}}"
                                                       class="btn btn-primary no-margin-bottom">{{ trans('telefonListeForm.reset') }}</a>
                                                </span>
                                        </div>
                                    @endif


                                    <div class="col-md-4 col-lg-2 hidden-sm hidden-xs">
                                             <span class="custom-input-group-btn">
                                                <a href="#" class="btn btn-primary" data-toggle="modal"
                                                   data-target="#darstellung">
                                                    <!--<i class="fa fa-eye"></i> -->
                                                    {{ trans('telefonListeForm.appearance') }}
                                                </a>
                                            </span>
                                    </div>
                                </div>

                            </div><!-- .row -->


                            <div class="col-xs-4 hidden-md hidden-lg">
                                        <span class="custom-input-group-btn">
                                            <button type="submit" class="btn btn-primary no-margin-bottom"
                                                    title="{{ trans('telefonListeForm.search') }}">
                                                <!--<i class="fa fa-search"></i>-->{{ trans('telefonListeForm.search') }}
                                            </button>


                                        </span>
                            </div>
                            @if(isset($searchParameter))
                                <div class="col-xs-4 hidden-md hidden-lg">
                                            <span class="custom-input-group-btn">
                                                <a href="{{url('telefonliste')}}"
                                                   class="btn btn-primary no-margin-bottom">{{ trans('telefonListeForm.reset') }}</a>
                                            </span>
                                </div>
                            @endif

                            <div class="col-xs-4  hidden-md hidden-lg">
                                    <span class="custom-input-group-btn">
                                        <a href="#" class="btn btn-primary" data-toggle="modal"
                                           data-target="#darstellung">
                                            {{ trans('telefonListeForm.appearance') }}
                                        </a>
                                    </span>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            {{ Form::close() }}

        </div>

    </fieldset>


    <div class="row">
        <div class="col-xs-12">

            @if( !empty($search) && $search == true )
                <h2 class="title">Suchergebnisse für Mandanten ({{count($mandants)}})</h2>
            @else
                <h2 class="title">Übersicht</h2>
            @endif

            @if( count($mandants) > 0 )
                <div class="panel-group" role="tablist" data-multiselectable="true" aria-multiselectable="true">

                    @foreach($mandants as $mandant)
                        <div id="panel-{{$mandant->id}}" class="panel panel-primary">

                            <div class="panel-heading">
                                <h4 class="panel-title transform-normal display-inline col-lg-10 col-md-8">
                                    <a data-toggle="collapse" data-target="#collapseMandant{{$mandant->id}}"
                                       class="collapsed transform-normal"
                                       href="#collapseMandant{{$mandant->id}}"
                                       @if(isset($mandant->openTreeView) ) data-open="true" @endif>

                                        ({{$mandant->mandant_number}}) {{$mandant->kurzname}}
                                        @if($mandant->hauptstelle) [Hauptstelle]
                                        @else [Filiale - {{ViewHelper::getHauptstelle($mandant)->mandant_number}}]
                                        @endif
                                    </a>
                                </h4>

                            <span class="panel-options col-lg-2 col-md-4 no-margin-top">
                                <span class="pull-right">
                                    <a href="#" data-toggle="modal" data-target="#details{{$mandant->id}}"
                                       class="btn btn-primary no-arrow">
                                        Firmeninformationen </a><!-- before was Detailansicht -->
                                </span>
                            </span>

                            </div>

                            <div id="collapseMandant{{$mandant->id}}" class="panel-collapse collapse" role="tabpanel"
                                 aria-labelledby="heading-{{$mandant->id}}">
                                <div class="panel-body box-white">
                                    <table class="table data-table">
                                        <thead>
                                        <tr>
                                            <th class="@if(!isset($visible['col1'])) col-hide @endif col1 no-sort">{{ trans('telefonListeForm.photo') }} </th>
                                            <th class="@if(!isset($visible['col2'])) col-hide @endif col2">{{ trans('telefonListeForm.title') }} </th>
                                            <th class="@if(!isset($visible['col3'])) col-hide @endif col3">{{ trans('telefonListeForm.firstname') }} </th>
                                            <th class="@if(!isset($visible['col4'])) col-hide @endif col4 defaultSort">{{ trans('telefonListeForm.lastname') }} </th>
                                            <th class="@if(!isset($visible['col5'])) col-hide @endif col5 no-sort">{{ trans('telefonListeForm.role') }} </th>
                                            <th class="@if(!isset($visible['col6'])) col-hide @endif col6 no-sort">{{ trans('telefonListeForm.phone') }} </th>
                                            @if(ViewHelper::getMandantIsNeptun(Auth::user()->id))
                                            <th class="@if(!isset($visible['col7'])) col-hide @endif col7 no-sort">{{ trans('telefonListeForm.phone_short') }} </th>
                                            @endif
                                            <th class="@if(!isset($visible['col8'])) col-hide @endif col8 no-sort">{{ trans('telefonListeForm.phone_mobile') }} </th>
                                            <th class="@if(!isset($visible['col9'])) col-hide @endif col9 no-sort">{{ trans('telefonListeForm.email_work') }} </th>
                                            <th class="@if(!isset($visible['col10'])) col-hide @endif col10 no-sort">{{ trans('telefonListeForm.email_private') }} </th>
                                            <th class="@if(!isset($visible['col11'])) col-hide @endif col11 no-sort">{{ trans('telefonListeForm.position') }} </th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        {{-- dd(array_pluck($mandant->usersInternal,'role_id')) --}}
                                        @foreach($mandant->usersInternal as $internal)
                                            @if( is_object($internal->user))
                                                <tr>
                                                    <td>
                                                        @if(isset($internal->user->picture) && $internal->user->picture)
                                                            <img class="img-responsive img-phonelist"
                                                                 src="{{url('/files/pictures/users/'. $internal->user->picture)}}"/>
                                                        @else
                                                            <img class="img-responsive img-phonelist"
                                                                 src="{{url('/img/user-default.png')}}"/>
                                                        @endif
                                                    </td>

                                                    <td>{{ $internal->user->title }}</td>
                                                    <td>{{ $internal->user->first_name }}</td>
                                                    <td>{{ $internal->user->last_name }}</td>
                                                    {{-- <td>{{ $internal->role->name }}</td> --}}
                                                    <td>{{ $internal->user->abteilung }}</td>
                                                    <td>{{ $internal->user->phone }}</td>

                                                    @if(ViewHelper::getMandantIsNeptun(Auth::user()->id))
                                                    <td>{{ $internal->user->phone_short }}</td>
                                                    @endif

                                                    <td>{{ $internal->user->phone_mobile }}</td>
                                                    <td>{{ $internal->user->email_work }}</td>
                                                    <td>{{ $internal->user->email_private }}</td>
                                                    <td>{{ $internal->user->position }}</td>
                                                </tr>
                                            @endif
                                        @endforeach

                                        @foreach($mandant->usersInMandants as $user)
                                            {{-- @if(ViewHelper::phonelistVisibility($user, $mandant)) --}}
                                            <tr>
                                                <td width="60">
                                                    @if(isset($user->picture) && $user->picture)
                                                        <img class="img-responsive img-phonelist"
                                                             src="{{url('/files/pictures/users/'. $user->picture)}}"/>
                                                    @else
                                                        <img class="img-responsive img-phonelist"
                                                             src="{{url('/img/user-default.png')}}"/>
                                                    @endif
                                                </td>
                                                <td>{{ $user->title }}</td>
                                                <td>{{ $user->first_name }}</td>
                                                <td>{{ $user->last_name }}</td>
                                                <td>
                                                    {{-- User roles --}}
                                                    {{--
                                                    <div class="user-roles">
                                                    @foreach( $user->mandantRoles as $mandantUserRole)
                                                        @if(ViewHelper::getMandant(Auth::user()->id)->rights_admin || ViewHelper::universalHasPermission())
                                                            @if( $mandantUserRole->role->phone_role || $mandantUserRole->role->mandant_role )
                                                                @if( !in_array($mandantUserRole->role->id, array_pluck($mandant->usersInternal,'role_id')) )
                                                                    @if($mandantUserRole->mandantUser->mandant->id == $mandant->id)
                                                                        {{ ( $mandantUserRole->role->name ) }}
                                                                    @endif
                                                                @endif
                                                            @endif
                                                        @else
                                                            @if(isset($mandant))
                                                                @if($mandant->rights_admin)
                                                                    @if( $mandantUserRole->role->phone_role )
                                                                        @if( !in_array($mandantUserRole->role->id, array_pluck($mandant->usersInternal,'role_id')) )
                                                                            @if($mandantUserRole->mandantUser->mandant->id == $mandant->id)
                                                                                {{ ( $mandantUserRole->role->name ) }}
                                                                            @endif
                                                                        @endif
                                                                    @endif
                                                                @else
                                                                    @if( $mandantUserRole->role->mandant_role )
                                                                        @if( !in_array($mandantUserRole->role->id, array_pluck($mandant->usersInternal,'role_id')) )
                                                                            @if($mandantUserRole->mandantUser->mandant->id == $mandant->id)
                                                                                {{ ( $mandantUserRole->role->name ) }}
                                                                            @endif
                                                                        @endif
                                                                    @endif
                                                                @endif
                                                            @endif
                                                        @endif
                                                    @endforeach
                                                    </div>
                                                    --}}
                                                    {{ $user->abteilung }}
                                                </td>
                                                <td>{{ $user->phone }}</td>

                                                @if(ViewHelper::getMandantIsNeptun(Auth::user()->id))
                                                <td>{{ $user->phone_short }}</td>
                                                @endif

                                                <td>{{ $user->phone_mobile }}</td>
                                                <td>{{ $user->email_work }}</td>
                                                <td>{{ $user->email_private }}</td>
                                                <td>{{ $user->position }}</td>
                                            </tr>
                                            {{-- @endif --}}
                                        @endforeach

                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </div>
                    @endforeach

                </div>
            @else
                @if(empty($search)) <h2 class="title">{{ trans('telefonListeForm.noResults') }}</h2> @endif
            @endif


            @if( !empty($search) && $search == true )

                <h2 class="title">Suchergebnisse für Benutzer ({{count($users)+count($usersInternal)}})</h2>

                @if(count($users))


                    <div class="panel panel-primary" id="panelUsers">

                        <div class="panel-heading">
                            <h4 class="panel-title col-xs-10">
                                <a data-toggle="collapse" data-target="#userSearch" href="#userSearch" class="transform-normal">
                                    {{ trans('telefonListeForm.userList') }}
                                </a>
                            </h4>
                        </div>

                        <div id="userSearch" class="panel-collapse collapse in" role="tabpanel">
                            <div class="panel-body">
                                <table class="table data-table">
                                    <thead>
                                    <tr>
                                        <th class="@if(!isset($visible['col1'])) col-hide @endif col1 no-sort">{{ trans('telefonListeForm.photo') }} </th>
                                        <th class="@if(!isset($visible['col2'])) col-hide @endif col2">{{ trans('telefonListeForm.title') }} </th>
                                        <th class="@if(!isset($visible['col3'])) col-hide @endif col3">{{ trans('telefonListeForm.firstname') }} </th>
                                        <th class="@if(!isset($visible['col4'])) col-hide @endif col4 defaultSort">{{ trans('telefonListeForm.lastname') }} </th>
                                        <th class="@if(!isset($visible['col5'])) col-hide @endif col5 no-sort">{{ trans('telefonListeForm.role') }} </th>
                                        <th class="@if(!isset($visible['col6'])) col-hide @endif col6 no-sort">{{ trans('telefonListeForm.phone') }} </th>
                                        @if(ViewHelper::getMandantIsNeptun(Auth::user()->id))
                                        <th class="@if(!isset($visible['col7'])) col-hide @endif col7 no-sort">{{ trans('telefonListeForm.phone_short') }} </th>
                                        @endif
                                        <th class="@if(!isset($visible['col8'])) col-hide @endif col8 no-sort">{{ trans('telefonListeForm.phone_mobile') }} </th>
                                        <th class="@if(!isset($visible['col9'])) col-hide @endif col9 no-sort">{{ trans('telefonListeForm.email_work') }} </th>
                                        <th class="@if(!isset($visible['col10'])) col-hide @endif col10 no-sort">{{ trans('telefonListeForm.email_private') }} </th>
                                        <th class="@if(!isset($visible['col11'])) col-hide @endif col11 no-sort">{{ trans('telefonListeForm.position') }} </th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    @foreach($usersInternal as $internal)
                                        <tr>
                                            <td>
                                                @if(isset($internal->user->picture) && $internal->user->picture)
                                                    <img class="img-responsive img-phonelist"
                                                         src="{{url('/files/pictures/users/'. $internal->user->picture)}}"/>
                                                @else
                                                    <img class="img-responsive img-phonelist"
                                                         src="{{url('/img/user-default.png')}}"/>
                                                @endif
                                            </td>
                                            <td>{{ $internal->user->title }}</td>
                                            <td>{{ $internal->user->first_name }}</td>
                                            <td>{{ $internal->user->last_name }}</td>

                                            {{--
                                            <td>
                                                ({{$internal->mandant->mandant_number}})
                                                {{ $internal->mandant->kurzname }}
                                            </td>
                                            --}}

                                            <td>{{ $internal->user->abteilung }}</td>
                                            <td>{{ $internal->user->phone }}</td>

                                            @if(ViewHelper::getMandantIsNeptun(Auth::user()->id))
                                            <td>{{ $internal->user->phone_short }}</td>
                                            @endif

                                            <td>{{ $internal->user->phone_mobile }}</td>
                                            <td>{{ $internal->user->email_work }}</td>
                                            <td>{{ $internal->user->email_private }}</td>
                                            <td>{{ $internal->user->position }}</td>
                                        </tr>
                                    @endforeach

                                    @foreach( $users as $user)
                                        <tr>
                                            <td>
                                                @if(isset($user->picture) && $user->picture)
                                                    <img class="img-responsive img-phonelist"
                                                         src="{{url('/files/pictures/users/'. $user->picture)}}"/>
                                                @else
                                                    <img class="img-responsive img-phonelist"
                                                         src="{{url('/img/user-default.png')}}"/>
                                                @endif
                                            </td>
                                            <td>{{ $user->title }}</td>
                                            <td>{{ $user->first_name }}</td>
                                            <td>{{ $user->last_name }}</td>
                                            {{--
                                            <td>
                                                @foreach(ViewHelper::getUserMandants($user->id) as $mandant)
                                                    ({{$mandant->mandant_number}}) {{$mandant->kurzname}};
                                                @endforeach
                                            </td>
                                            --}}
                                            <td>{{ $user->abteilung }}</td>
                                            <td>{{ $user->phone }}</td>

                                            @if(ViewHelper::getMandantIsNeptun(Auth::user()->id))
                                            <td>{{ $user->phone_short }}</td>
                                            @endif

                                            <td>{{ $user->phone_mobile }}</td>
                                            <td>{{ $user->email_work }}</td>
                                            <td>{{ $user->email_private }}</td>
                                            <td>{{ $user->position }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>


                @endif

            @endif


        </div>
    </div>


    <div class="clearfix"></div> <br>

    {{-- Darstellung modal --}}
    <div class="modal fade" id="darstellung" tabindex="-1" role="dialog" aria-labelledby="darstellung"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><span
                                class="fa fa-close"></span></button>
                    <h4 class="modal-title" id="myModalLabel">{{ trans('telefonListeForm.appearance') }}</h4>
                </div>

                {{ Form::open(['action' => 'TelephoneListController@displayOptions', 'method' => 'POST']) }}

                <div class="modal-body">

                    <div class="row">
                        <div class="col-xs-12">
                            <div class="form-inline">
                                <label>Sichtbare Tabellenspalten</label>
                                <select class="form-control select" name="visibleColumns[]" data-placeholder="Sichtbare Tabellenspalten" multiple required>
                                    <option value="col1" @if(isset($visible['col1'])) selected @endif>{{ trans('telefonListeForm.photo') }}</option>
                                    <option value="col2" @if(isset($visible['col2'])) selected @endif>{{ trans('telefonListeForm.title') }}</option>
                                    <option value="col3" @if(isset($visible['col3'])) selected @endif>{{ trans('telefonListeForm.firstname') }}</option>
                                    <option value="col4" @if(isset($visible['col4'])) selected @endif>{{ trans('telefonListeForm.lastname') }}</option>
                                    <option value="col5" @if(isset($visible['col5'])) selected @endif>{{ trans('telefonListeForm.role') }}</option>
                                    <option value="col6" @if(isset($visible['col6'])) selected @endif>{{ trans('telefonListeForm.phone') }}</option>
                                    @if(ViewHelper::getMandantIsNeptun(Auth::user()->id))
                                    <option value="col7" @if(isset($visible['col7'])) selected @endif>{{ trans('telefonListeForm.phone_short') }}</option>
                                    @endif
                                    <option value="col8" @if(isset($visible['col8'])) selected @endif>{{ trans('telefonListeForm.phone_mobile') }}</option>
                                    <option value="col9" @if(isset($visible['col9'])) selected @endif>{{ trans('telefonListeForm.email_work') }}</option>
                                    <option value="col10" @if(isset($visible['col10'])) selected @endif>{{ trans('telefonListeForm.email_private') }}</option>
                                    <option value="col11" @if(isset($visible['col11'])) selected @endif>{{ trans('telefonListeForm.position') }}</option>
                                </select>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">{{ trans('telefonListeForm.save') }}</button>
                </div>

                {{ Form::close() }}

            </div>
        </div>
    </div>

    {{-- Mandant details modals --}}
    @foreach($mandants as $mandant)
        <div class="modal fade" id="details{{$mandant->id}}" tabindex="-1" role="dialog"
             aria-labelledby="details{{$mandant->id}}" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><span
                                    class="fa fa-close"></span></button>
                        <h4 class="modal-title" id="myModalLabel">Detailansicht: {{$mandant->name}}</h4>
                    </div>

                    <div class="modal-body">

                        <div class="row general">
                            <div class="col-xs-10">
                                <h4>Allgemeine Informationen</h4>
                                <dl class="dl-horizontal">
                                    <dt>Name</dt>
                                    <dd>{{$mandant->name}}</dd>

                                    <dt>Mandantnummer</dt>
                                    <dd>{{$mandant->mandant_number}}</dd>

                                    <dt>Mandantname Kurz</dt>
                                    <dd>{{$mandant->kurzname}}</dd>

                                    <dt>Adresszusatz</dt>
                                    <dd>{{$mandant->adresszusatz}}</dd>

                                    <dt>Strasse/ Nr.</dt>
                                    <dd>{{$mandant->strasse}}/ {{$mandant->hausnummer}}</dd>

                                    <dt>PLZ/ Ort</dt>
                                    <dd>{{$mandant->plz}}/ {{$mandant->ort}}</dd>

                                    <dt>Bundesland</dt>
                                    <dd>{{$mandant->bundesland}}</dd>

                                    <dt>Telefon</dt>
                                    <dd>{{$mandant->telefon}}</dd>

                                    <dt>Kurzwahl</dt>
                                    <dd>{{$mandant->kurzwahl}}</dd>

                                    <dt>Fax</dt>
                                    <dd>{{$mandant->fax}}</dd>

                                    <dt>E-Mail</dt>
                                    <dd><a href="mailto:{{$mandant->email}}">{{$mandant->email}}</a></dd>

                                    <dt>Website</dt>
                                    <dd><a target="_blank" href="{{$mandant->website}}">{{$mandant->website}}</a></dd>
                                </dl>
                            </div>

                            <div class="col-xs-2">
                                @if($mandant->logo)
                                    <img class="img-responsive"
                                         src="{{url('/files/pictures/mandants/'. $mandant->logo)}}"/>
                                @else
                                    <img class="img-responsive" src="{{url('/img/mandant-default.png')}}"/>
                                @endif
                            </div>
                        </div>

                        @if(!$mandant->hauptstelle)
                            <div class="row hauptstelle">
                                <div class="col-xs-10">
                                    <h4>Hauptstelle</h4>
                                    <dl class="dl-horizontal">

                                        <dt>Name</dt>
                                        <dd>{{ViewHelper::getHauptstelle($mandant)->name}}</dd>

                                        <dt>Mandantnummer</dt>
                                        <dd>{{ViewHelper::getHauptstelle($mandant)->mandant_number}}</dd>

                                        <dt>Mandantname Kurz</dt>
                                        <dd>{{ViewHelper::getHauptstelle($mandant)->kurzname}}</dd>

                                        <dt>Adresszusatz</dt>
                                        <dd>{{ViewHelper::getHauptstelle($mandant)->adresszusatz}}</dd>

                                        <dt>Strasse/ Nr.</dt>
                                        <dd>{{ViewHelper::getHauptstelle($mandant)->strasse}}
                                            / {{ViewHelper::getHauptstelle($mandant)->hausnummer}}</dd>

                                        <dt>PLZ/ Ort</dt>
                                        <dd>{{ViewHelper::getHauptstelle($mandant)->plz}}
                                            / {{ViewHelper::getHauptstelle($mandant)->ort}}</dd>

                                        <dt>Bundesland</dt>
                                        <dd>{{ViewHelper::getHauptstelle($mandant)->bundesland}}</dd>

                                        <dt>Telefon</dt>
                                        <dd>{{ViewHelper::getHauptstelle($mandant)->telefon}}</dd>

                                        <dt>Kurzwahl</dt>
                                        <dd>{{ViewHelper::getHauptstelle($mandant)->kurzwahl}}</dd>

                                        <dt>Fax</dt>
                                        <dd>{{ViewHelper::getHauptstelle($mandant)->fax}}</dd>

                                        <dt>E-Mail</dt>
                                        <dd>
                                            <a href="mailto:{{ViewHelper::getHauptstelle($mandant)->email}}">{{ViewHelper::getHauptstelle($mandant)->email}}</a>
                                        </dd>

                                        <dt>Website</dt>
                                        <dd><a target="_blank"
                                               href="{{$mandant->website}}">{{ViewHelper::getHauptstelle($mandant)->website}}</a>
                                        </dd>
                                    </dl>
                                </div>

                                <div class="col-xs-2">
                                    @if(ViewHelper::getHauptstelle($mandant)->logo)
                                        <img class="img-responsive"
                                             src="{{url('/files/pictures/mandants/'. ViewHelper::getHauptstelle($mandant)->logo)}}"/>
                                    @else
                                        <img class="img-responsive" src="{{url('/img/mandant-default.png')}}"/>
                                    @endif
                                </div>
                            </div>
                        @endif

                        @if( ViewHelper::universalHasPermission( array(19,20) ) == true  )
                            <div class="row important">
                                <div class="col-xs-12">
                                    <h4>Wichtige Informationen</h4>
                                    <dl class="dl-horizontal">

                                        @if(isset($mandant->mandantInfo))
                                            <dt>Wichtiges</dt>
                                            <dd>{{$mandant->mandantInfo->info_wichtiges}}</dd>
                                        @endif

                                        <dt>Geschäftsführer</dt>
                                        <dd>{{$mandant->geschaftsfuhrer}}</dd>

                                        <dt>Geschäftsführer-Informationen</dt>
                                        <dd>{{$mandant->geschaftsfuhrer_infos}}</dd>

                                        <dt>Geschäftsführer Von</dt>
                                        <dd>{{$mandant->geschaftsfuhrer_von}}</dd>

                                        <dt>Geschäftsführer Bis</dt>
                                        <dd>{{$mandant->geschaftsfuhrer_bis}}</dd>

                                        <dt>Geschäftsführerhistorie</dt>
                                        <dd>{{$mandant->geschaftsfuhrer_history}}</dd>

                                    </dl>
                                </div>
                            </div>

                            @if(isset($mandant->mandantInfo))
                                <div class="row additional">
                                    <div class="col-xs-12">
                                        <h4>Weitere Informationen</h4>
                                        <dl class="dl-horizontal">

                                            <dt class="col-xs-4">Prokura</dt>
                                            <dd>{{$mandant->mandantInfo->prokura}}</dd>

                                            <dt>Betriebsnummer</dt>
                                            <dd>{{$mandant->mandantInfo->betriebsnummer}}</dd>

                                            <dt>Handelsregisternummer</dt>
                                            <dd>{{$mandant->mandantInfo->handelsregister}}</dd>

                                            <dt>Handelsregistersitz</dt>
                                            <dd>{{$mandant->mandantInfo->Handelsregister_sitz}}</dd>

                                            <dt>Gewerbeanmeldung</dt>
                                            <dd>{{$mandant->mandantInfo->angemeldet_am}}</dd>

                                            <dt>Umgemeldet am</dt>
                                            <dd>{{$mandant->mandantInfo->umgemeldet_am}}</dd>

                                            <dt>Abgemeldet am</dt>
                                            <dd>{{$mandant->mandantInfo->abgemeldet_am}}</dd>

                                            <dt>Gewerbeanmeldung Historie</dt>
                                            <dd>{{$mandant->mandantInfo->gewerbeanmeldung_history}}</dd>

                                            <dt>Steuernummer</dt>
                                            <dd>{{$mandant->mandantInfo->steuernummer}}</dd>

                                            <dt>USt-IdNr.</dt>
                                            <dd>{{$mandant->mandantInfo->ust_ident_number}}</dd>

                                            <dt>Zusätzliche Informationen Steuer</dt>
                                            <dd>{{$mandant->mandantInfo->zausatzinfo_steuer}}</dd>

                                            <dt>Berufsgenossenschaft/ Mitgliedsnummer</dt>
                                            <dd>{{$mandant->mandantInfo->berufsgenossenschaft_number}}</dd>

                                            <dt>Zusätzliche Informationen Berufsgenossenschaft</dt>
                                            <dd>{{$mandant->mandantInfo->berufsgenossenschaft_zusatzinfo}}</dd>

                                            <dt>Erlaubnis zur Arbeitnehmerüberlassung</dt>
                                            <dd>{{ Carbon\Carbon::parse( $mandant->mandantInfo->erlaubniss_gultig_ab)->format('d.m.Y h:i:s') }}</dd>

                                            <dt>Unbefristet</dt>
                                            @if($mandant->mandantInfo->unbefristet)
                                                <dd>Ja</dd>
                                            @else
                                                <dd>Nein</dd>
                                            @endif

                                            <dt>Befristet bis</dt>
                                            <dd>{{$mandant->mandantInfo->befristet_bis}}</dd>

                                            <dt>Zuständige Erlaubnisbehörde</dt>
                                            <dd>{{$mandant->mandantInfo->erlaubniss_gultig_von}}</dd>

                                            <dt>Informationen zum Geschäftsjahr</dt>
                                            <dd>{{$mandant->mandantInfo->geschaftsjahr_info}}</dd>

                                            @if( ViewHelper::universalHasPermission( array(20) ) == true  )
                                                <dt>Bankverbindungen</dt>
                                                <dd>{!! str_replace(array('[',']'), array('','<br>'), $mandant->mandantInfo->bankverbindungen) !!}</dd>
                                            @endif
                                            <dt>Sonstiges</dt>

                                            <dd>{{ $mandant->mandantInfo->info_sonstiges }}</dd>

                                        </dl>
                                    </div>
                                </div>
                            @endif
                        @endif

                    </div>

                    <div class="modal-footer">

                        @if( ViewHelper::universalHasPermission( array(20) ) == true  )
                                <!--this was wrapped around the button-> then changed with task NEPTUN-303 -->
                        @endif
                        <a target="_blank" href="{{url('/telefonliste/'.$mandant->id.'/pdf')}}" class="btn btn-primary">
                            <!--<i class="fa fa-file-pdf-o"></i>-->
                            {{ trans('telefonListeForm.pdf-export') }}
                        </a>

                    </div>
                </div>
            </div>
        </div>
    @endforeach


@stop
