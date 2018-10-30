{{-- Invetarliste Abrechnen --}}

@extends('master')

@section('page-title') {{ trans('navigation.inventoryAbrechnen') }} @stop

@section('content')

<!--search row-->
<div class="row">
    <div class="col-sm-12 ">
        <div class="box-wrapper">
            <h2 class="title"> @lang('inventoryList.searchDeduct')</h2>
            <div class="box  box-white">
                <div class="row">
                @if(Request::is('*abrechnen-abgerechnt*') )    
                    {!! Form::open(['url' => 'inventarliste/suche-abrechnen-abgerechnt', 'method'=>'POST']) !!}
                @elseif(Request::is('*abrechnen-alle*') )    
                    {!! Form::open(['url' => 'inventarliste/suche-abrechnen-alle', 'method'=>'POST']) !!}
                @else    
                    {!! Form::open(['url' => 'inventarliste/suche-abrechnen', 'method'=>'POST']) !!}
                @endif
                      
                            <div class="col-xs-12 col-md-8 col-lg-4">
                                <select name="search" class="form-control select" width="120px!important;" data-placeholder="{{ strtoupper(trans('inventoryList.search').' '.trans('inventoryList.searchTextOptions')) }}" required>
                                    <option></option>
                                    @foreach($searchSuggestions as $suggestion)
                                        <option @if(isset($searchInput) && ($searchInput == $suggestion)) selected @endif value="{{$suggestion}}">
                                            {{$suggestion}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-12 col-lg-12">
                                <span class="custom-input-group-btn">
                                    <button type="submit" class="btn btn-primary no-margin-bottom">
                                        {{ trans('navigation.search') }} 
                                    </button>
                                </span>
                            </div>
                    {!! Form::close() !!}
                </div>
            </div><!-- end box -->
        </div><!-- end box wrapper-->
    </div>
</div><!-- end search row -->

<div class="row">
    <div class="col-sm-12 ">
        <div class="box-wrapper">
            <div class="box  box-white">
                <div class="row">
                    <div class="col-xs-6">
                        <span class="custom-input-group-btn">
                            
                            <a href="{{ url('inventarliste/abrechnen')}}" class="btn btn-primary no-margin-bottom">
                                {{ trans('inventoryList.open') }} 
                            </a>
                            
                            <a href="{{ url('inventarliste/abrechnen-abgerechnt')}}" class="btn btn-primary no-margin-bottom">
                                {{ trans('inventoryList.billed') }} 
                            </a>
                            
                            <a href="{{ url('inventarliste/abrechnen-alle')}}" class="btn btn-primary no-margin-bottom">
                                {{ trans('inventoryList.all') }} 
                            </a>
                           
                        </span>
                    </div>
                    <div class="col-xs-6">
                        <span class="custom-input-group-btn">
                            
                            {!! Form::open(['url' => 'inventarliste/abrechnen/pdf', 'method'=>'POST','target'=>'_blank']) !!}
                       
                                <input type="hidden" name="accounted_for"
                                @if(Request::is('*abrechnen-abgerechnt*') )  
                                    value="1" 
                                @elseif(Request::is('*abrechnen-alle*') )  
                                    value="all"
                                @else    
                                    value="0"
                                @endif />
                                @if(isset($searchInput) )
                                    <input type="hidden" name="search" value="{{old('search',$searchInput)}}"/>
                                @else
                                <input type="hidden" name="search" value="{{old('search')}}"/>
                                @endif
                                <button type="submit" class="btn btn-primary no-margin-bottom pull-right">
                                    {{ trans('inventoryList.downloadPDF') }} 
                                </button>
                            </form>
                        </span>
                    </div>
                </div><!--end .row -->
            </div><!--end .box .box-white -->
        </div><!--end .box-wrapper -->
    </div><!--end .col-sm-12 -->
</div><!--end .row -->
    @if( isset($searchMandants) && count($searchMandants) )
    <!-- search results categories categorie box-->
        @foreach( $searchMandants as $mandant)
            <div class="panel-group">
                <div class="panel panel-primary" id="panelInventory{{$mandant->id}}">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-target="#collapseInventory{{$mandant->id}}" class="collapsed transform-normal" 
                               href="#collapseInventory{{$mandant->id}}">
                              ({{$mandant->mandant->mandant_number}}) {{$mandant->mandant->name}} 
                            </a>
                        </h4>
                    </div><!--end .panel-heading -->    
                </div><!--end .panel.panel-primary -->
            
                <div id="collapseInventory{{$mandant->id}}" class="panel-collapse collapse">
                    <div class="panel-body box-white">
                        <table class="table data-table box-white">
                            <thead>
                                <tr>
                                    <th class="text-center valign">@lang('inventoryList.name')</th>
                                    <th class="text-center valign">@lang('inventoryList.number')</th>
                                    <th class="text-center valign">@lang('inventoryList.size')</th>
                                    <th class="text-center valign">@lang('inventoryList.sellPrice')</th>
                                    <th class="text-center valign ">@lang('inventoryList.dateWithdrawal')</th>
                                    <th class="text-center valign no-sort">@lang('inventoryList.billed')</th>
                                    <th class="text-center valign no-sort"></th>
                                </tr>
                            </thead>
                            <tbody>
                            @if(count($mandant->items) )
                               @foreach($mandant->items as $k => $item)
                                    <tr>
                                {!! Form::open(['url' => ['inventarliste/abrechnen/'.$item->id.'/update'], 'method' => 'POST']) !!}
                                    <input type="hidden" name="href" value="#collapseInventory{{$mandant->id}}" />
                                    <td class="text-center valign">
                                        {{ $item->item->name }}
                                    </td>
                                    <td class="text-center valign ">
                                        {{ $item->value }}
                                    </td>
                                    <td class="text-center valign ">
                                        {{ $item->size->name }}
                                    </td>
                                    <td class="text-center valign ">
                                        {{ $item->sell_price }} Euro
                                    </td>
                                    
                                    <td class="text-center valign"> 
                                         {{ $item->created_at->format('d.m.Y H:i:s') }}
                                    </td>
                                    <td class="text-center valign"> 
                                        {!! ViewHelper::setCheckbox('accounted_for',$item,old('accounted_for'),trans('inventoryList.billed'),false,array(),array(),$item->id ) !!}
                                    </td>
                                    <td class="text-center valign"> 
                                        <button class="btn btn-primary" type="submit" name="save" value="1">{{ trans('adressatenForm.save') }}</button>
                                    </td>
                                </form>
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
                    </div><!-- end .panel-body -->
                </div><!-- end .panel-collapse -->
            
            </div><!--end .panel-group-->  
        @endforeach
    @elseif( isset($searchMandants) && !count($searchMandants) )
        <!-- search results categories categorie box-->
         <div class="panel-group">
            <div class="panel panel-primary" id="panelInventory">
                <div class="panel-heading">
                    <h4 class="panel-title">
                            <a data-toggle="collapse" data-target="#collapseInventory" class="transform-normal" 
                               href="#collapseInventory">
                                Suchergebnisse
                            </a>
                    </h4>
                </div><!--end .panel-heading -->    
            </div><!--end .panel.panel-primary -->
        
            <div id="collapseInventory" class="panel-collapse collapse in">
                <div class="panel-body box-white">
                    <table class="table data-table box-white">
                        <thead>
                            <tr>
                                <th  class="text-center valign">@lang('inventoryList.name')</th>
                                <th  class="text-center valign">@lang('inventoryList.number')</th>
                                <th class="text-center valign">@lang('inventoryList.size')</th>
                                <th class="text-center valign">@lang('inventoryList.sellPrice')</th>
                                <th class="text-center valign no-sort">@lang('inventoryList.dateWithdrawal')</th>
                                <th class="text-center valign no-sort">@lang('inventoryList.billed')</th>
                                <th class="text-center valign no-sort"></th>
                            </tr>
                        </thead>
                        <tbody>
                             <tr>
                                <td class"valign"></td>
                                <td class"valign"></td>
                                <td class"valign"></td>
                                <td class"valign">Keine Daten vorhanden</td>
                                <td class"valign"></td>
                                <td class"valign"></td>
                                <td class"valign"></td>
                            </tr>
                        </tbody>
                    </table>    
                </div><!-- end .panel-body -->
            </div><!-- end .panel-collapse -->
        
        </div><!--end .panel-group-->  
    @else
        <!-- regular categorie box-->
        @if($mandants)
            @foreach( $mandants as $mandant)
                <div class="panel-group">
                    <div class="panel panel-primary" id="panelInventory{{$mandant->id}}">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                    <a data-toggle="collapse" data-target="#collapseInventory{{$mandant->id}}" class="collapsed transform-normal" 
                                       href="#collapseInventory{{$mandant->id}}">
                                      ({{$mandant->mandant_number}}) {{$mandant->name}} 
                                    </a>
                            </h4>
                        </div><!--end .panel-heading -->    
                    </div><!--end .panel.panel-primary -->
                
                    <div id="collapseInventory{{$mandant->id}}" class="panel-collapse collapse">
                        <div class="panel-body box-white">
                            <table class="table data-table box-white">
                                <thead>
                                    <tr>
                                        <th  class="text-center valign">@lang('inventoryList.name')</th>
                                        <th  class="text-center valign">@lang('inventoryList.number')</th>
                                        <th class="text-center valign">@lang('inventoryList.size')</th>
                                        <th class="text-center valign">@lang('inventoryList.sellPrice')</th>
                                        <th class="text-center valign">@lang('inventoryList.dateWithdrawal')</th>
                                        <th class="text-center valign no-sort">@lang('inventoryList.billed')</th>
                                        <th class="text-center valign no-sort"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                 @if(count($mandant->items) )
                                  
                                        @foreach($mandant->items as $k => $item)
                                        <tr>
                                        {!! Form::open(['url' => ['inventarliste/abrechnen/'.$item->id.'/update'], 'method' => 'POST']) !!}
                                        <input type="hidden" name="href" value="#collapseInventory{{$mandant->id}}" />
                                            <td class="text-center valign">
                                                {{ $item->item->name }}
                                            </td>
                                            <td class="text-center valign ">
                                                {{ $item->value }}
                                            </td>
                                            <td class="text-center valign ">
                                                {{ $item->size->name }}
                                            </td>
                                            <td class="text-center valign ">
                                                {{ $item->sell_price }} Euro
                                            </td>
                                            
                                            <td class="text-center valign"> 
                                                 {{ $item->created_at->format('d.m.Y H:i:s') }}
                                            </td>
                                            <td class="text-center valign"> 
                                                {!! ViewHelper::setCheckbox('accounted_for',$item,old('accounted_for'),trans('inventoryList.billed'),false,array(),array(),$item->id ) !!}
                                            </td>
                                            <td class="text-center valign"> 
                                                <button class="btn btn-primary" type="submit" name="save" value="1">{{ trans('adressatenForm.save') }}</button>
                                            </td>
                                        </form>
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
                        </div><!-- end .panel-body -->
                    </div><!-- end .panel-collapse -->
                </div><!--end .panel-group-->  
            @endforeach
        @endif
       
    @endif

@stop
