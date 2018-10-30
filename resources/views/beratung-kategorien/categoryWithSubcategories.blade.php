@extends('master')

@section('page-title') {{ ( trans('navigation.juristenPortalBeratung') ) }} {{ trans('navigation.kategorien') }}  - {{ $category->name }}@stop

@section('content')


 <div class="row">
    <div class="col-xs-12">
        <div class="col-xs-12 box-wrapper box-white">
    
    <h2 class="title">{{ $category->name }}</h2>
    
    <div class="box iso-category-overview">
        
        @if(count($category->juristCategoriesBeratungActive))
            <ul class="level-1">
                @foreach($category->juristCategoriesBeratungActive as $cat)
                    @if($cat->active)
                        <li>
                            <a href="{{ url('beratung-kategorien/'. $cat->id) }}">{{ $cat->name }}</a>
                            <ul class="level-2">
                            @foreach($cat->juristCategoriesBeratungActive as $c)
                                <li><a href="{{ url('beratung-kategorien/'. $c->id ) }}">{{$c->name}}</a></li>
                            @endforeach
                            </ul>
                        </li>
                    @endif
                @endforeach
            </ul>
        @else
            Keine Einträge gefunden.
        @endif
        
    </div>

</div>
</div><!-- end .col-xs-12-->

</div><!--end .row-->
@stop