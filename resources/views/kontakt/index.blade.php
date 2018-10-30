{{-- CONTACT MESSAGES --}}

@extends('master')

@section('page-title') {{ trans('contactForm.verwaltung') }} - {{ trans('contactForm.kontaktanfragen') }} @stop

@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="col-xs-12 box-wrapper ">
            
            <h2 class="title">{{ trans('contactForm.searchTitle') }} {{ trans('contactForm.kontaktanfragen') }}</h2>
            
            <div class="box box-white">
                {!! Form::open(['action' => 'HomeController@contactSearch', 'method'=>'GET']) !!}
                    <div class="contact-message-search row">
                        
                        <div class="col-md-3">
                            <select name="user_id" class="form-control select" data-placeholder="{{trans('contactForm.mitarbeiter')}}*" required >
                                <option></option>
                                @foreach($usersAll as $user)
                                    <option value="{{$user->id}}" @if($user->id == $userId) selected @endif >
                                       {{$user->last_name}} {{$user->first_name}} 
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="clearfix"></div> <br>
                        
                        <div class="col-md-3 col-lg-3">
                            <button type="submit" class="btn btn-primary no-margin-bottom">
                                {{ trans('contactForm.search') }} 
                            </button>
                            @if(isset($userId))
                            <a href="{{url('kontaktanfragen')}}" class="btn btn-primary no-margin-bottom">{{ trans('contactForm.reset') }}</a>
                            @endif
                            
                        </div>
                        
                    </div>
                {!! Form::close() !!}
            </div>
            
        </div>
        
        <!-- top categorie box-->
        <div class="col-xs-12 box-wrapper">
            <h2 class="title">{{ trans('contactForm.overview') }}</h2>
            <div class="box box-white">
                 <table class="table data-table box-white">
                     <!--Datum/Sender/Empfänger/Betreff/Mailinhalt-->
                    <thead>
                        <th class="text-center valign no-sort">{{ trans('contactForm.datum') }}</th>
                        <th class="text-center valign">{{ trans('contactForm.sender') }}</th>
                        <th class="text-center valign">{{ trans('contactForm.reciever') }}</th>
                        <th class="text-center valign">{{ trans('contactForm.subject') }}</th>
                        <th class="text-center valign no-sort">{{ trans('contactForm.options') }}</th>
                    </thead>
                    <tbody>
                        @if(count($messagesAllPaginated) > 0)
                            @foreach($messagesAllPaginated as $k => $data)
                                <tr>
                                    <td class="text-center valign"> 
                                        {{ $data->created_at->format('d.m.Y H:i:s') }}
                                    </td>
                                    <td class="text-center valign"> 
                                        {{ $data->userFrom->first_name }} {{ $data->userFrom->last_name }}
                                    </td>
                                    <td class="text-center valign"> 
                                        {{ $data->user->first_name }} {{ $data->user->last_name }}
                                    </td>
                                    <td class="text-center valign ">
                                        {{ $data->title }}
                                    </td>
                                    <td class="valign table-options text-center">
                                        <a href="#" data-toggle="modal" data-target="#details{{$data->id}}" class="btn btn-xs btn-primary">
                                            {{trans('contactForm.preview')}}
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td class"valign"></td>
                                <td class"valign"></td>
                                <td class"valign">Keine Daten vorhanden</td>
                                <td class"valign"></td>
                                <td class"valign"></td>
                            </tr>
                        @endif
                    
                    </tbody>
                </table>
                
                <div class="text-center">
                    {{ $messagesAllPaginated->appends(['user_id' => Request::get('user_id')])->render() }}
                </div>
                
            </div><!-- end box -->
             
        </div><!-- end top categorie box wrapper -->
    </div><!-- end .col-xs-12 -->
</div><!-- end main row -->

{{-- Message details modals --}}
@foreach($messagesAllPaginated as $message)
    <div class="modal fade contactMessage" id="details{{$message->id}}" tabindex="-1" role="dialog"
         aria-labelledby="details{{$message->id}}" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><span
                                class="fa fa-close"></span></button>
                    <h4 class="modal-title">{{trans('contactForm.preview')}}: {{ $message->title }}</h4>
                </div>

                <div class="modal-body">
                    {{ $message->message }}
                </div>
                
                @if(count($message->files) > 0)
                    <div class="modal-footer">
                        <div class="pull-left text-left">
                            <strong>{{trans('contactForm.attachments')}}</strong> <br>
                            @foreach($message->files as $attachment)
                                <a href="{{url('/files/contacts/'. $message->user_id .'/'. $attachment->filename)}}">{{ $attachment->filename }}</a> <br>
                            @endforeach
                        </div>
                    </div>
                @endif
                
            </div>
        </div>
    </div>
@endforeach

@stop
