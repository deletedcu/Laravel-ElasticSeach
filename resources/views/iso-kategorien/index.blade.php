{{-- ISO KATEGORIEN --}}

@extends('master')

@section('page-title') NEPTUN-Verwaltung - ISO-Kategorien @stop

@section('content')

<fieldset class="form-group">
    <div class="box-wrapper">
        <h4 class="title">ISO-{{ trans('isoKategorienForm.category') }} {{ trans('isoKategorienForm.add') }} </h4>
        <div class="box box-white">
      
            <!-- input box-->
            
            {!! Form::open(['route' => 'iso-kategorien.store']) !!}
                <div class="row">
                    <div class="col-md-6 col-lg-4"> 
                        <div class="form-group">
                            {!! ViewHelper::setInput('name', '', old('name'), trans('isoKategorienForm.name'), trans('isoKategorienForm.name'), true) !!} 
                         </div> 
                    </div>
                    <div class="col-md-6 col-lg-4"> 
                        <div class="checkbox">
                            <input class="hide-input" id="hide-input" data-hide-target="iso-categories" data-disable-target="iso-categories" type="checkbox" name="parent"/>
                            <label for="hide-input">{{ trans('isoKategorienForm.parent-category') }}</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group no-margin-bottom" data-hide="iso-categories">
                         <div class="col-md-6 col-lg-4"> 
                            <label>{{ trans('isoKategorienForm.parent-category') }}</label>
                           
                            <select name="category_id" class="form-control select" data-disable="iso-categories" data-placeholder="{{ trans('isoKategorienForm.parent-category-select') }}">
                                 <option value=""></option>
                                 @foreach($isoCategories as $isoCategory)
                                     @if($isoCategory->parent)
                                         <option value="{{ $isoCategory->id }}"> {{ $isoCategory->name }} </option>
                                     @endif
                                 @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-2 custom-input-group-btn"> 
                       <button class="btn btn-primary no-margin-bottom">{{ trans('isoKategorienForm.add') }} </button>
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
                <h4 class="title">{{ trans('isoKategorienForm.overview') }}</h4>
                <div class="box box-white">
                    <table class="table">
                        <tr>
                            <th colspan="3">
                                {{ trans('isoKategorienForm.categories') }}
                            </th>
                        </tr>
                        @foreach($isoCategories as $isoCategory)
                      
                        {!! Form::open(['route' => ['iso-kategorien.update', 'iso-kategorien' => $isoCategory->id], 'method' => 'PATCH']) !!}
                         <tr>
                            <td class="col-xs-5 vertical-center">
                                 <input type="text" class="form-control" name="name" placeholder="Name" value="{{ $isoCategory->name }}" required/>
                            </td>
                            <td class="col-xs-4 vertical-center position-relative">
                                @if($isoCategory->parent)
                                   <p>{{ trans('isoKategorienForm.parent-category') }}</p>
                                @else
                                 <select name="category_id" class="form-control select" data-placeholder="{{ trans('isoKategorienForm.parent-category-select') }}">
                                     <option value=""></option>
                                     @foreach($isoCategories as $isoCategoryChild)
                                         @if($isoCategoryChild->parent)
                                             <option value="{{ $isoCategoryChild->id }}" @if($isoCategory->iso_category_parent_id == $isoCategoryChild->id) selected @endif > {{ $isoCategoryChild->name }} </option>
                                         @endif
                                     @endforeach
                                 </select>
                                @endif
                            </td>
                            <td class="col-xs-3 text-right table-options">
        
                                @if($isoCategory->active)
                                <button class="btn btn-success" type="submit" name="activate" value="1">{{ trans('adressatenForm.active') }}</button>
                                @else
                                <button class="btn btn-danger" type="submit" name="activate" value="0">{{ trans('adressatenForm.inactive') }}</button>
                                @endif
                                
                                <button class="btn btn-primary" type="submit">{{ trans('adressatenForm.save') }}</button>
                               {!! Form::close() !!} 
                               
                               @if( count($isoCategory->isIsoCategoryParent) < 1 && count($isoCategory->hasAllDocuments) < 1  )
                                {!! Form::open([
                                   'url' => 'iso-dokumente/delete/'.$isoCategory->id,
                                   'method' => 'POST',
                                   'class' => 'horizontal-form',]) !!}
                                        <button  type="submit" href="" class="btn btn-danger delete-prompt"
                                         data-text="Wollen Sie diesen kategorie wirklich löschen?">
                                             löschen
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
