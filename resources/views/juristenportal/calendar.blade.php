@extends('master')

@section('page-title'){{ ucfirst( trans('juristenPortal.juristenportal') )}} {{ ucfirst( trans('juristenPortal.calendar') )}}@stop

@section('content')

<div class="box-wrapper col-sm-12">
    <div class="box  box-white">
        <div class="row">
     
            <div class="box">
                 
                
                <div class="row">
                    {!! Form::open(['action' => 'JuristenPortalController@viewUserCalendar', 'method'=>'POST']) !!}
                    <input type="hidden" id="starViewtDate" name="starViewtDate" value="">
                    
                    <div class="col-md-4 col-lg-3">
                          <div class="form-group">
                           {!! ViewHelper::setUserSelect($users,'id', $data, old('users'),'', 'Mitarbeiter',false ) !!}
                        </div>
                    </div>
                    <div class="col-md-4 col-lg-3">
                        <label>&nbsp;</label>  
                        <div class="form-group">
                           <button class="btn btn-primary" type="submit">anzeigen</button>
                        </div>
                    </div>
                    {!! Form::close() !!}
                    <div class="col-md-4 col-lg-3">
                    </div>
                    <div class="col-md-4 col-lg-3 text-right">
                        
                    </div>
                </div>    
                
                
                <div id='calendar'></div>
                
            </div>

        </div>
    </div>
    
</div>

<div class="clearfix"></div> <br>
@stop

@section('script')
<script>

$( 'select[name=id]' ).change(function() {
    var start = $('#calendar').fullCalendar('getDate');
    $('#starViewtDate').val(start.format('YYYY-MM-DD'));
});


$('#calendar').fullCalendar({

    defaultDate: moment('{{  $startdate or Carbon\Carbon::today()->format("Y-m-d") }}'),
    
    locale: 'de',
    buttonText: {
        today: 'heute'
    },
    
    header: {
				left: 'prev,next, today',
				center: 'title',
				right: 'listMonth,month'
			},
    views: {
				listMonth: { buttonText: 'Listenansicht' },
				
				month: { buttonText: 'Kalender' }
			},
			
    eventLimit: 6, // for all non-agenda views
    
    events: function(start, end, timezone, callback) {
        
        var startdate = $('#calendar').fullCalendar('getDate');
        $('#starViewtDate').val(startdate.format('YYYY-MM-DD'));
        
        var user_id = $('select[name=id]').val();
        
        jQuery.ajax({
            url: '{{ url("calendarEvent") }}',
            type: 'POST',
            dataType: 'json',
            data: {
                start: start.format(),
                end: end.format(),
                user_id: user_id,
                _token: '{{ csrf_token() }}',
            },
            success: function(doc) {
                
                var events = [];
                
                for(var i = 0; i < doc.length; i++){
                    var item = {};
                    item.id = doc[i].id;
                    item.title = doc[i].title;
                    item.start = doc[i].start;
                    item.color = doc[i].bgcolor;
                    item.textColor = doc[i].color;
                    events.push(item);
                }

                callback(events);
            }
        });
    }
    
});
</script>

@stop