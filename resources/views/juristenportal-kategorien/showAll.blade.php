@extends('master')

@section('page-title')
  @lang('navigation.juristenPortalRechtsablage')-@lang('navigation.kategorien')
@stop


@section('content')

<div class="row">
    
    <div class="col-xs-12">
        <div class="col-xs-12 box-wrapper box-white">
            
            <h2 class="title">Alle Kategorien</h2>
            
            <div class="box iso-category-overview">
                
                @if(count($juristenCategories))
                    <ul class="level-1">
                        <li>
                            <a href="#">{{ ucfirst( trans('juristenPortal.createRechtsablage') ) }} </a>
                        </li>
                        @foreach( $juristenCategories as $jueristenCategory)
                            <li>
                            <a href="{{url('rechtsablage-kategorien/'.$jueristenCategory->id)}}">{{ $jueristenCategory->name }}
                                @if( count($jueristenCategory->juristCategoriesActive) ) <span class="fa arrow"></span> @endif
                            </a>
                            @if( count($jueristenCategory->juristCategoriesActive) )
                            <ul class="level-2">
                                @foreach( $jueristenCategory->juristCategoriesActive as $subLevel1)
                                <li>
                                    <a href="{{url('rechtsablage-kategorien/'.$subLevel1->id)}}">{{ $subLevel1->name }}
                                        @if( count($subLevel1->juristCategoriesActive) ) <span class="fa arrow"></span> @endif
                                    </a>
                                    @if( count( $subLevel1->juristCategoriesActive ) )
                                    <ul class="level-3">
                                        @foreach( $subLevel1->juristCategoriesActive as $subLevel2)
                                            <li>
                                                <a href="{{url('rechtsablage-kategorien/'.$subLevel2->id)}}">{{ $subLevel2->name }}</a>
                                            </li>
                                        @endforeach
                                    </ul>
                                    @endif
                                </li>
                                @endforeach
                            </ul>
                            @endif
                            </li>
                        @endforeach {{-- first level subcategory --}}
                    </ul>
                @else
                    Keine Einträge gefunden.
                @endif
                
            </div>

        </div>
    </div>
    
</div>
@stop