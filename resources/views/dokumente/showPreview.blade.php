{{-- DOKUMENT DETAILS --}}

@extends('master')

@section('page-title') {{ ucfirst( trans('controller.dokumente')) }} - @if( isset($document->documentType->name) ){{ $document->documentType->name }}@endif @stop

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

                            <div class="content">
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
                                         @if( ViewHelper::htmlObjectType( $document,$attachment ) != null && ViewHelper::htmlObjectType( $document,$attachment ) == 'application/pdf' )
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
                                                                 <a href="{{route('dokumente.edit', $docAttach->document->id)}}" class="no-underline">
                                                                     <span class="icon icon-edit inline-block"></span>
                                                                 </a> 
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
               
                <div class="clearfix"></div> 
               
            </div>

            <div class="col-sm-4 col-md-3 col-lg-2 btns scrollable-document">
                @if(isset($variants[0] ) ) 
                    <a target="_blank"
                    href="/dokumente/ansicht-pdf/{{$document->id}}/{{$variants[0]->variant_number}}" class="btn btn-primary pull-right">Druckvorschau</a>
                @endif
            </div>
            
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
        </div>
    </div>
</div>       
@stop        
        @if( isset( $document->document_type_id ) )
           @section('preScript')
               <!-- variable for expanding document sidebar-->
               <script type="text/javascript">
                    var documentType = "{{ $document->documentType->name}}";
                   
                      
               </script>
               
               <!--patch for checking iso category document-->
                @if( isset($document->isoCategories->name) )
                    <script type="text/javascript">   
                        if( documentType == 'ISO Dokument')
                            var isoCategoryName = '{{ $document->isoCategories->name}}';
                    </script>
                @endif
               <!-- End variable for expanding document sidebar-->
           @stop
       @endif
