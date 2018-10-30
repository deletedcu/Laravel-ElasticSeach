{{-- WIKI MANAGEMENT --}}

@extends('master')

@section('page-title') {{ trans('wiki.managment') }} @stop

@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="col-xs-12 box-wrapper ">
            
            <h2 class="title">{{ trans('wiki.search') }}</h2>
            
            <div class="box box-white">
                    {!! Form::open(['action' => 'WikiController@searchManagment', 'method'=>'POST']) !!}
                         @if( isset($admin) && $admin == true) 
                             <input type="hidden" name="admin" value="1" />
                         @else 
                             <input type="hidden" name="admin" value="0" />
                         @endif
                        <div class="wiki-managment-search row">
                            <div class="col-md-3 mb-20">
                                {!! ViewHelper::setInput('name', $data,old('name'), trans('wiki.name') ) !!}
                            </div>
                            <!--<div class="col-md-3 mb-20">-->
                            <!--    {!! ViewHelper::setInput('subject',  $data,old('subject'), trans('wiki.subject') ) !!}-->
                            <!--</div>-->
                          
                            <div class="col-md-3 mb-20">
                                {!! ViewHelper::setInput('date_from',  $data,old('date_from'), trans('wiki.dateFrom'), trans('wiki.dateFrom') 
                                , false, 'text' , ['datetimepicker']  ) !!}
                            </div>
                            <div class="col-md-3 mb-20">
                                {!! ViewHelper::setInput('date_to', $data,old('date_to'), trans('wiki.dateTo'), trans('wiki.dateTo'), 
                                false, 'text' , ['datetimepicker']  ) !!}
                                
                            </div>
                            <div class="col-md-3 mb-20">
                                 {!! ViewHelper::setSelect($categories,'category',$data,old('category'), trans('wiki.category'),
                                 trans('wiki.category'), false, array(), array(), array(), true) !!}
                               
                            </div>
                           
                            <div class="col-md-3 mb-20">
                                 {!! ViewHelper::setSelect($statuses,'status',$data,old('status'),trans('wiki.status'),
                                 trans('wiki.status'), false, array(), array(), array(), true) !!}
                              
                            </div>
                            @if( isset($admin) && $admin == true) 
                                <div class="col-md-3 mb-20">
                                    {!! ViewHelper::setUserSelect($wikiUsers,'ersteller',$data,old('ersteller'),trans('wiki.user'),
                                    trans('wiki.user'), false, array(), array(), array(), true) !!}
                            
                                </div>
                            @endif
                            <div class="col-md-3 col-lg-3 mb-20">
                                <span class="custom-input-group-btn">
                                    <button type="submit" class="btn btn-primary no-margin-bottom">
                                        {{ trans('navigation.search') }} 
                                    </button>
                                </span>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    {!! Form::close() !!}
            </div><!-- end box -->
        </div><!-- end box wrapper-->
        
        <!-- top categorie box-->
        <div class="col-xs-12 box-wrapper">
            <h2 class="title">{{ trans('adressatenForm.overview') }}</h2>
            
            <div class="box box-white">
                 <table class="table data-table box-white">
                    <thead>
                        <th  class="text-center valign">Name</th>
                        <th  class="text-center valign">Kategorie</th>
                        <th class="text-center valign">Status</th>
                        <th class="text-center valign no-sort">Ersteller</th>
                        <th class="text-center valign no-sort">Datum</th>
                        <th class="text-center valign no-sort">Optionen</th>
                    </thead>
                    <tbody>
                        @if(count($wikies) > 0)
                            @foreach($wikies as $k => $data)
                            <tr>
                                    
                                    <td class="text-center valign"><a href="/wiki/{{$data->id}}">{{ $data->name }}</a> </td>
                                    <td class="text-center valign ">
                                        {{ $data->category->name }}
                                    </td>
                                    <td class="text-center valign ">
                                        {{ $data->status->name }}
                                    </td>
                                    <td class="text-center valign"> 
                                        {{ $data->user->first_name }} {{ $data->user->last_name }}
                                    </td>
                                    <td class="text-center valign"> 
                                        {{ $data->created_at }}
                                    </td>
                                    <td class="valign table-options text-center">
                                        <a href="/wiki/{{$data->id}}/edit" class="btn btn-xs btn-primary">Bearbeiten</a><br>
                                    </td>
                                </tr>
                                
                            @endforeach
                        @else
                            <tr>
                                <td class"valign"></td>
                                <td class"valign"></td>
                                <td class"valign"></td>
                                <td class"valign">Keine Daten vorhanden</td>
                                <td class"valign"></td>
                                <td class"valign"></td>
                            </tr>
                        @endif
                    
                    </tbody>
                </table>
            </div><!-- end box -->
             
        </div><!--end  top categorie box wrapper-->
    </div><!-- end .col-xs-12 -->    
</div><!-- end main row-->




@stop
