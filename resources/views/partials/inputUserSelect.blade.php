    <label class="control-label">
        {{ ucfirst($label) }}@if( $required !=false )* @endif 
    </label>
<select name="{{$inputName}}" class="form-control select @foreach($classes as $class) {{$class}} @endforeach"
data-placeholder="{{ ucfirst($placeholder) }}@if( $required !=false )* @endif"
@if( count($dataTag) )
    @foreach($dataTag as $tag) {{$tag}} @endforeach
@endif
@foreach($attributes as $attr) {{$attr}} @endforeach

    @if( $required !=false ) 
        required 
    @endif
    >
    @if($emptyOption == true) <option value="">Alle</option> @endif
    @if( count($collections) > 0 )
        @foreach($collections as $collection)
           <option value="{{$collection->id}}" 
                @if( !empty( $data->$inputName) && $collection->id == $data->$inputName)
                    selected
                @endif >
               {{$collection->last_name}} {{$collection->first_name}} 
           </option>
        @endforeach
    @endif
</select>