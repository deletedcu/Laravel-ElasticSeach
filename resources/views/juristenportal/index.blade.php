{{-- Client managment--}}

@extends('master')

@section('page-title')
    {{ ucfirst( trans('navigation.juristenPortal') )}}
@stop


@section('content')

<div class="row">
    
    <div class="col-xs-12">
        <div class="col-xs-12 box-wrapper box-white">
            
            <h2 class="title">{{ ucfirst( trans('juristenPortal.juristenportal') )}}</h2>
            
            <div class="box iso-category-overview">
                <ul class="level-1">
                    <li class="">
                    <a href="{{ url('#') }}"> @lang('juristenPortal.juristTemplates')
                    <!--<span class="fa arrow"></span>-->
                    </a>
                        <!--<ul class="level-2">-->
                        <!--    <li>-->
                        <!--        <a href="{{ url('#') }}">@lang('juristenPortal.juristTemplatesCreate')</a>-->
                        <!--    </li>-->
                        <!--</ul>    -->
                    </li><!--end Wiedervorlage -->
                    <li>
                        <a href="{{ url('#') }}">@lang('juristenPortal.notes')
                        <!--<span class="fa arrow"></span>-->
                        </a>
                        <!--<ul class="level-2">-->
                        <!--    <li>-->
                        <!--        <a href="{{ url('#') }}">@lang('juristenPortal.notesCreate')</a>-->
                        <!--    </li>-->
                        <!--</ul>    -->
                    </li><!--end notiz -->
                    
                    <!--akten-->
                    <li>
                        <a href="{{ url('#') }}">@lang('juristenPortal.akten')
                        <!--<span class="fa arrow"></span>-->
                        </a>
                        
                        <!--<ul class="level-2">-->
                        <!--    <li>-->
                        <!--        <a href="{{ url('#') }}">@lang('juristenPortal.createAkten')</a>-->
                        <!--    </li>-->
                        <!--</ul>    -->
                    </li><!--end akten -->
                    
                    <!--documente beratung-->
                    <li>
                        <a href="{{ url('beratung-kategorien/alle') }}">{{ ucfirst( trans('juristenPortal.beratungDocuments') ) }} </a>
                        <!--<ul class="level-2">-->
                        <!--    <li>-->
                        <!--        <a href="#">{{ ucfirst( trans('juristenPortal.createRechtsablage') ) }} </a>-->
                        <!--    </li>-->
                        <!--    <li>-->
                        <!--        <a href="{{ url('beratungsportal/upload') }}">{{ ucfirst( trans('juristenPortal.upload') ) }} </a>-->
                        <!--    </li>-->
                        <!--    @if(!empty($juristenCategories))-->
                        <!--        @foreach( $juristenCategoriesBeratung as $juristenCategory)-->
                               
                        <!--           <li>-->
                        <!--                <a href="{{url('rechtsablage-kategorien/'.$juristenCategory->id)}}">{{ $juristenCategory->name }}-->
                        <!--                    @if( count($juristenCategory->juristCategoriesBeratungActive) ) <span class="fa arrow"></span> @endif-->
                        <!--                </a>-->
                        <!--                    @if( count($juristenCategory->juristCategoriesBeratungActive) )-->
                        <!--                    <ul class="level-3">-->
                        <!--                        @foreach( $juristenCategory->juristCategoriesBeratungActive as $subLevel1)-->
                        <!--                        <li>-->
                        <!--                            <a href="{{url('rechtsablage-kategorien/'.$subLevel1->id)}}">{{ $subLevel1->name }}-->
                        <!--                                @if( count($subLevel1->juristCategoriesBeratungActive) ) <span class="fa arrow"></span> @endif-->
                        <!--                            </a>-->
                        <!--                            @if( count( $subLevel1->juristCategoriesBeratungActive ) )-->
                        <!--                            <ul class="level-4">-->
                        <!--                                @foreach( $subLevel1->juristCategoriesBeratungActive as $subLevel2)-->
                        <!--                                    <li>-->
                        <!--                                        <a href="{{url('rechtsablage-kategorien/'.$subLevel2->id)}}">{{ $subLevel2->name }}</a>-->
                        <!--                                    </li>-->
                        <!--                                @endforeach-->
                        <!--                            </ul>-->
                        <!--                            @endif-->
                        <!--                        </li>-->
                        <!--                        @endforeach-->
                        <!--                    </ul>-->
                        <!--                    @endif-->
                        <!--            </li>-->
                        <!--        @endforeach-->
                        <!--    @endif  -->
                        <!--</ul>-->
                      
                    </li><!-- end documente beratung-->
                    
                    <li>
                        <a href="{{ url('rechtsablage-kategorien/alle') }}">{{ ucfirst( trans('juristenPortal.documentsRechtsablage') ) }} </a>
                        <!--   <ul class="level-2">-->
                        <!--        <li>-->
                        <!--            <a href="#">{{ ( trans('juristenPortal.createBeratung') ) }} </a>-->
                        <!--        </li>-->
                        <!--        <li>-->
                        <!--            <a href="{{ url('beratungsportal/upload') }}">{{ ucfirst( trans('juristenPortal.upload') ) }} </a>-->
                        <!--        </li>-->
                               
                        <!--@if(!empty($juristenCategories))-->
                            
                        <!--        @foreach( $juristenCategories as $juristenCategory)-->
                                
                        <!--                <a href="{{url('rechtsablage-kategorien/'.$juristenCategory->id)}}">{{ $juristenCategory->name }}-->
                        <!--                    @if( count($juristenCategory->juristCategoriesActive) ) <span class="fa arrow"></span> @endif-->
                        <!--                </a>-->
                        <!--                    @if( count($juristenCategory->juristCategoriesActive) )-->
                        <!--                    <ul class="level-2">-->
                        <!--                        @foreach( $juristenCategory->juristCategoriesActive as $subLevel1)-->
                        <!--                        <li>-->
                        <!--                            <a href="{{url('rechtsablage-kategorien/'.$subLevel1->id)}}">{{ $subLevel1->name }}-->
                        <!--                                @if( count($subLevel1->juristCategoriesActive) ) <span class="fa arrow"></span> @endif-->
                        <!--                            </a>-->
                        <!--                            @if( count( $subLevel1->juristCategoriesActive ) )-->
                        <!--                            <ul class="level-3">-->
                        <!--                                @foreach( $subLevel1->juristCategoriesActive as $subLevel2)-->
                        <!--                                    <li>-->
                        <!--                                        <a href="{{url('rechtsablage-kategorien/'.$subLevel2->id)}}">{{ $subLevel2->name }}</a>-->
                        <!--                                    </li>-->
                        <!--                                @endforeach-->
                        <!--                            </ul>-->
                        <!--                            @endif-->
                        <!--                        </li>-->
                        <!--                        @endforeach-->
                        <!--                    </ul>-->
                        <!--                    @endif-->
                        <!--        @endforeach-->
                        <!--@endif-->
                        <!--  </ul>-->
                    </li>
                     <li>
                        <a href="{{ url('beratungsportal/upload') }}">{{ ucfirst( trans('juristenPortal.upload') ) }} </a>
                    </li>
                    
                    
                </ul>
                
            </div>

        </div>
    </div>
    
</div>

<div class="clearfix"></div> <br>

@stop