@section('page-title') {{ trans('controller.wikiCategory') }} @stop
    <!-- input box-->
    <div class="col-md-4 col-lg-4"> 
        <div class="form-group">
            <label class="control-label"> {{ ucfirst(trans('documentForm.status')) }} </label>
            <select name="status_id" class="form-control select" data-placeholder="{{ ucfirst(trans('documentForm.status')) }}" disabled>
                @foreach($documentStatus as $status)
                    <option value="{{$status->id}}" @if($status->id == 1) selected @endif> 
                        {{ $status->name }}
                    </option>
                @endforeach
            </select>
        </div>   
    </div><!--End input box-->
   
            
            <div class="clearfix"></div>
            <br/>