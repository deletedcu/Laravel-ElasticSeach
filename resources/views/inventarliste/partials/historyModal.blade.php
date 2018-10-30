<!-- history modal for {{$item->name}} -->
<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="history-inventory-{{$item->id}}" aria-hidden="true" id="history-inventory-{{$item->id}}">
    <div class="modal-dialog ">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title text-left">@lang('inventoryList.history') {{ $item->category->name }} {{ $item->name }}</h4>
            </div>        
            
            <div class="modal-body">
                <p class="text-left"><strong class="bigger">@lang('inventoryList.lastTwentyChanges')</strong><br/><br/></p>
                @if( $item->history->count() > 0)
                
                    @foreach( $item->history as $history )
                        <div>
                            <p class="text-left">
                                {!! ViewHelper::genterateHistoryModalString($history) !!}
                                <hr/>
                            </p>
                        </div>
                    @endforeach
                @else
                    <p>Keine Daten vorhanden</p>
                @endif
            </div>
            
            <div class="modal-footer">
                <br/>
                <button type="button" class="btn btn-default" data-dismiss="modal">@lang('inventoryList.close')</button>
            </div>      
        </div>
      </div>
</div><!-- end history modal for {{$item->name}} -->