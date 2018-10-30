<div class="header">
    <div class="div-pusher">
        <p></p>
    </div>
    <div class="image-div text-right">
        @if($mandant->logo)
            <img src="{{url('/files/pictures/mandants/'. $mandant->logo)}}"/>
        @else
            <img src="{{url('/img/mandant-default.png')}}"/>
        @endif
    </div>
</div>