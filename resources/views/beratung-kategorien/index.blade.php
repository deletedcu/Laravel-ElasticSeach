{{-- Juristen kategor KATEGORIEN --}}

@extends('master')

@section('page-title') NEPTUN-Verwaltung - {{ ( trans('navigation.juristenPortalBeratung') ) }}-{{ trans('navigation.kategorien') }} @stop

@section('content')

<fieldset class="form-group">
    <div class="box-wrapper">
        <h4 class="title">{{ ( trans('navigation.juristenPortalBeratung') ) }}-{{ trans('navigation.kategorien') }} {{ trans('isoKategorienForm.add') }} </h4>
        <div class="box box-white">
      
            <!-- input box-->
            
            {!! Form::open(['route' => 'beratung-kategorien.store']) !!}
                <div class="row">
                    <div class="col-md-6 col-lg-4"> 
                        <div class="form-group">
                            {!! ViewHelper::setInput('name', '', old('name'), trans('isoKategorienForm.name'), trans('isoKategorienForm.name'), true) !!} 
                         </div> 
                    </div>
                    <div class="col-md-6 col-lg-4"> 
                        <div class="checkbox">
                            <input class="hide-input" id="hide-input" data-hide-target="iso-categories" data-disable-target="iso-categories" type="checkbox" name="parent" />
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
                                @foreach($juristenCategories as $jueristenCategory)
                                    @if($jueristenCategory->parent)
                                         <option value="{{ $jueristenCategory->id }}"> {{ $jueristenCategory->name }} </option>
                                        @if( count( $jueristenCategory->juristCategoriesBeratung ) )
                                            @foreach( $jueristenCategory->juristCategoriesBeratung as $subLevel1)
                                                <option  class="jurist-subcategory-option-level-one" 
                                                value="{{ $subLevel1->id }}"> {{ $subLevel1->name }} 
                                                </option>
                                              
                                            @endforeach
                                        @endif
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
                <h4 class="title">{{ trans('juristenPortal.overview') }}</h4>
                <div class="box box-white">
                    <table class="table table-responsive">
                        <thead>
                            <tr>
                                <th class="col-xs-5 vertical-center">
                                    {{ trans('juristenPortal.categories') }}
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($juristenCategories as $jueristenCategory)
                      
                        {!! Form::open(['route' => ['beratung-kategorien.update', 'rechtsablage' => $jueristenCategory->id], 'method' => 'PATCH']) !!}
                         <tr>
                            <td class="col-xs-5 vertical-center">
                                 <input type="text" class="form-control" name="name" placeholder="Name" value="{{ $jueristenCategory->name }}" required/>
                            </td>
                            <td class="col-xs-4 vertical-center">
                                @if( $jueristenCategory->parent && count($jueristenCategory->juristCategoriesBeratung)  )
                                <p>@lang('isoKategorienForm.parent-category')</p>
                                @else 
                                <select name="category_id" class="form-control select" data-placeholder="{{ trans('isoKategorienForm.parent-category-select') }}">
                                    <option value="parent" @if($jueristenCategory->parent) selected @endif>
                                        @lang('isoKategorienForm.parent-category')
                                    </option>
                                    @foreach($juristenCategories as $jueristenCategoryChild)
                                        @if($jueristenCategoryChild->parent)
                                            <option value="{{ $jueristenCategoryChild->id }}" @if($jueristenCategory->jurist_category_parent_id == $jueristenCategoryChild->id) selected @endif > {{ $jueristenCategoryChild->name }} </option>
                                       
                                            @if( count( $jueristenCategoryChild->juristCategoriesBeratung ) )
                                                @foreach( $jueristenCategoryChild->juristCategoriesBeratung as $subLevel1)
                                                    <option  class="jurist-subcategory-option-level-one" 
                                                    @if($jueristenCategory->jurist_category_parent_id == $subLevel1->id) selected @endif
                                                    value="{{ $subLevel1->id }}"> {{ $subLevel1->name }} 
                                                    </option>
                                                   
                                                @endforeach
                                            @endif
                                        @endif
                                    @endforeach
                                </select>
                                @endif 
                            </td>
                            
                            <td class=" text-right table-options">
        
                                @if($jueristenCategory->active)
                                <button class="btn btn-success" type="submit" name="activate" value="1">{{ trans('adressatenForm.active') }}</button>
                                @else
                                <button class="btn btn-danger" type="submit" name="activate" value="0">{{ trans('adressatenForm.inactive') }}</button>
                                @endif
                                
                                <button class="btn btn-primary" type="submit">{{ trans('adressatenForm.save') }}</button>
                               {!! Form::close() !!} 
                               
                               @if( count($jueristenCategory->isJuristCategoryBeratungParent) < 1 && count($jueristenCategory->hasAllDocuments) < 1  )
                                {!! Form::open(array('route' => array('beratung-kategorien.destroy', $jueristenCategory->id), 'method' => 'delete')) !!}
                                        <button  type="submit" href="" class="btn btn-danger delete-prompt"
                                         data-text="Wollen Sie diesen Kategorie wirklich löschen?">
                                             löschen
                                         </button> 
                                     </form>
                                @endif     
                            </td>
                            
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
</fieldset>

<div class="clearfix"></div> <br>

@stop
