@if($mailList)
<!-- versand panel -->
<div class="panel panel-primary" id="panelMail">
    <div class="panel-heading">
        <h4 class="panel-title">
            <a data-toggle="collapse" data-target="#mailPanel" href="#mailPanel" class="transform-normal collapsed">
                Versand Brief
            </a>
        </h4>
    </div>
            
    <div id="mailPanel" class="panel-collapse collapse" role="tabpanel">
        <div class="panel-body">
            <div class="sendingList">
                @foreach( $variants as $variant )
                    @foreach( ViewHelper::getMailsPerVariant($document, $variant) as $item )
                    <div class="sent-item-{{$item->document_id}} row flexbox-container col-xs-12">
                        <div class="pull-left">                                
                                <span class="comment-header">
                                    <?php $user = ViewHelper::getUser($item->user_id); ?>
                                    <?php $mandant = ViewHelper::getMandantById($item->mandant_id); ?>
                                    <strong> 
                                        ({{$mandant->mandant_number}}) {{$mandant->kurzname}}, {{ $user->first_name ." ". $user->last_name }}<br>
                                        {{ "Variante " . $variant }}
                                    </strong> <br>
                                    
                                </span>

                            <div class="clearfix"></div>
                        </div>
                    </div>

                    <div class="clearfix"></div>
                    <hr/>
                    @endforeach
                @endforeach
            </div>
        </div><!--end .panel-body -->
    </div><!--end #freigabePanel -->
</div><!-- end freigeber panel -->        
@endif