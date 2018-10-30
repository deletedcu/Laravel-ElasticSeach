{{-- ISO CATEGORIEN INDEX --}}

@extends('master')

@section('page-title')
    {{ ucfirst( trans('controller.dokumente')) }} - ISO-Dokumente 
@stop


@section('content')

<div class="row">
    
    <div class="col-xs-12">
        <div class="col-xs-12 box-wrapper box-white">
            
            <h2 class="title">Alle Kategorien</h2>
            
            <div class="box iso-category-overview">
                
                @if(count($isoCategories))
                    
                    <ul class="level-1"> 
                        @foreach($isoCategories as $isoCategory)
                            @if($isoCategory->parent)
                            <li>
                                <a href="{{ url('iso-dokumente/'. $isoCategory->slug) }}">{{ $isoCategory->name }}</a>
                                <ul class="level-2">
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
                @else
                    Keine Einträge gefunden.
                @endif
                
            </div>

        </div>
    </div>
    
</div>

 <div class="clearfix"></div> <br>
    
    <div class="col-xs-12 box-wrapper box-white">
        <h2 class="title">{{ trans('documentForm.searchTitle') }} ISO-Dokumente</h2>
        <div class="search box">
            <div class="row">
                {!! Form::open(['action' => 'DocumentController@search', 'method'=>'GET']) !!}
                    <input type="hidden" name="document_type_id" value="4">
                    
                    <div class="input-group">
                        <div class="col-md-12 col-lg-12">
                            {!! ViewHelper::setInput('search', old('search'), old('search'), trans('navigation.search_placeholder'), trans('navigation.search_placeholder'), true) !!}
                        </div>
                        <div class="col-md-12 col-lg-12">
                            <span class="custom-input-group-btn">
                                <button type="submit" class="btn btn-primary no-margin-bottom">
                                    {{ trans('documentForm.searchButton') }}
                                </button>
                            </span>
                        </div>
                    </div>
               </form>
            </div>
        </div>
    </div>

@stop
@section('afterScript')
            <!--patch for checking iso category document-->
          
                <script type="text/javascript">
                        var detectHref =window.location.href ;
                         setTimeout(function(){
                             if( $('a[href$="'+detectHref+'"]').next('ul').length){
                                //  console.log('yeah');
                                  $('a[href$="'+detectHref+'"]').next('ul').addClass('in');
                             }
                            
                         },1000 );
                </script>
                    <!-- End variable for expanding document sidebar-->
        @stop