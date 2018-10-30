  <label class="control-label">
        {{ ucfirst($label)}}@if( $required !=false )* @endif 
    </label>
<select name="{{$inputName}}" class="form-control select @foreach($classes as $class) {{$class}} @endforeach"
data-placeholder="{{ strtoupper($placeholder) }}@if( $required !=false )* @endif" 
@foreach($dataTag as $tag) {{$tag}} @endforeach

@foreach($attributes as $attr) {{$attr}} @endforeach

    @if( $required !=false ) 
        required 
    @endif
    >
   
    @if($emptyOption == true) <option value="">@if(!empty($placeholder) ) {{ ucfirst($placeholder) }} @else Alle @endif</option> @endif
    @if( count($collections) > 0 )
        @foreach($collections as $collection)
           <option value="{{$collection->id}}" 
                @if( !empty($data->$inputName) && $collection->id == $data->$inputName)
                    selected
                @endif >
               {{$collection->name}}
           </option>
        @endforeach
    @endif
</select>