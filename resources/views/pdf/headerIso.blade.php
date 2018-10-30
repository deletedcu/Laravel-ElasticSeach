<div class="header"  style="">
    <div class="div-pusher">       
        <p>{!! $document->name_long !!}</p>
    </div>
        <div class="image-div">
            <div class="pull-right" style="">
                @if( $document->isoCategories != null) 
                    <p class="subcategory-title">
                        {{ $document->isoCategories->name }}
                        @if( $document->iso_category_number != null)
                            / Kapitel-Nr:   {{ $document->iso_category_number }}@if( $document->additional_letter ){{ $document->additional_letter }} @endif
                        @endif
                    </p>
                @endif
                <p class="parent-pagenum"> 
                   Seite {{$document->iso_category_number}} - {PAGENO} von {nbpg}
                </p>
            </div>
    </div>
    <div class="clearfix"></div>
    <div class="border-wrapper">
        <div class="border-div"></div>
    </div>
    
</div>
<div class="dummy-div" style=""></div>