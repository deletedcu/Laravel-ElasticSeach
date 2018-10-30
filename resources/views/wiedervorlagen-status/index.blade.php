{{-- ISO KATEGORIEN --}}

@extends('master')

@section('page-title') {{ trans('contactForm.verwaltung') }} - {{ trans('wiedervorlagenStatus.wiedervorlagenStatus') }} @stop

@section('content')

<fieldset class="form-group">
    <div class="box-wrapper">
        <h4 class="title">{{ trans('wiedervorlagenStatus.wiedervorlagenStatus') }} {{ trans('wiedervorlagenStatus.add') }} </h4>
        <div class="box box-white">
      
            <!-- input box-->
            
            {!! Form::open(['route' => 'wiedervorlagen-status.store']) !!}
                <div class="row">
                    <div class="col-md-6 col-lg-4"> 
                        <div class="form-group">
                            {!! ViewHelper::setInput('name', '', old('name'), trans('wiedervorlagenStatus.name'), trans('wiedervorlagenStatus.name'), true, '', array(''), array('id=new_name') ) !!} 
                         </div> 
                    </div>
                    <div class="col-md-3 col-lg-2"> 
                        <div class="form-group">
                            {!! ViewHelper::setInput('color', '', old('color'), trans('wiedervorlagenStatus.text-color-code'), trans('wiedervorlagenStatus.text-color-code'), true, '', array('colorpicker'), array('id=new_color') ) !!}
                         </div> 
                    </div>
                    <div class="col-md-3 col-lg-2"> 
                        <div class="form-group">
                            {!! ViewHelper::setInput('bgcolor', '', old('bgcolor'), trans('wiedervorlagenStatus.bg-color-code'), trans('wiedervorlagenStatus.bg-color-code'), true, '', array('colorpicker'), array('id=new_bgcolor') ) !!}
                         </div> 
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 col-lg-2 custom-input-group-btn"> 
                       <button class="btn btn-primary no-margin-bottom">{{ trans('wiedervorlagenStatus.add') }} </button>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
</fieldset>


<fieldset class="form-group">
    
     
    <div class="box-wrapper">
        <div class="row">
            <div class="col-md-12">
                <h4 class="title">{{ trans('wiedervorlagenStatus.overview') }}</h4>
                <div class="box box-white">
                    <table class="table">
                        <tr>
                            <th>
                                {{ trans('wiedervorlagenStatus.name') }}
                            </th>
                            <th>{{ trans('wiedervorlagenStatus.text-color-code') }}</th>
                            <th>{{ trans('wiedervorlagenStatus.bg-color-code') }}</th>
                        </tr>
                        @foreach($wiedervorlagenStatuss as $wiedervorlagenStatus)
                      
                        {!! Form::open(['route' => ['wiedervorlagen-status.update', 'wiedervorlagen-status' => $wiedervorlagenStatus->id], 'method' => 'PATCH']) !!}
                         <tr>
                            <td class="col-xs-4 vertical-center">
                                 <input type="text" class="form-control" name="name" id="{{ 'name' . $wiedervorlagenStatus->id  }}" placeholder="Name" value="{{ $wiedervorlagenStatus->name }}" required/>
                            </td>
                            <td class="col-xs-2 vertical-center position-relative">
                                <input type="text" class="form-control colorpicker" id="{{ 'color' . $wiedervorlagenStatus->id  }}" onchange="colorChanger({{$wiedervorlagenStatus->id}})" name="color" placeholder="{{ trans('wiedervorlagenStatus.text-color-code') }}" value="{{ $wiedervorlagenStatus->color }}" required/>
                            </td>
                            <td class="col-xs-2 vertical-center position-relative">
                                <input type="text" class="form-control colorpicker" id="{{ 'bgcolor' . $wiedervorlagenStatus->id  }}" onchange="colorChanger({{$wiedervorlagenStatus->id}})" name="bgcolor" placeholder="{{ trans('wiedervorlagenStatus.bg-color-code') }}" value="{{ $wiedervorlagenStatus->bgcolor }}" required/>
                            </td>
                            <td class="col-xs-4 text-right table-options">
        
                                @if($wiedervorlagenStatus->active)
                                <button class="btn btn-success" type="submit" name="activate" value="1">{{ trans('adressatenForm.active') }}</button>
                                @else
                                <button class="btn btn-danger" type="submit" name="activate" value="0">{{ trans('adressatenForm.inactive') }}</button>
                                @endif
                                
                                <button class="btn btn-primary" type="submit">{{ trans('adressatenForm.save') }}</button>
                               {!! Form::close() !!} 
                               
                               @if( count($wiedervorlagenStatus->hasAllDocuments) < 1  )
                                {!! Form::open([
                                   'url' => 'wiedervorlagen-status/'.$wiedervorlagenStatus->id,
                                   'method' => 'DELETE',
                                   'class' => 'horizontal-form',]) !!}
                                        <button  type="submit" href="" class="btn btn-danger delete-prompt"
                                         data-text="{{ trans('wiedervorlagenStatus.question-delete') }}">
                                             {{ trans('adressatenForm.delete') }}
                                         </button> 
                                     </form>
                                @endif     
                            </td>
                            
                        </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
    
</fieldset>

<div class="clearfix"></div> <br>

@stop

@section('script')
<script>
function colorChanger(t) {
    var color = $('#color' + t ).val();
    var bgcolor = $('#bgcolor' + t ).val();
    $('#name' + t ).attr('style', 'color:'+ color + '!important; background-color:' + bgcolor + '!important;');
}

$('#new_color').change(function(event) {
    var color = $('#new_color').val();
    var bgcolor = $('#new_bgcolor').val();
    $('#new_name').attr('style', 'color:'+ color + '!important; background-color:' + bgcolor + '!important;');
});

$('#new_bgcolor').change(function(event) {
    var color = $('#new_color').val();
    var bgcolor = $('#new_bgcolor').val();
    $('#new_name').attr('style', 'color:'+ color + '!important; background-color:' + bgcolor + '!important;');
});

</script>



@stop
