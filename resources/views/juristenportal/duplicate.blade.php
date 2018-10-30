@extends('master')
    @section('page-title')Dublettenprüfung @stop
    @section('content')
        <div class="col-xs-12 box-wrapper">
            <h3 class="title">{{ trans('juristenPortal.upload') }} // Duplicate Check</h3>

            <div class="box">
                <div class="row">
                    <div class="col-lg-12">
                        Folgende Dubletten wurden gefunden:
                    </div>
                </div>
            </div>
                        
            @foreach ($duplicates as $duplicate)
            <div class="box">
                <div class="row">
                    <div class="col-lg-6">
                        <h3>Neues Dokument</h3>
                              <li>File Name: {{$duplicate['metadata']['FileName'] or 'Unknown'}}</li>
                            <li>Create Date: {{$duplicate['metadata']['CreateDate'] or 'Unknown'}}</li>
                            <li>Version: {{$duplicate['metadata']['RevisionNumber'] or 'Unknown'}}</li>
                            <li>Title: {{$duplicate['metadata']['Title']  or 'Unknown'}}</li>
                            <li>Creator: {{$duplicate['metadata']['Creator'] or 'Unknown'}}</li>
                            <li>Keywords: {{$duplicate['metadata']['Keywords'] or 'Unknown'}}</li>
                            <li>Description: {{$duplicate['metadata']['Description'] or 'Unknown'}}</li>


                    </div>
                    <div class="col-lg-6">
                        <h3>Vorhandenes Dokument</h3>
                            <ul>
                            <li>File Name: {{$duplicate['original']->name or 'Unknown'}}</li>
                            <li>Create Date: {{$duplicate['original']['created_at'] or 'Unknown'}}</li>
                            <li>Version: {{$duplicate['original']->version or 'Unknown'}}</li>
                            <li>Title: {{$duplicate['metadata']['Title']  or 'Unknown'}}</li>
                            <li>Creator: {{$duplicate['original']->user->first_name}} {{$duplicate['original']->user->last_name}}</li>
                            <li>Keywords: {{$duplicate['original']->search_tags or 'Unknown'}}</li>
                            <li>Description: {{$duplicate['original']->summary or 'Unknown'}}</li>
                        </ul>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
               {!! Form::open([
                   'url' => route('juristenportal.duplicate'),
                   'method' => 'POST',
                   'class' => 'horizontal-form'
                   ]) 
               !!}
                        <input type="hidden" name="document" value="{{$duplicate['metadata']['FileName'] or 'Unknown'}}" />
                        <input type="hidden" name="originalID" value="{{$duplicate['original']->id}}" />
                        <button type="submit" name="action" value="keep" class="btn btn-primary">Keep Both</button>
                        <button type="submit" name="action" value="update" class="btn btn-primary">Update existing (new version)</button>
                        <button type="submit" name="action" value="delete" class="btn btn-primary">Delete duplicate</button>
                    {{ Form::close() }}

                    </div>
                </div>
            </div>
            @endforeach
                        
        </div><!--end .row -->
    @endsection


