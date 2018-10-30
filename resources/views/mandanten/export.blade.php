{{-- MANDANTEN EXPORT --}}

@extends('master')

@section('page-title')Mandantenverwaltung - Mandanten Export @stop

@section('content')


{{ Form::open(['action' => 'TelephoneListController@xlsExport', 'method' => 'POST']) }}
<div class="box-wrapper col-sm-6">
    
    <div class="box box-white">

        <div class="row">
            
            <div class="col-xs-12">
                <div class="form-inline">
                    <label>{{ trans('telefonListeForm.mandants') }}</label>
                    <select name="export-mandants[]" data-placeholder="{{ trans('telefonListeForm.mandants') }}" class="form-control select" multiple required>
                        <option></option>
                        <option value="0" selected>Alle</option>
                        @foreach($mandants as $mandant)
                        <option value="{{$mandant->id}}">{{$mandant->mandant_number}} - {{$mandant->kurzname}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            <div class="clearfix"></div><br>
            
            <div class="col-xs-12">
                <div class="form-inline">
                    <label>{{ trans('telefonListeForm.options') }}</label>
                    <select name="export-option" data-placeholder="{{ trans('telefonListeForm.options') }}" class="form-control select" required>
                        <option></option>
                        <option value="1">Option 1 - Partner Gesamt</option>
                        <option value="2">Option 2 - Einteilung Mandanten - Neptun-Mitarbeiter</option>
                        <option value="3">Option 3 - Adressliste Mandanten-Gesamt</option>
                        <option value="4">Option 4 - Partner Gesamt</option>
                        <option value="5">Option 5 - Zeitarbeits-Partner</option>
                        <option value="6">Option 6 - Bankverbindungen</option>
                    </select>
                </div>
            </div>
            
            <div class="clearfix"></div><br>
            
            <div class="col-xs-12">
                <button type="submit" class="btn btn-primary no-margin-bottom">{{ trans('telefonListeForm.export') }}</button>
            </div>
            
        </div>
        
    </div>
    
</div>

    

{{ Form::close() }}

<div class="clearfix"></div> <br>

@stop
