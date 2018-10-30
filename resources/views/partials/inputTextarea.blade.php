<label class=" control-label">
    {{ ucfirst($label) }}@if( $required !=false )* @endif 
</label>
<textarea cols="30" rows="5" class="form-control" name="{{$inputName}}" @if( $required ) required @endif  @if( $readonly !=false ) readonly @endif placeholder="{{ strtoupper($label) }}@if( $required !=false )* @endif">@if( isset( $data->$inputName )  )@if($parseBack == true) {!! str_replace(["<br/>", "<br>"], "\r\n", $data->$inputName) !!} @else {!! $data->$inputName !!}@endif  @else {{ $old }} @endif</textarea>
