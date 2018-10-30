{{-- DOKUMENT TYPEN --}}

@extends('master')

@section('page-title')
     {{ trans('dokumentTypenForm.document-types-management') }}
@stop

@section('content')

<fieldset class="form-group">
    <div class="box-wrapper">
        <h4 class="title">{{ trans('dokumentTypenForm.document') }}-{{ trans('dokumentTypenForm.type') }} {{ strtolower(trans('dokumentTypenForm.add') ) }}</h4>
        
        {!! Form::open(['route' => 'dokument-typen.store']) !!}
        
         <div class="box box-white">
              <div class="box-header">
                  <div class="row">
                    <div class="col-lg-3 col-md-3"><strong>{{ trans('dokumentTypenForm.name') }}*</strong></div>
                    <div class="col-lg-3 col-md-3"><strong>{{ trans('dokumentTypenForm.document_art') }}</strong></div>
                    <div class="col-lg-3 col-md-3"><strong>{{ trans('dokumentTypenForm.document_role') }}</strong></div>
                    <div class="col-lg-3 col-md-3"><strong>{{ trans('dokumentTypenForm.options') }}</strong></div>
                    <div class="col-lg-12"></div>
                </div>
             </div>
            <div class="row">
                <!-- input box-->
                <div class="col-md-3 col-lg-3"> 
                    <div class="form-group">
                        <div class="form-group">
                            <input type="text" name="name" class="form-control" placeholder="{{ trans('dokumentTypenForm.name') }}*" value="" required />
                        </div>
                       
                    </div>
                </div><!--End input box-->
                <div class="col-md-3 col-lg-3"> 
                    <div class="radio no-margin-top">
                        <label><input type="radio" name="document_art" value="0" checked>{{ trans('dokumentTypenForm.editor') }}</label>
                    </div>
                    <div class="radio">
                        <label><input type="radio" name="document_art" value="1">{{ trans('dokumentTypenForm.upload') }}</label>
                    </div>
                </div>
                <div class="col-md-3 col-lg-3">
                    <div class="radio no-margin-top">
                        <label><input type="radio" name="document_role" value="0" checked>{{ trans('dokumentTypenForm.document') }}-{{ trans('dokumentTypenForm.verfasser') }}</label>
                    </div>
                    <div class="radio">
                        <label><input type="radio" name="document_role" value="1">{{ trans('dokumentTypenForm.rundschreiben') }}-{{ trans('dokumentTypenForm.verfasser') }}</label>
                    </div>
                </div>
                <div class="col-md-3 col-lg-3">
                    <div class="checkbox no-margin-top">
                       <input type="checkbox" name="read_required" id="read_required-0"><label for="read_required-0">{{ trans('dokumentTypenForm.read_required') }}</label>
                    </div>
                    <div class="checkbox no-margin-top">
                        <input type="checkbox" name="allow_comments" id="allow_comments-0"><label for="allow_comments-0">{{ trans('dokumentTypenForm.allow_comments') }}</label>
                    </div>
                    <div class="checkbox no-margin-top">
                        <input type="checkbox" name="visible_navigation" id="visible_navigation-0"><label for="visible_navigation-0">{{ trans('dokumentTypenForm.visible_navigation') }}</label>
                    </div>
                    <div class="checkbox no-margin-top">
                        <input type="checkbox" name="publish_sending" id="publish_sending-0"><label for="publish_sending-0">{{ trans('dokumentTypenForm.publish_sending') }}</label>
                    </div>
                    <div class="clearfix"></div>
                    <div>
                        <select name="menu_position" id="menu_position" class="form-control select" data-placeholder="anzeigen in ... *" required>
                            <option></option>
                            <option value="1">Untermenü</option>
                            <option value="2">Hauptmenü</option>
                        </select>
                    </div>
                    
                </div>
                
            </div>
            <div class="row">
                <div class="col-xs-12">
                    <br> <button class="btn btn-primary">{{ trans('dokumentTypenForm.add') }} </button>
                </div>
            </div>
        </div>
        {!! Form::close() !!}
    </div>
</fieldset>

<fieldset class="form-group">
    
    <div class="box-wrapper">
        <div class="row">
            <div class="col-md-12">
                <h4 class="title"> {{ trans('dokumentTypenForm.overview-menu') }}</h4>
                <div class="box box-white">
                    <table class="table">
                        <tr>
                            <th class="col-lg-1"><!-- <strong> {{ trans('dokumentTypenForm.order') }}</strong> --></th>
                            <th class="col-lg-3"><strong>{{ trans('dokumentTypenForm.name') }}*</strong></th>
                            <th class="col-lg-2"><strong>{{ trans('dokumentTypenForm.document_art') }}</strong></th>
                            <th class="col-lg-3"><strong>{{ trans('dokumentTypenForm.document_role') }}</strong></th>
                            <th class="col-lg-3"><strong>{{ trans('dokumentTypenForm.options') }}</strong></th>
                            <th class="col-lg-1"></th>
                        </tr>
                        
                        @foreach($documentTypesMenu as $documentTypeMenu)
                            {{-- @if( in_array($documentTypeMenu->id, [App\DocumentType::JURISTEN, App\DocumentType::NOTIZEN]) == false ) --}}
                            @if( $documentTypeMenu->jurist_document == false )
                            {!! Form::open(['route' => ['dokument-typen.update', 'dokument_typen' => $documentTypeMenu->id], 'method' => 'PATCH']) !!}
                            <tr>
                                <td>
                                    <div class="text-center">
                                        <a href="{{ url('dokument-typen/sort-up/' . $documentTypeMenu->id) }}" class="inline-block"><i class="fa fa-arrow-up"></i></a>
                                        <a href="{{ url('dokument-typen/sort-down/' . $documentTypeMenu->id) }}" class="inline-block"><i class="fa fa-arrow-down"></i></a>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <input type="text" name="name" class="form-control" placeholder="{{ trans('dokumentTypenForm.name') }}" value="{{ $documentTypeMenu->name }}" required />
                                    </div>
                                </td>   
                                <td>
                                    <div class="">
                                        @if($documentTypeMenu->documents->isEmpty())
                                            <div class="radio no-margin-top">
                                                <label><input type="radio" name="document_art" value="0" @if(!$documentTypeMenu->document_art) checked @endif >{{ trans('dokumentTypenForm.editor') }}</label>
                                            </div>
                                            <div class="radio">
                                                <label><input type="radio" name="document_art" value="1" @if($documentTypeMenu->document_art) checked @endif >{{ trans('dokumentTypenForm.upload') }}</label>
                                            </div>
                                        @else
                                            @if($documentTypeMenu->document_art || $documentTypeMenu->id == 5)    
                                                {{ trans('dokumentTypenForm.upload') }} {{-- trans('dokumentTypenForm.document') --}}
                                            @else
                                                {{ trans('dokumentTypenForm.editor') }}
                                            @endif
                                        @endif
                                    </div>
                                </td>
                                <td>     
                                    <div class="">
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="document_role" value="0" @if(!$documentTypeMenu->document_role) checked @endif>
                                                {{ trans('dokumentTypenForm.document') }} {{ trans('dokumentTypenForm.verfasser') }}
                                            </label>
                                        </div>
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="document_role" value="1" @if($documentTypeMenu->document_role) checked @endif>
                                                {{ trans('dokumentTypenForm.rundschreiben') }} {{ trans('dokumentTypenForm.verfasser') }}
                                            </label>
                                        </div>
                                    </div>
                                 </td>  
                                 <td>
                                    <div class="">
                                        
                                        <div class="checkbox no-margin-top">
                                            <input type="checkbox" name="read_required" id="read_required-{{$documentTypeMenu->id}}" @if($documentTypeMenu->read_required) checked @endif>
                                            <label for="read_required-{{$documentTypeMenu->id}}">{{ trans('dokumentTypenForm.read_required') }}</label>
                                        </div>
                                        <div class="checkbox no-margin-top">
                                            <input type="checkbox" name="allow_comments" id="allow_comments-{{$documentTypeMenu->id}}" @if($documentTypeMenu->allow_comments) checked @endif>
                                            <label for="allow_comments-{{$documentTypeMenu->id}}">{{ trans('dokumentTypenForm.allow_comments') }}</label>
                                        </div>
                                        <div class="checkbox no-margin-top">
                                            <input type="checkbox" name="visible_navigation" id="visible_navigation-{{$documentTypeMenu->id}}" @if($documentTypeMenu->visible_navigation) checked @endif>
                                            <label for="visible_navigation-{{$documentTypeMenu->id}}">{{ trans('dokumentTypenForm.visible_navigation') }}</label>
                                        </div>
                                        <div class="checkbox no-margin-top">
                                            <input type="checkbox" name="publish_sending" id="publish_sending-{{$documentTypeMenu->id}}" @if($documentTypeMenu->publish_sending) checked @endif>
                                            <label for="publish_sending-{{$documentTypeMenu->id}}">{{ trans('dokumentTypenForm.publish_sending') }}</label>
                                        </div>
                                    </div>
                                 </td> 
                                 <td>
                                    <div class=" table-options text-right">
                                        
                                        <button class="btn btn-primary dark-blue" type="submit" name="switch_menu" value="1"> {{ trans('dokumentTypenForm.toSubmenu') }} </button>
                                        
                                        @if($documentTypeMenu->active)
                                            <button class="btn btn-success" type="submit" name="activate" value="1"> {{ trans('dokumentTypenForm.active') }} </button>
                                        @else
                                            <button class="btn btn-danger" type="submit" name="activate" value="0"> {{ trans('dokumentTypenForm.inactive') }} </button>
                                        @endif
                                        
                                        <button class="btn btn-primary" type="submit" name="save" value="1"> {{ trans('dokumentTypenForm.save') }} </button>
                                    </div>
                                </td>
                             
                                    {!! Form::close() !!}
                               @endif
                            </tr>  
                        @endforeach
                        
                    </table>
                </div>
            </div>
        </div>
    </div>
    
</fieldset>

<fieldset class="form-group">
    
    <div class="box-wrapper">
        <div class="row">
            <div class="col-md-12">
                <h4 class="title"> {{ trans('dokumentTypenForm.overview-submenu') }}</h4>
                <div class="box box-white">
                    <table class="table">
                        <tr>
                            <th class="col-lg-1"><!-- <strong> {{ trans('dokumentTypenForm.order') }}</strong> --></th>
                            <th class="col-lg-3"><strong>{{ trans('dokumentTypenForm.name') }}*</strong></th>
                            <th class="col-lg-2"><strong>{{ trans('dokumentTypenForm.document_art') }}</strong></th>
                            <th class="col-lg-3"><strong>{{ trans('dokumentTypenForm.document_role') }}</strong></th>
                            <th class="col-lg-3"><strong>{{ trans('dokumentTypenForm.options') }}</strong></th>
                            <th class="col-lg-1"></th>
                        </tr>
                        
                        @foreach($documentTypesSubmenu as $documentTypeSubmenu)
                            {{-- @if( in_array($documentTypeSubmenu->id, [App\DocumentType::JURISTEN, App\DocumentType::NOTIZEN]) == false ) --}}
                            @if( $documentTypeSubmenu->jurist_document == false )
                            {!! Form::open(['route' => ['dokument-typen.update', 'dokument_typen' => $documentTypeSubmenu->id], 'method' => 'PATCH']) !!}
                            <tr>
                                <td>
                                    <div class="text-center">
                                        <a href="{{ url('dokument-typen/sort-up/' . $documentTypeSubmenu->id) }}" class="inline-block"><i class="fa fa-arrow-up"></i></a>
                                        <a href="{{ url('dokument-typen/sort-down/' . $documentTypeSubmenu->id) }}" class="inline-block"><i class="fa fa-arrow-down"></i></a>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <input type="text" name="name" class="form-control" placeholder="{{ trans('dokumentTypenForm.name') }}" value="{{ $documentTypeSubmenu->name }}" required />
                                    </div>
                                </td>   
                                <td>
                                    <div class="">
                                        @if($documentTypeSubmenu->documents->isEmpty())
                                            <div class="radio no-margin-top">
                                                <label><input type="radio" name="document_art" value="0" @if(!$documentTypeSubmenu->document_art) checked @endif >{{ trans('dokumentTypenForm.editor') }}</label>
                                            </div>
                                            <div class="radio">
                                                <label><input type="radio" name="document_art" value="1" @if($documentTypeSubmenu->document_art) checked @endif >{{ trans('dokumentTypenForm.upload') }}</label>
                                            </div>
                                        @else
                                            @if($documentTypeSubmenu->document_art || $documentTypeSubmenu->id == 5)    
                                                {{ trans('dokumentTypenForm.upload') }} {{-- trans('dokumentTypenForm.document') --}}
                                            @else
                                                {{ trans('dokumentTypenForm.editor') }}
                                            @endif
                                        @endif
                                    </div>
                                </td>
                                <td>     
                                    <div class="">
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="document_role" value="0" @if(!$documentTypeSubmenu->document_role) checked @endif>
                                                {{ trans('dokumentTypenForm.document') }} {{ trans('dokumentTypenForm.verfasser') }}
                                            </label>
                                        </div>
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="document_role" value="1" @if($documentTypeSubmenu->document_role) checked @endif>
                                                {{ trans('dokumentTypenForm.rundschreiben') }} {{ trans('dokumentTypenForm.verfasser') }}
                                            </label>
                                        </div>
                                    </div>
                                 </td>  
                                 <td>
                                    <div class="">
                                        
                                        <div class="checkbox no-margin-top">
                                            <input type="checkbox" name="read_required" id="read_required-{{$documentTypeSubmenu->id}}" @if($documentTypeSubmenu->read_required) checked @endif>
                                            <label for="read_required-{{$documentTypeSubmenu->id}}">{{ trans('dokumentTypenForm.read_required') }}</label>
                                        </div>
                                        <div class="checkbox no-margin-top">
                                            <input type="checkbox" name="allow_comments" id="allow_comments-{{$documentTypeSubmenu->id}}" @if($documentTypeSubmenu->allow_comments) checked @endif>
                                            <label for="allow_comments-{{$documentTypeSubmenu->id}}">{{ trans('dokumentTypenForm.allow_comments') }}</label>
                                        </div>
                                        <div class="checkbox no-margin-top">
                                            <input type="checkbox" name="visible_navigation" id="visible_navigation-{{$documentTypeSubmenu->id}}" @if($documentTypeSubmenu->visible_navigation) checked @endif>
                                            <label for="visible_navigation-{{$documentTypeSubmenu->id}}">{{ trans('dokumentTypenForm.visible_navigation') }}</label>
                                        </div>
                                        <div class="checkbox no-margin-top">
                                            <input type="checkbox" name="publish_sending" id="publish_sending-{{$documentTypeSubmenu->id}}" @if($documentTypeSubmenu->publish_sending) checked @endif>
                                            <label for="publish_sending-{{$documentTypeSubmenu->id}}">{{ trans('dokumentTypenForm.publish_sending') }}</label>
                                        </div>
                                    </div>
                                 </td> 
                                 <td>
                                    <div class=" table-options text-right">
                                        
                                        <button class="btn btn-primary dark-blue" type="submit" name="switch_menu" value="2"> {{ trans('dokumentTypenForm.toMenu') }} </button>
                                        
                                        @if($documentTypeSubmenu->active)
                                            <button class="btn btn-success" type="submit" name="activate" value="1"> {{ trans('dokumentTypenForm.active') }} </button>
                                        @else
                                            <button class="btn btn-danger" type="submit" name="activate" value="0"> {{ trans('dokumentTypenForm.inactive') }} </button>
                                        @endif
                                        
                                        <button class="btn btn-primary" type="submit" name="save" value="1"> {{ trans('dokumentTypenForm.save') }} </button>
                                    </div>
                                </td>
                             
                                    {!! Form::close() !!}
                           
                            </tr> 
                            @endif
                        @endforeach
                        
                    </table>
                </div>
            </div>
        </div>
    </div>
    
</fieldset>

<div class="clearfix"></div> <br>

@stop
