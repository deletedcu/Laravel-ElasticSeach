{{-- PAPIERKORB --}}

@extends('master')
@section('page-title') {{ trans('controller.trash') }} @stop

@section('content')

@if(count($trashedDocuments))

    <div class="row">
    
        <div class="col-xs-12">
            <div class="box-wrapper trashed-documents">
                <h4 class="title">{{ trans('controller.overview') }}</h4>
                
                {{ Form::open(['action' => 'DocumentController@destroyTrash', 'method' => 'POST']) }}
                    <div class="box box-white">
                        <div class="trash-buttons">
                            <div class="pull-left">
                                <a href="#" class="btn btn-primary select-all-checkboxes">Alle auswählen</a>
                                <a href="#" class="btn btn-primary unselect-all-checkboxes">Alle abwählen</a>
                            </div>
                            <div class="pull-right">
                                <button type="submit" value="1" name="restore" class="btn btn-success">Wiederherstellen</button>
                                <button type="submit" value="1" name="delete" class="btn btn-danger delete-prompt" 
                                    data-text="{{trans('documentForm.warningDeleteTrash')}}">Löschen</button>
                                {{--
                                <button type="submit" value="1" name="empty-trash" class="btn btn-danger delete-prompt" 
                                    data-text="{{trans('documentForm.warningEmptyTrash')}}">Papierkorb Leeren</button>
                                --}}
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        
                        <div class="box">
                        @foreach($trashedDocuments as $document)
                            <div class="row">
                                <div class="col-xs-12 item-trash">
                                    <span class="checkbox no-margin-top no-margin-bottom btn-block pull-left">
                                        <input type="checkbox" name="documentIds[]" value="{{ $document->id }}" id="delete-{{ $document->id }}">
                                        <label for="delete-{{ $document->id }}"></label>
                                    </span>
                                    <span class="delete-details btn-block">
                                        Version {{ $document->version }},
                                        {{ $document->documentStatus->name }} -
                                        {{ Carbon\Carbon::parse($document->date_published)->format('d.m.Y') }} 
                                        @if(isset($document->owner)) - {{ $document->owner->first_name .' '. $document->owner->last_name }} @endif <br>
                                        
                                        @if( ($document->pdf_upload == 1) || (isset($document->documentType) && $document->documentType->document_art == 1) )
                                            @if($document->propAttachment)
                                                <a href="{{url('download/'. $document->id .'/'. $document->propAttachment)}}" target="_blank">
                                                    <strong>{{ $document->name }}</strong>
                                                </a>
                                            @else
                                                <strong>{{ $document->name }}</strong>
                                            @endif
                                            <br>
                                        @else    
                                            <a href="{{url('/papierkorb/download/' . $document->id)}}" target="_blank"> <strong>{{ $document->name }}</strong></a> <br>
                                        @endif
                                        
                                        @if(isset($document->documentType))
                                            {{ $document->documentType->name }}
                                        @endif
                                        <br>
                                    </span>
                                </div>
                            </div>
                        @endforeach
                        </div>
                        
                    </div>
                {{ Form::close() }}
                
            </div>
        </div>
    
    </div><!-- end .row -->
@else
    <p>Der Papierkorb ist leer.</p>
@endif

@stop