@if(count($sendingList) )
<!-- versand panel -->
<div class="panel panel-primary" id="panelVersand">
    <div class="panel-heading">
        <h4 class="panel-title">
            <a data-toggle="collapse" data-target="#versandPanel" href="#versandPanel" class="transform-normal collapsed">
                Versand
            </a>
        </h4>
    </div>
            
    <div id="versandPanel" class="panel-collapse collapse" role="tabpanel">
        <div class="panel-body">
            <div class="sendingList">
                @foreach( $sendingList as $item )
                    <div class="sent-item-{{$item->document_id}} row flexbox-container col-xs-12">
                        <div class="pull-left">                                
                                <span class="comment-header">
                                    <?php $user = ViewHelper::getUser($item->userEmailSetting->user_id); ?>
                                    <strong> 
                                        {{ $user->first_name .' '. $user->last_name }}
                                        ({{$item->userEmailSetting->recievers_text}}) - 
                                        @if($item->userEmailSetting->sending_method == 1)
                                            {{ trans('benutzerForm.email') }}
                                        @elseif($item->userEmailSetting->sending_method == 2)
                                            {{ trans('benutzerForm.email-attachment') }}
                                        @elseif($item->userEmailSetting->sending_method == 3)
                                            {{ trans('benutzerForm.fax') }}
                                        @elseif($item->userEmailSetting->sending_method == 4)
                                            {{ trans('benutzerForm.mail') }}
                                        @endif
                                    </strong> <br>

                                    @if( $item->sent == true )
                                        Gesendet
                                    @else
                                        Nicht gesendet
                                    @endif
                                </span>

                            <div class="clearfix"></div>
                        </div>
                    </div>

                    <div class="clearfix"></div>
                    <hr/>
                @endforeach
            </div>
        </div><!--end .panel-body -->
    </div><!--end #freigabePanel -->
</div><!-- end freigeber panel -->        
@endif