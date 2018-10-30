<!-- edit modal for {{$item->name}} -->
<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="item-view-{{$item->id}}" aria-hidden="true" id="item-view-{{$item->id}}">
    <div class="modal-dialog modal-lg edit">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title text-left">{{ $item->name }}</h4>
                <!--<h4 class="modal-title">@lang('inventoryList.edit') {{ $item->name }} ({{ $item->category->name }})</h4>-->
            </div>
            <div class="modal-body">
                <div class="col-md-6 col-lg-6">
                  <div class="form-group">
                    {!! ViewHelper::setInput('name', $item,old('name'), 
                    trans('inventoryList.name'), trans('inventoryList.name'), true,'text',array(), ['disabled'=>'disabled']) !!}
                    </div>
                    <br/>
                </div>
                <div class="col-md-6 col-lg-6">
                      <div class="form-group">
                        {!! ViewHelper::setSelect($categories,'inventory_category_id',$item,old('inventory_category_id'),
                        trans('inventoryList.category'), trans('inventoryList.category'),true,array(),array(),['disabled'=>'disabled'] ) !!}
                    </div>
                    <br/>
                </div>
                <div class="col-md-6 col-lg-6">
                    <div class="form-group">
                        {!! ViewHelper::setSelect($sizes,'inventory_size_id',$item,old('inventory_size_id'),
                        trans('inventoryList.size'), trans('inventoryList.size'),true,array(),array(),['disabled'=>'disabled'] ) !!}
                    </div>
                    <br/>
                </div>
                <div class="col-md-6 col-lg-6">
                    <div class="form-group">
                        {!! ViewHelper::setInput('value', $item,old('value'), 
                        trans('inventoryList.number'), trans('inventoryList.number'), true,'number', array(),
                        array('min'=> 0,'disabled'=>'disabled') ) !!}
                    </div>
                    <br/>
                </div>
                <div class="col-md-6 col-lg-6">
                    <div class="form-group">
                        {!! ViewHelper::setInput('min_stock', $item,old('min_stock'), 
                        trans('inventoryList.minStock'), trans('inventoryList.minStock'), true,'number', array(),
                        array('min'=> 0,'disabled'=>'disabled') ) !!}
                    </div>
                    <br/>
                </div>
                <div class="col-md-6 col-lg-6">
                    <div class="form-group">
                        {!! ViewHelper::setInput('purchase_price', $item,old('purchase_price'), 
                        trans('inventoryList.purchasePrice'), trans('inventoryList.purchasePrice'),false,'number',array(),['disabled'=>'disabled'] ) !!}
                    </div>
                    <br/>
                </div>
                <div class="col-md-6 col-lg-6">
                    <div class="form-group">
                        {!! ViewHelper::setInput('sell_price', $item,old('sell_price'), 
                        trans('inventoryList.sellPrice'), trans('inventoryList.sellPrice'),false,'number',array(),['disabled'=>'disabled'] ) !!}
                    </div>
                </div>
                <div class="col-md-6 col-lg-6">
                    <div class="form-group text-left">
                        <br/>
                       {!! ViewHelper::setCheckbox('neptun_intern',$item,old('neptun_intern'),
                       trans('inventoryList.neptunIntern'),false,array(),['disabled'=>'disabled']  ) !!}
                    </div>
               </div>
            </div>
            <div class="modal-footer">
                <br/>
                <span class="custom-input-group-btn pull-right">
                    
                </span>
                <span class="custom-input-group-btn pull-right">
                    <button type="button" class="btn btn-default " data-dismiss="modal">@lang('inventoryList.close')</button>
                </span>
                
            </div>  
        </div>
      </div>
</div><!-- end edit modal for {{$item->name}} -->