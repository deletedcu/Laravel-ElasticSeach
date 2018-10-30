{{-- DOKUMENT DETAILS --}}

@extends('master')

@section('page-title') {{ ucfirst( trans('controller.dokumente')) }} - 
@if( isset($document->documentType->name) && $document->documentType->id ==4)
    ISO Dokumente 
    @if(isset($isoCategoryParent))- {{$isoCategoryParent->name}}@endif
    @if(isset($isoCategory) )- {{$isoCategory->name}}@endif
@elseif( isset($document->documentType->name) )
{{ $document->documentType->name }}
@endif @stop

@section('content')

    <div class="box-wrapper ">
         <div class="row">
            <div class="col-md-12 col-lg-12">
                <h3 class="title">
                    @if( isset($document->name_long) && $document->name_long != '' )
                        @if( $document->document_type_id == 3 )
                                QMR @if( $document->qmr_number != null) {{ $document->qmr_number }}@if( $document->additional_letter ){{ $document->additional_letter }}@endif: @endif
                        @endif
                        {!! $document->name_long !!}
                    @else
                        {!! $document->name !!}
                    @endif  
                        <br>
                        <span class="text">
                            <strong>({{ trans('dokumentShow.version') }}: {{ $document->version }}, {{ trans('dokumentShow.status') }}: {{ $document->documentStatus->name }}@if($document->date_published), {{$document->date_published}}, @endif
                            @if(isset($document->owner) ){{ $document->owner->first_name.' '.$document->owner->last_name }}@endif)
                            </strong>
                        </span>
                    </h3>
            </div>
        </div>
        <div class="box">
            <div class="row">
                <div class="col-sm-8 col-md-9 col-lg-10">

                    <div class="clearfix"></div>

                    <div class="row">
                        <div class="col-xs-12">

                            <!--<div class="header">-->
                            <!--    <p class="text-small">{{ $document->created_at }}</p> -->
                            <!--    @if($document->documentAdressats)-->
                            <!--    <p><b>{{ trans('dokumentShow.adressat') }}:</b> {{ $document->documentAdressats->name }}</p> -->
                            <!--    @endif-->
                            <!--     @if( !empty( $document->betreff ))-->
                            <!--        <p><b>{{ trans('dokumentShow.subject') }}:</b> {{ $document->betreff }}</p> -->
                            <!--     @endif-->
                            <!--</div>-->

                            <div class="content @if($document->landscape == true) landscape @else portrait @endif">
                                <!--<p class="text-strong title-small">{{ trans('dokumentShow.content') }}</p>-->
                                
                                @if(!$document->pdf_upload)
                                    @foreach( $variants as $v => $variant)
                                
                                        @if( isset($variant->hasPermission) && $variant->hasPermission == true )
                                         
                                            <div>
                                                {{--<pre> {!! ($variant->inhalt) !!} </pre>--}}
                                                {!! ViewHelper::stripTags($variant->inhalt, array('div' ) ) !!}
                                            </div>
                                        @endif
                                    @endforeach

                                @endif

                            </div><!-- end .content -->

                            <div class="clearfix"></div> <br>

                            <div class="footer">

                               @if(count($document->documentUploads))
                              
                                    @if( ViewHelper::hasPdf( $document ) == true)
                                    <div class="attachments">
                                        <span class="text">PDF Vorschau: </span>
                                        <div class="clearfix"></div> <br>

                                        @foreach($document->documentUploads as $k => $attachment)
                                                <!--<a target="_blank" href="#{{$attachment->file_path}}" class="">{{basename($attachment->file_path)}}</a><br>-->
                                        <!--<a target="_blank" href="{{ url('download/'.str_slug($document->name).'/'.$attachment->file_path) }}" class="link">-->
                                        <!--{{-- basename($attachment->file_path) --}} PDF download</a>-->
                                        <!--<br><span class="indent"></span>-->
                                        
                                         @if(ViewHelper::htmlObjectType( $document,$attachment ) == 'application/pdf' )
                                          
                                            <object data="{{url('open/'.$document->id.'/'.$attachment->file_path)}}" 
                                            type="{{ ViewHelper::htmlObjectType( $document,$attachment ) }}" width="100%"  @if(ViewHelper::htmlObjectType( $document,$attachment )=='application/pdf') height="640" @endif>
                                                PDF konnte nicht initialisiert werden. Die Datei können sie <a href="{{url('download/'. $document->id .'/'.$attachment->file_path)}}">hier</a> runterladen.
                                            </object>
                                        @endif
                                        <div class="clearfix"></div> <br>
                                        @endforeach
                                    </div>
                                    @endif
                                @endif
                                
                                {{--
                                <!-- if doc type formulare display where it's attached -->
                                <!-- JIRA Task NEPTUN-653 -->
                                @if( $document->documentType->document_art == 1 && count( $document->variantDocuments )  )
                                
                                <div class="attachments document-attachments">
                                    <span class="text"> <strong>{{$document->name}}</strong> ist in folgenden Dokumenten angehängt: </span>
                                    <div class="clearfix"></div> <br>
                                    <div class="">
                                        @foreach($document->variantDocuments as $key =>$dc)
                                            @if(isset($dc->editorVariant->document))
                                                @if( in_array($dc->editorVariant->document->document_status_id, [3, 5]))
                                                    <div class="row flexbox-container">
                                                        <div class="col-md-12 link-padding">
                                                           <span class="text">
                                                                <span>@if($dc->editorVariant->document->date_published){{$dc->editorVariant->document->date_published}} - @endif @if(isset($dc->editorVariant->document->owner) ){{ $dc->editorVariant->document->owner->first_name.' '.$dc->editorVariant->document->owner->last_name }}@endif</span><br/>
                                                                @if($dc->editorVariant->document->published != null)
                                                                    <a href="/dokumente/{{ $dc->editorVariant->document->published->url_unique }}" target="_blank">
                                                                @else
                                                                    <a href="/dokumente/{{ $dc->editorVariant->document->id }}" target="_blank">
                                                                @endif    
                                                                    <strong>  {!! $dc->editorVariant->document->name !!} </strong>
                                                                </a><br/>
                                                                <span>
                                                                    {{ $dc->editorVariant->document->documentType->name }}
                                                                </span>
                                                            </span>
                                                            
                                                        </div>
                                                    </div><!-- end flexbox container -->
                                                @endif
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                                
                                @endif
                                --}}
                                
                                @foreach( $variants as $v => $variant)
                                    @if( ( isset($variant->hasPermission) && $variant->hasPermission == true ))
                                        
                                        @if( count( $variant->EditorVariantDocument ) )
                                            <div class="attachments document-attachments">
                                                <span class="text">Dokument Anlage/n: </span> <br>
                                                <div class="">
                                                @foreach($variant->EditorVariantDocument as $k =>$docAttach)
                                                    @if( $docAttach->document_id != $document->id )
                                                        @foreach( $docAttach->document->documentUploads as $key=>$docUpload)
                                                            @if( $key == 0 )
                                                             <!--<a href="{{route('dokumente.edit', $docAttach->document->id)}}" class="btn btn-primary">-->
                                                             <div class="row flexbox-container">
                                                                 <div class="col-md-12">
                                                                @if( ViewHelper::universalDocumentPermission( $document, false, false, true ) == true )     
                                                                 <a href="{{route('dokumente.edit', $docAttach->document->id)}}" class="no-underline">
                                                                     <span class="icon icon-edit inline-block"></span>
                                                                 </a>
                                                                 @endif
                                                                 <a target="_blank" href="{{ url('download/'. $docAttach->document->id .'/'.$docUpload->file_path) }}" class="link pl10 pr10">
                                                                   {!! ViewHelper::stripTags($docAttach->document->name, array('p' ) ) !!}</a> <br> <!-- <span class="indent"></span> -->
                                                                </div>
                                                            </div>
                                                            <div class="clearfix"></div>
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                @endforeach
                                                </div>
                                            </div><!-- end .attachments .document-attacments -->
                                        @endif
                                    @endif
                                @endforeach

                            </div><!-- end .footer -->

                          

                        </div><!--end col-xs-12-->
                    </div><!--end row-->

                    <div class="clearfix"></div> <br>
                

                </div><!-- end .col-sm-8 .col-md-9 .col-lg-10 -->
                
                <!-- sidebar -->
                <div class="col-sm-4 col-md-3 col-lg-2 btns scrollable-document">
                    
                    @if( ViewHelper::universalDocumentPermission( $document,false,false,true ) == true )
                        @if( $document->document_status_id  != 5 )
                            <a href="{{route('dokumente.edit', $document->id)}}" class="btn btn-primary pull-right">{{ trans('dokumentShow.edit')}} </a>
                       @endif
                    @endif
                    
                   @if( $document->document_status_id  != 5 )
                    @if( in_array($document->document_status_id, [3,5] ))
                        @if( ViewHelper::universalDocumentPermission( $document,false,false,true ) == true )
                                <a href="/dokumente/{{$document->id}}/activate" class="btn btn-primary pull-right">
                                    @if( $document->active  == false)
                                        {{ trans('dokumentShow.activate') }}
                                    @else
                                        {{ trans('dokumentShow.deactivate') }}
                                    @endif</a>
                                {{-- <a href="#" class="btn btn-primary pull-right">{{ trans('dokumentShow.new-version') }}</a> --}}
                             @endif
                        @endif
                    @endif
                    
                    @if(isset($document->documentType) && $document->documentType->document_art == 1)
                        @if( ViewHelper::universalDocumentPermission( $document,false,false,true ) == true || 
                        ViewHelper::universalHasPermission( array(13) ) == true )
                            <a href="/dokumente/new-version/{{$document->id}}" class="btn btn-primary pull-right">{{ trans('dokumentShow.new-version') }}</a> 
                        @endif
                    @else
                         @if( ViewHelper::universalDocumentPermission( $document,false,false,true ) == true || ViewHelper::universalHasPermission( array(11) ) == true )
                            <a href="/dokumente/new-version/{{$document->id}}" class="btn btn-primary pull-right">{{ trans('dokumentShow.new-version') }}</a> 
                        @endif
                    @endif
                    
                    {{-- JIRA Task NEPTUN-649 --}}
                    @if($document->document_status_id == 1)
                        @if( ViewHelper::universalHasPermission( array(11, 13) ) == true )
                            {{--  delete-prompt" data-text="{{trans('dokumentShow.delete-prompt')}} --}}
                            {!! Form::open(['route'=>['dokumente.destroy', 'id'=> $document->id], 'method'=>'DELETE']) !!}
                                <button type="submit" class="btn btn-primary pull-right">{{ trans('dokumentShow.delete') }}</button> <br>
                            {!! Form::close() !!}
                        @endif
                    @endif
                    
                    @if( ViewHelper::universalHasPermission( array(14) ) == true  && count( $document->documentHistory ) > 1  )
                        <a href="/dokumente/historie/{{$document->id}}" class="btn btn-primary pull-right">{{ trans('dokumentShow.history') }}</a>
                    @endif
                    
                
                    @if( $document->document_status_id  != 5 )
                        @if( ViewHelper::universalDocumentPermission($document,false) && ViewHelper::universalHasPermission(array(33)) ) 
                            @if($document->document_status_id == 3)
                                <a href="/dokumente/statistik/{{$document->id}}" class="btn btn-primary pull-right">{{ trans('dokumentShow.stats') }}</a>
                            @endif
                        @endif
                    @endif
                    

                    @if(count(Request::segments() ) == 2 && (!is_numeric(Request::segment(2) )) )
                        
                        <a href="#" class="btn btn-primary pull-right" data-toggle="modal" data-target="#favoriten">
                            {{ trans('dokumentShow.favorite') }}
                        </a>
                    
                        {{--
                        <!-- NEPTUN-657 -->
                        @if( $document->hasFavorite == false)
                            {{ trans('dokumentShow.favorite') }}
                        @else
                            <a href="/dokumente/{{$document->id}}/favorit" class="btn btn-primary pull-right">
                            {{ trans('dokumentShow.unFavorite') }}
                        @endif</a>  
                        --}}
                        
                        @if( $document->documentType->allow_comments == 1 && ViewHelper::documentVariantPermission($document)->permissionExists )
                            <button class="btn btn-primary pull-right" data-toggle="modal" data-target="#kommentieren">{{ trans('dokumentShow.commenting') }}</button>
                        @endif
                    @endif
                    
                    @if( $document->document_status_id  != 5 && $document->document_status_id != 1 )
                        @if(count(Request::segments() ) == 2 && is_numeric(Request::segment(2) ) )
                            @if( $authorised == false && $canPublish ==false && $published == false)
                                 @if( $document->documentType->document_art == 1) 
                                    @if( ViewHelper::universalHasPermission( array(13) ) == true  )
                                        <a href="/dokumente/{{$document->id}}/freigabe" class="btn btn-primary pull-right">{{ trans('dokumentShow.approve') }}</a>
                                    @endif
                                @else
                                    @if( ViewHelper::universalHasPermission( array(11) ) == true )
                                        <a href="/dokumente/{{$document->id}}/freigabe" class="btn btn-primary pull-right">{{ trans('dokumentShow.approve') }}</a>
                                    @endif
                                @endif
                            @elseif( ($authorised == false &&  $published == false ) ||
                               ($authorised == true && $published == false ) || ($canPublish == true && $published == false) 
                               && (ViewHelper::universalDocumentPermission( $document, false, false, true ) ) ){{-- $canPublish --}}
                                
                                @if( ( ( $document->documentType->document_art == 1 &&
                                    ViewHelper::universalHasPermission( array(13) ) == true ) ||
                                    ( $document->documentType->document_art == 0 &&
                                    ViewHelper::universalHasPermission( array(11) ) == true ) )
                                    && ViewHelper::universalDocumentPermission( $document, false, false, true ) )
                                <a href="/dokumente/{{$document->id}}/publish" class="btn btn-primary pull-right">{{ trans('documentForm.publish') }}</a>
                                @endif
                            @endif
                        @endif
                    @endif<!-- end if document is deleted -->
                    
                    @if( count($document->documentUploads) || ($document->pdf_upload == 1 ) || $document->documentType->document_art == 1 ) 
                        {{-- The PDF download button is only shown if the document has PDF Rundschreiben / PDF uploads --}}
                        @foreach($document->documentUploads as $k => $attachment)
                            @if($k > 0) @break @endif
                            <a href="{{url('download/'. $document->id .'/'. $attachment->file_path)}}" class="btn btn-primary pull-right">Download / Druck</a>
                        @endforeach
                    @else
                        {{-- The link for generating PDF from the document content should be here (the content you see on the overview) --}}
                        <a target="_blank" href="/dokumente/{{$document->id}}/pdf" class="btn btn-primary pull-right">Druckvorschau</a>
                    @endif

                </div><!--end sidebar -->

                <div class="clearfix"></div>
                <!-- modal start -->
                <div id="kommentieren" class="modal fade" tabindex="-1" role="dialog">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <h4 class="modal-title">{{ trans('dokumentShow.commenting') }}</h4>
                            </div>

                            {!! Form::open([
                               'url' => '/comment/'.$document->id,
                               'method' => 'POST',
                               'class' => 'horizontal-form']) !!}
                            <input type="hidden" name="page" value="/dokumente/{{$document->id}}" />
                            <div class="modal-body">
                                <div class="form-group">
                                    <label class="form-label">{{ trans('dokumentShow.subject') }}</label>
                                    <input type="text" name="betreff" class="form-control" placeholder="{{ trans('dokumentShow.subject') }}">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">{{ trans('dokumentShow.comment') }}</label>
                                    <textarea name="comment" cols="30" rows="5" class="form-control" placeholder="{{ trans('dokumentShow.comment') }}"></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('dokumentShow.close') }}</button>
                                <button type="submit" class="btn btn-primary">{{ trans('dokumentShow.save') }}</button>
                            </div>
                            </form>

                        </div>
                    </div>
                </div>  <!-- modal end -->
                
                <div class="clearfix"></div>
                <!-- modal start -->
                <div id="favoriten" class="modal fade" tabindex="-1" role="dialog">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <h4 class="modal-title">{{ trans('dokumentShow.favorite') }}</h4>
                            </div>
                            {{ Form::open(['action' => 'FavoritesController@store', 'class' => 'horizontal-form']) }}
                                <input type="hidden" name="document_id" value="{{$document->id}}" />
                                <div class="modal-body">
                                    {{-- <h5>{{trans('favoriten.default-category')}}: {{ $document->documentType->name }} </h5> --}}
                                    <h5>{{trans('favoriten.assign-category')}}:</h5>
                                    <div class="form-group">
                                        <select name="category_id" id="favorite_category_id" class="form-control select" data-placeholder="{{ strtoupper(trans('dokumentShow.favoriteCategorySelect')) }}">
                                            @if(isset($document->documentType))
                                            <option value="0">{{ $document->documentType->name }}</option>
                                            @endif
                                            <option value="new">{{trans('favoriten.new-category')}}</option>
                                            @foreach($favoriteCategories as $category)
                                                <option value="{{$category->id}}" @if(isset($document->favorite) && ($document->favorite->favorite_categories_id == $category->id)) selected @endif >
                                                    {{$category->name}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <input type="text" name="category_name" id="favorite_category_new" class="form-control" placeholder="{{ trans('favoriten.favorite-category-new') }}">
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('favoriten.close') }}</button>
                                    @if($document->hasFavorite)
                                        <a href="{{ url('dokumente/'.$document->id.'/favorit') }}" class="btn btn-danger">{{ trans('favoriten.remove-favorite') }}</a>
                                    @endif
                                    <button type="submit" name="save" value="1" class="btn btn-primary">{{ trans('favoriten.save-favorite') }}</button>
                                </div>
                            {{ Form::close() }}
                
                        </div>
                    </div>
                </div>  <!-- modal end -->
            </div>
        </div>
    </div>
     
    <div class="clearfix"></div> <br>
    
    @if( isset($document->documentType->name) && $document->documentType->document_art == 1 && count( $document->variantDocuments )  )
    
        <div class="panel panel-primary" id="panelDokumente">
        
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-target="#dokumentePanel" href="#dokumentePanel" class="transform-normal collapsed">
                        Dokumente
                    </a>
                </h4>
            </div>
            <div id="dokumentePanel" class="panel-collapse collapse" role="tabpanel">
                <div class="panel-body">
        
                    <div class="documentsAttached">
                                    
                        @foreach($document->variantDocuments as $key =>$dc)
                            @if(isset($dc->editorVariant->document))
                                @if( in_array($dc->editorVariant->document->document_status_id, [3, 5]))
                                    <div class="row flexbox-container padding-left">
                                        
                                       <span class="text col-xs-12">
                                            <span>
                                                @if($dc->editorVariant->document->version) Version {{$dc->editorVariant->document->version}}, @endif
                                                {{ $dc->editorVariant->document->documentStatus->name }} -
                                                @if($dc->editorVariant->document->date_published){{$dc->editorVariant->document->date_published}} - @endif 
                                                @if(isset($dc->editorVariant->document->owner) ){{ $dc->editorVariant->document->owner->first_name.' '.$dc->editorVariant->document->owner->last_name }}@endif
                                            </span><br/>
                                            
                                            @if($dc->editorVariant->document->published != null)
                                                <a href="/dokumente/{{ $dc->editorVariant->document->published->url_unique }}" target="_blank">
                                            @else
                                                <a href="/dokumente/{{ $dc->editorVariant->document->id }}" target="_blank">
                                            @endif    
                                                <strong>  {!! $dc->editorVariant->document->name !!} </strong>
                                            </a><br/>
                                            
                                            <span>
                                                {{ $dc->editorVariant->document->documentType->name }}
                                            </span>
                                        </span>
                                        
                                    </div>
                                    
                                    <div class="clearfix"></div>
                                    <hr/>
                                @endif
                            @endif
                        @endforeach
                        
                    </div>
        
                </div>
            </div>
        
        </div>
    
    @endif

    <div class="clearfix"></div>
    
    {{-- @if(ViewHelper::universalHasPermission( array(9)))<!-- changed @task NEPTUN-630 --> --}}
    @if( ViewHelper::universalDocumentPermission($document, false, $freigeber = false, true) || 
    ViewHelper::universalDocumentPermission($document, false, true, true) || ViewHelper::universalHasPermission( array())  )
        {!! ViewHelper::generateFreigabeBox($document) !!}
        @if($document->send_published)
            {!! ViewHelper::generateSentPublishedBox($document) !!}
            {!! ViewHelper::generateSentMailBox($document) !!}
        @endif
    @endif
    
     @if(ViewHelper::universalHasPermission( array(9)) || ViewHelper::universalDocumentPermission($document, false,false,true))
        @if( ViewHelper::universalDocumentPermission($document,false) == true )
           @if( $commentVisibility->freigabe == true || ViewHelper::universalDocumentPermission($document, false,false,true) )
                @if( count($documentCommentsFreigabe) )
                    {!! ViewHelper::generateCommentBoxes($documentCommentsFreigabe, trans('wiki.commentAdmin'),true ) !!}
                @endif
            @endif
        @endif
    @endif
    
    @if( count($myComments) )
        {!! ViewHelper::generateCommentBoxes($myComments, trans('dokumentShow.myComments'), true ) !!}
    @endif
    
    @if(ViewHelper::universalHasPermission( array(9)) || ViewHelper::universalDocumentPermission($document, false,false,true))
        @if( $commentVisibility->user == true || $commentVisibility->freigabe == true )
            @if(count($documentComments) )
                    {!! ViewHelper::generateCommentBoxes($documentComments, trans('wiki.commentUser'),true ) !!}
            @endif
        @endif
    @endif
    
       
    @stop
    
    @if( isset( $document->document_type_id ) )
        @section('preScript')
                <!-- variable for expanding document sidebar-->
        <script type="text/javascript">
            // console.log('yes');
            var documentType = "{{ $document->documentType->name}}";
            var documentSlug = "{{ str_slug( $document->documentType->name ) }}";
            // console.log(documentType);
            // console.log(documentSlug);
        </script>
        @stop
        
        @section('afterScript')
            <!--patch for checking iso category document-->
            @if( isset($document->isoCategories->name) )
                <script type="text/javascript">
                    if( documentType == 'ISO-Dokumente')
                        var isoCategoryName =  '{{ $isoCategoryName }}' ;
                        var detectHref = $('#side-menu').find('a:contains("'+isoCategoryName+'")');
                            $('#side-menu a').each(function(){
                                if (this.href.indexOf(isoCategoryName) != -1){
                                    detectHref = this.href;
                                }
                            });
                         setTimeout(function(){
                             $('a[href$="'+detectHref+'"]').addClass('active').attr('class','active').parents("ul").not('#side-menu').addClass('in');
                             if( $('a[href$="'+detectHref+'"]').addClass('active').attr('class','active').parent("li").find('ul').length){
                                  $('a[href$="'+detectHref+'"]').addClass('active').attr('class','active').parent("li").find('ul').addClass('in');
                             }
                         },1000 );
                </script>
            @endif
                    <!-- End variable for expanding document sidebar-->
        @stop
    @endif

