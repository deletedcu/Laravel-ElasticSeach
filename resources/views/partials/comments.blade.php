<!-- {{ $title }} comments -->
@if( $withRow == true)
    <div class="row">
@endif

    <div class="col-xs-12">

        <div class="panel panel-primary" id="panelComments">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-target="#commentsPanel-{{str_slug($title)}}" href="#commentsPanel-{{str_slug($title)}}" class="transform-normal collapsed">
                        {{ $title }}
                    </a>
                </h4>
            </div>

            <div id="commentsPanel-{{str_slug($title)}}" class="panel-collapse collapse" role="tabpanel">
                
                <div class="panel-body">
                        
                    <div class="commentsMy">
                        @if(count($collection))
                            @foreach( $collection as $k => $comment )
                                @if( $comment->deleted_at == null )

                                    <div class="comment-{{++$k}} row flexbox-container">
                                        <!-- delete comment box -->
                                        <div class="pull-left delete-comment">
                                            <a href="/comment-delete/{{$comment->id}}/{{ $comment->document_id }}" class="no-underline"
                                               data-text="Wollen Sie diesen Kommentar wirklich löschen?">
                                             <span class="icon icon-trash inline-block delete-prompt"
                                                   data-text="Wollen Sie diesen Kommentar wirklich löschen?" title="Entfernen"></span>
                                            </a>
                                        </div>
                                        <!-- end delete comment box -->

                                        <div class="pull-left">
                                            
                                            <span class="comment-header">
    
                                                @if( isset($comment) && $comment->freigeber == 1)
                                                    <strong>
                                                        @if($comment->approved)
                                                            Freigegeben
                                                        @else
                                                            Nicht Freigegeben
                                                        @endif
                                                    </strong><br/>
                                                @endif
    
                                                <strong>
                                                    @if( isset($comment->betreff) && !empty($comment->betreff) )
                                                        {{ $comment->betreff }} -&nbsp;
                                                    @endif
                                                </strong>
    
                                                @if( isset($comment->user) && !empty($comment->user) )
                                                    {{ $comment->user->title }} {{ $comment->user->first_name }} {{ $comment->user->last_name }}, {{ $comment->created_at }} <br>
                                                @endif
    
                                            </span>
    
                                            <span class="comment-body">
                                                @if( isset($comment->comment) && !empty($comment->comment) )
                                                    {{-- {!! str_limit( str_replace(["\r\n", "\r", "\n"], "<br/>", $comment->comment) , $limit = 200, $end = ' ...') !!} --}}
                                                    {!! str_replace(["\r\n", "\r", "\n"], "<br/>", $comment->comment) !!}
                                                @endif
                                            </span>

                                            <div class="clearfix"></div>
                                            @if(isset($comment->document))
                                                @if(ViewHelper::documentVariantPermission($comment->document)->permissionExists && $comment->document->active)
                                                    Version {{ $comment->document->version }}, {{ $comment->document->documentStatus->name }} - 
                                                   @if(isset($comment->document->date_published) ) {{ $comment->document->date_published }} -@endif @if(isset($comment->document->owner) ){{ $comment->document->owner->first_name ." ". $comment->document->owner->last_name }}@endif
                                                    <br>
                                                    @if( $comment->document->published != null )
                                                        <a href="{{url('/dokumente/'. $comment->document->published->url_unique)}}">
                                                            <strong> {{ $comment->document->name }} </strong>
                                                        </a>
                                                    @else
                                                        <a href="{{url('/dokumente/'. $comment->document->id)}}">
                                                            <strong> {{ $comment->document->name }} </strong>
                                                        </a>
                                                    @endif
                                                    <br>
                                                    {{ $comment->document->documentType->name }}
                                                @endif
                                            @endif
                                        </div>

                                    </div>
                                    
                                    <hr/> <div class="clearfix"></div>

                                @endif
                            @endforeach
                        @endif
                    </div>
                    
                </div>
                
            </div>
            
        </div>
        
    </div>

            
@if( $withRow == true)
    </div> <!-- end .row -->
@endif
<!-- end {{ $title }} comments -->