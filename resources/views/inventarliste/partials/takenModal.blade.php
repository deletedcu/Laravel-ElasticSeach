<!-- edit modal for {{$item->name}} -->
<div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" 
@if($double== true) 
    id="item-taken-{{$item->id}}-2" aria-labelledby="item-taken-{{$item->id}}-2" 
@else 
    id="item-taken-{{$item->id}}" aria-labelledby="item-taken-{{$item->id}}"
@endif
>
    
    <div class="modal-dialog modal-lg edit">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">{{ $item->name }}</h4>
                <!--<h4 class="modal-title">@lang('inventoryList.edit') {{ $item->name }} ({{ $item->category->name }})</h4>-->
            </div>        
            {!! Form::open(['route' => ['inventarliste.update', 'inventarliste'=> $item->id], 'method' => 'PATCH']) !!}
                <input type="hidden" name="href" value="#collapseInventory{{$item->category->id}}" />
                <input type="hidden" name="search" value="{{$searchParam}}" />
            <div class="modal-body">
                <p class="text-left"><strong class="bigger">@lang('inventoryList.howManyTaken')</strong></p>
                <div class="row">
                    <div class="col-md-6 col-lg-6">
                        <div class="form-group">
                            <label class="control-label">
                               @lang('inventoryList.number')* 
                            </label> 
                           <input type="number" min="1" max="{{$item->value}}" name="taken" class="form-control" value="1" required />
                        </div>
                    </div>
                    @if( !$item->neptun_intern )
                        <div class="col-md-6 col-lg-6">
                              <div class="form-group">
                                {!! ViewHelper::setSelect($mandants,'mandant_id',$item,old('mandant_id'),
                                trans('benutzerForm.mandant'), trans('inventoryList.select'), true, array(), array(), array(), true  ) !!}
                            </div>
                        </div>
                        
                        <div class="clearfix"></div><br/>
                        <!-- input box-->
                        <div class="col-md-12 col-lg-12">
                            <div class="form-group">
                                {!! ViewHelper::setArea('text',$item,old('text'),trans('inventoryList.text'),trans('inventoryList.text'),true ) !!}
                            </div>
                        </div><!--End input box-->  
                    @endif
                </div><!--end .row-->
            </div>
            <div class="modal-footer">
                <br/>
                <span class="custom-input-group-btn pull-right">
                    <button type="submit" class="btn btn-primary no-margin-bottom">
                        {{ trans('inventoryList.save') }} 
                    </button>
                </span>
                <span class="custom-input-group-btn pull-right">
                    <button type="button" class="btn btn-default " data-dismiss="modal">@lang('inventoryList.close')</button>
                </span>
                
            </div>  
            {!! Form::close() !!}
        </div>
      </div>
</div><!-- end edit modal for {{$item->name}} -->