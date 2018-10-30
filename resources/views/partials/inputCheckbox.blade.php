<div class="checkbox no-margin-top">
    
        <input type="checkbox" 
            value="1" 
            name="{{$inputName}}"
            id="{{$inputName}}@if($number != -1)-{{$number}} @endif"
            class="@foreach( $classes as $class) {{ $class }} @endforeach" 
            @foreach ($dataTags as $dataTag ) {{$dataTag}} @endforeach 
            @if( $required !=false ) required @endif
            @if( isset( $data->$inputName ) && ( $data->$inputName == 1  ) )
    		    checked
        	@elseif( $old == 1 )
        	    checked
        	@endif
        	>
    <label for="{{$inputName}}@if($number != -1)-{{$number}} @endif">
        {{ ($label) }} @if( $required !=false ) {!! ViewHelper::asterisk() !!} @endif
    </label>
</div>
