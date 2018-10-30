@extends('master')

@section('page-title') {{ trans('juristenPortal.createNotes') }} @stop

@section('content')


<div class="col-md-12 box-wrapper"> 
    <div class="box">
        <div class="row">
             @if( Request::is('*/edit') )
                  {!! Form::open([
                   'url' => route('notiz.update', $data->id) ,
                   'method' => 'patch',
                   'enctype' => 'multipart/form-data',
                   'class' => 'horizontal-form']) !!}
             @else
                 {!! Form::open(['route' => 'notiz.store', 'method' => 'POST', 'class' => 'horizontal-form',
                   'enctype' => 'multipart/form-data', ]) !!} 
             @endif
             
            
            <!-- row 1-->
            <div class="col-md-4 col-lg-3 "> 
                <div class="form-group">
                    <label class="control-label">{{ trans('juristenPortal.mandant') }}</label>
                    {!! ViewHelper::setSelect($mandants,'mandant_id', $data, old('mandant_id'),'', trans('juristenPortal.mandant'), true ) !!}
                </div>
            </div>
            
            
            <div class="col-md-4 col-lg-3"> 
                <div class="form-group">
                    <label class="control-label">{{ trans('juristenPortal.mitarbeiter') }}</label>
                    <select name="mitarbeiter_id" id="mitarbeiter_id" class="form-control select empty-select" data-placeholder="Mitarbeiter">
                        <option value="" data-position="" select >&nbsp;</option>
                        @foreach($mitarbeiterUsers as $mitarbeiterUser)
                            <option value="{{$mitarbeiterUser->id}}" data-position="{{$mitarbeiterUser->position}}">
                                {{ $mitarbeiterUser->last_name }} {{ $mitarbeiterUser->first_name }}  
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            <div class="col-md-4 col-lg-3">
                <div class="form-group">
                    {!! ViewHelper::setInput('client', $data, old('client'), trans('juristenPortal.mitarbeiterName'), trans('juristenPortal.mitarbeiterName'), false, '', array(''), array('id=mitarbeiter')) !!}
                </div>
            </div>
            
            <div class="col-md-4 col-lg-3">
                <div class="form-group">
                   {!! ViewHelper::setInput('note_date', $data, old('note_date'), trans('juristenPortal.date'), trans('juristenPortal.date'), true, 'text', ['datetimepicker']) !!}
                </div>
            </div>
            
            
            <!-- row 2-->
            <div class="col-md-4 col-lg-3"> 
                <div class="form-group ">
                      {!! ViewHelper::setInput('telefon', $data, old('telefon'), trans('juristenPortal.phone'), trans('juristenPortal.phone'), false) !!}
                </div>   
            </div>
            
            <div class="col-md-4 col-lg-3"> 
                <div class="form-group">
                    {!! ViewHelper::setInput('funktion', $data, old('funktion'), trans('juristenPortal.function'), trans('juristenPortal.function'), false, '', array(''), array('id=function')) !!}
                </div>   
            </div><!--End input box-->
            
            <div class="col-md-4 col-lg-3">
                <div class="form-group">
                   {!! ViewHelper::setInput('note_time', $data, old('note_time'), trans('juristenPortal.time'), trans('juristenPortal.time'), true, 'text', ['timepicker']) !!}
                </div>
            </div>
            
            <!-- row 3-->
          
              <div class="col-md-4 col-lg-3"> 
                <div class="form-group">
                    {!! ViewHelper::setInput('nachricht', $data, old('nachricht'), trans('juristenPortal.nachricht'), trans('juristenPortal.nachricht'), false) !!}
                </div>   
            </div>
            
            <!-- text editor-->
            
          
            <div class="col-md-4 col-lg-3"> 
                <div class="form-group">
                    {!! ViewHelper::setInput('betreff', $data, old('betreff'), trans('juristenPortal.betreff'), trans('juristenPortal.betreff'), true) !!}
                </div>   
            </div>
            
            <div class="col-md-4 col-lg-3"> 
                <div class="form-group">
                    <div class="checkbox">
                        {!! ViewHelper::setCheckbox('ruckruf', $data, old('ruckruf'), trans('juristenPortal.recall')) !!}
                    </div>
                </div>   
            </div>
            
            <div class="clearfix"></div>
                
            <div class="col-xs-12">
                <div class="variant" data-id='inhalt'>
                    @if( isset($data->editorVariant) )
                        {!! $data->editorVariant->first()->inhalt !!}
                    @endif
                </div>
                
            </div>
            <div class="clearfix"></div>
            <br/>
            
            <!-- input box-->
            <div class="col-lg-6"> 
                <div class="form-group">
                    <input type="file" name="file[]" class="form-control" multiple />
                </div>   
            </div><!--End input box-->
            @if( isset($data->documentUploads) && $data->documentUploads->count() > 0 )
                <div class="col-lg-6 "> 
                <span class="lead"> Hochgeladene Dateien</span>
                @foreach($data->documentUploads as $doc)
                    <p class="text-info"><span class="fa fa-file-o"></span> 
                    <a href="{{ url('download/'. $data->id .'/'.$doc->file_path) }}">{{ $doc->file_path }}</a></p>
                @endforeach
              
                </div><!--End input box-->
            @endif
        

            
        </div>

    </div>
    
    <br/>
    <div class="clearfix"></div>
    <div class="row">
        <div class="col-xs-12 form-buttons">
            {{ Form::submit(trans('documentForm.saveContinue'), array('class' => 'btn btn-primary no-margin-bottom')) }}
        </div>
    </div>
    <div class="clearfix"></div> 
@stop

@section('script')
<script>
$('#mitarbeiter_id').change(function(event) {
    var selected = $(this).find('option:selected');
    var name = $.trim(selected.text());
    var position = selected.data('position');
    $('#mitarbeiter').val(name);
    $('#function').val(position);
});
</script>



@stop