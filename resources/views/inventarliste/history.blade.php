{{-- Invetarliste index --}}

@extends('master')

@section('page-title') @lang('inventoryList.history') {{ $item->name }} @stop

@section('content')
   
    <!-- regular categorie box-->
    <div class="row">
        <div class="col-sm-12 ">
            <div class="box-wrapper">
                <h2 class="title"> @lang('inventoryList.history') {{ $item->name }} ({{ $item->category->name }})</h2>
                <div class="box  box-white">
                   @if( count($histories) )
                        @foreach( $histories as $history )
                        <div class="history-div">
                            <p class="text-left">
                                {!! ViewHelper::genterateHistoryModalString($history) !!}
                                <hr/>
                            </p>
                        </div>
                        @endforeach
                        <div>
                            {{ $histories->links() }}
                        </div>
                    @else
                        <p>Keine Daten vorhanden</p>
                    @endif 
                </div><!-- end box -->
            </div><!-- end box wrapper-->
        </div>
    </div><!-- end add row -->
    
   

@stop
