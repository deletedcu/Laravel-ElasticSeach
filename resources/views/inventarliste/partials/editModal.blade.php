<!-- edit modal for {{$item->name}} -->
<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="edit-inventory--{{$item->id}}" aria-hidden="true" id="edit-inventory-{{$item->id}}">
    <div class="modal-dialog modal-lg edit">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">@lang('inventoryList.edit') {{ $item->category->name }} {{ $item->name }}</h4>
                <!--<h4 class="modal-title">@lang('inventoryList.edit') {{ $item->name }} ({{ $item->category->name }})</h4>-->
            </div>        
            {!! Form::open(['route' => ['inventarliste.update', 'inventarliste'=> $item->id], 'method' => 'PATCH']) !!}
            <div class="modal-body">
                <div class="col-md-6 col-lg-6">
                    {!! ViewHelper::setInput('name', $item,old('name'), 
                    trans('inventoryList.name'), trans('inventoryList.name'), true) !!}
                </div>
                <div class="col-md-6 col-lg-6">
                    {!! ViewHelper::setSelect($categories,'inventory_category_id',$item,old('inventory_category_id'),
                        trans('inventoryList.category'), trans('inventoryList.category'),true ) !!}
                </div>
                <div class="clearfix"></div><br/>
                
                <div class="col-md-6 col-lg-6">
                    {!! ViewHelper::setSelect($sizes,'inventory_size_id',$item,old('inventory_size_id'),
                        trans('inventoryList.size'), trans('inventoryList.size'),true ) !!}
                </div>
                <div class="col-md-6 col-lg-6">
                    <label class="control-label">
                       @lang('inventoryList.number')* 
                    </label> 
                   <input type="number" min="0" name="value" class="form-control" value="{{$item->value}}" required />
                </div>
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