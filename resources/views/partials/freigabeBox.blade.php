@if(count($document->documentApprovals) )
<!-- freigeber panel -->
<div class="panel panel-primary" id="panelFreigeber">
    <div class="panel-heading">
        <h4 class="panel-title">
            <a data-toggle="collapse" data-target="#freigeberPanel" href="#freigeberPanel" class="transform-normal collapsed">
                Freigeber
            </a>
        </h4>
    </div>
            
    <div id="freigeberPanel" class="panel-collapse collapse" role="tabpanel">
        <div class="panel-body">
            <div class="commentsMy">
                @foreach( $document->documentApprovals as $k => $approved )
                    <div class="comment-{{++$k}} row flexbox-container col-xs-12">
                        <div class="pull-left">                                
                                <span class="comment-header">
                                    <strong> {{ $approved->user->first_name }} {{ $approved->user->last_name }} </strong> <br>

                                    @if( $approved->approved == 1 )
                                        @if($approved->fast_published)
                                            Schnell veröffentlicht,
                                        @else
                                            Freigegeben,
                                        @endif
                                    @elseif( $approved->approved == 0 && $approved->date_approved != null)
                                        Nicht Freigegeben,
                                    @else
                                        Wartet auf Freigabe
                                    @endif
                                    @if( $approved->date_approved != null){{ $approved->date_approved }} @endif<br>
                                </span>

                            <div class="clearfix"></div>

                            @if(isset($comment))
                                @if(ViewHelper::documentVariantPermission($comment->document)->permissionExists && $comment->document->active)
                                    @if( $approved->document->published != null )
                                        <a href="{{url('/dokumente/'. $comment->document->published->url_unique)}}">
                                            <strong> {{ $approved->document->name }} </strong>
                                        </a>
                                    @else
                                        <a href="{{url('/dokumente/'. $comment->document->id)}}">
                                            <strong> {{ $approved->document->name }} </strong>
                                        </a>
                                    @endif
                                @endif
                            @endif

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