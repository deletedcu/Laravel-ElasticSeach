{{-- ISO CATEGORIEN INDEX --}}

@extends('master')

@section('page-title')
    Dokumente Übersicht
@stop


@section('content')

<div class="row">
    
    <div class="col-xs-12">
        <div class="col-xs-12 box-wrapper box-white">
            
            <h2 class="title">Alle Kategorien</h2>
            
            <div class="box iso-category-overview">
                
                @if(count($documentTypes))  
                        <ul class="level-1">
                            
                            @if(!empty($documentTypes))
                                
                                @foreach($documentTypes as $documentType)
                                
                                    @if($documentType->active)
                                    
                                        @if($documentType->visible_navigation)
                                    
                                            @if($documentType->id == 1)
                                                <li> <a href="{{ url('dokumente/news') }}">{{ $documentType->name }}</a> </li>
                                            @elseif($documentType->id == 2)
                                                <li> <a href="{{ url('dokumente/rundschreiben') }}">{{ $documentType->name }}</a> </li>
                                            @elseif($documentType->id == 3)
                                                <li> <a href="{{ url('dokumente/rundschreiben-qmr') }}">{{ $documentType->name }}</a> </li>
                                            @elseif($documentType->id == 4)
                                                <li>
                                                    <a href="{{url('iso-dokumente')}}">{{ $documentType->name }}
                                                        @if(!empty($isoCategories)) <span class="fa arrow"></span> @endif
                                                    </a>
                                                    
                                                    @if(!empty($isoCategories))
                                                    <ul class="level-2">
                                                        @foreach($isoCategories as $isoCategory)
                                                            @if($isoCategory->parent)
                                                            <li>
                                                                <a href="{{ url('iso-dokumente/'. $isoCategory->slug) }}">{{ $isoCategory->name }}<span class="fa arrow"></span></a>
                                                                <ul class="level-3">
                                                                @foreach($isoCategories as $isoCategoryChild)
                                                                    @if($isoCategoryChild->iso_category_parent_id == $isoCategory->id)
                                                                        <li><a href="{{ url('iso-dokumente/'. $isoCategoryChild->slug ) }}">{{$isoCategoryChild->name}}</a></li>
                                                                    @endif
                                                                @endforeach
                                                                </ul>
                                                            </li>
                                                            @endif
                                                        @endforeach
                                                    </ul>
                                                    @endif
                                                </li>
                                            @elseif($documentType->id == 5)
                                                <li> <a href="{{ url('dokumente/vorlagedokumente') }}">{{ $documentType->name }}</a> </li>
                                            @elseif(in_array($documentType->id, [7,8]))
                                                {{-- DONT SHOW JURIST DOKUS --}}
                                            @elseif($documentType->id != 1)
                                                <li> <a href="{{ url('dokumente/typ/' . str_slug($documentType->name)) }}">{{ $documentType->name }}</a> </li>
                                            @endif
                                    
                                        @endif
                                    
                                    @endif
                                    
                                @endforeach
                                
                            @endif
                           
                            
                           
                            @if( ViewHelper::canCreateEditDoc() == true )
                                <li>
                                    <a href="{{ url('dokumente/create') }}">{{ ucfirst( trans('navigation.document') ) }} {{ trans('navigation.anlegen') }}</a>
                                </li>
                            @endif
                        </ul>
                    <div class="clearfix"></div>
                @else
                    Keine Einträge gefunden.
                @endif
                
            </div>

        </div>
    </div>
    
</div>

<div class="clearfix"></div> <br>

@stop
@section('afterScript')
            <!--patch for checking iso category document-->
          
                <script type="text/javascript">
                      
                </script>
                    <!-- End variable for expanding document sidebar-->
        @stop