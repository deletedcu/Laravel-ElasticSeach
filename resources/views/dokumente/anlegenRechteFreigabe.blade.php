@extends('master')

    @section('page-title')
        {{ trans('controller.create') }}
    @stop
    @section('content')
    
        {!! Form::open([
        'url' => 'dokumente/rechte-und-freigabe/'.$data->id,
        'method' => 'POST',
        'class' => 'horizontal-form freigabe-process' ]) !!}
            {{-- NEPTUN-815, NEPTUN-817 --}}
             
            <div class="box-wrapper">
                @if( $data->name != null)   
                    <div class="row">
                       <div class="col-md-12"><h3 class="title doc-title">{{ $data->name }}</h3></div>
                    </div>
                @endif
                <h2 class="title">{{ trans('rightsRelease.release') }}</h2>
                <div class="box">
                    <div class="row">
                        <div class="col-xs-12 col-md-6">
                            <label>{{ trans('rightsRelease.approver') }}*</label>
                            <select name="approval_users[]" class="form-control select approval-users" required 
                            @if($data->document_status_id != 1) disabled @endif
                            data-placeholder="{{ trans('rightsRelease.approver') }}" multiple>
                                <option value="0"></option>
                                @foreach($mandantUsers as $mandatUser)
                                <option value="{{$mandatUser->id}}"
                                        {!! ViewHelper::setMultipleSelect($data->documentApprovals, $mandatUser->id, 'user_id') !!}
                                        >{{ $mandatUser->last_name }} {{ $mandatUser->first_name }} </option>
                                @endforeach
                            </select>
                            
                            {{-- NEPTUN-815, NEPTUN-817 --}}
                            @if($data->document_status_id != 1) 
                                <select name="approval_users[]" class="hidden" multiple>
                                @foreach($mandantUsers as $mandatUser)
                                    <option value="{{$mandatUser->id}}" 
                                        {!! ViewHelper::setMultipleSelect($data->documentApprovals, $mandatUser->id, 'user_id') !!}>
                                        {{ $mandatUser->last_name }} {{ $mandatUser->first_name }}
                                    </option>
                                @endforeach
                                </select>
                            @endif
                        
                            <div class="clearfix"></div>
                            <div class="row">
                                <!-- input box-->
                                <div class="col-xs-12">
                                    <div class="form-group no-margin-bottom">
                                        <br>
                                        
                                        {{-- NEPTUN-815, NEPTUN-817 --}}
                                        
                                        @if($data->document_status_id != 1) 
                                            <div class="checkbox no-margin-top">
                                                <input type="checkbox"  value="1" name="email_approval" 
                                                    @if( isset( $data->email_approval ) && ( $data->email_approval == 1  ) )
                                                	    checked
                                                	@endif 
                                                	disabled readonly>
                                                <label>{{ trans('rightsRelease.sendEmail') }}</label>
                                            </div>
                                            <div class="hidden"> 
                                        @endif
                                            
                                        {!! ViewHelper::setCheckbox('email_approval', $data, old('email_approval'),
                                        trans('rightsRelease.sendEmail') ) !!}
                                            
                                        @if($data->document_status_id != 1) 
                                            </div> 
                                        @endif
                                        
                                    </div>   
                                </div><!--End input box--> 
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-xs-12 box-wrapper">
                <h2 class="title">{{ trans('rightsRelease.right') }}</h2>
                <div class="box">
                    <div class="row">
                        <div class="col-xs-12 col-md-6">
                               <div class="form-group">
                                  <label>{{ trans('rightsRelease.roles') }}</label>
                                    <select name="roles[]" class="form-control select alle-switch" data-placeholder="{{ trans('rightsRelease.roles') }}" multiple>
                                        @if( $data->approval_all_roles == true )
                                            <option value="0"></option>
                                            <option value="Alle" selected>Alle</option>
                                            @foreach($roles as $role)
                                            <option value="{{$role->id}}">{{ $role->name }}</option>
                                            @endforeach
                                        @elseif(ViewHelper::countComplexMultipleSelect($data->editorVariant,'documentMandantRoles')  == false)
                                            <option value="0"></option>
                                            <option value="Alle" selected>Alle</option>
                                            @foreach($roles as $role)
                                            <option value="{{$role->id}}">{{ $role->name }}</option>
                                            @endforeach
                                      
                                        @else
                                            <option value="0"></option>
                                            <option value="Alle">Alle</option>
                                            @foreach($roles as $role)
                                            <option value="{{$role->id}}"
                                                    {!! ViewHelper::setComplexMultipleSelect($data->editorVariant,'documentMandantRoles', $role->id, 'role_id') !!}
                                                    >{{ $role->name }}</option>
                                            @endforeach
                                        @endif
                                        
                                    </select>
                                </div>
                        </div>
                        
                        <div class="clearfix"></div>
                        
                        
                        @if( count($variants) > 0)
                   
                            @foreach( $variants as $k=>$variant) 
                            <div class="col-xs-12 col-md-6">
                                    <div class="form-group">
                                        <label>{{ trans('rightsRelease.variante') }} {{$k+1}}</label>
                                        <select name="variante-{{$k+1}}[]" class="form-control select freigabe-mandant alle-switch" 
                                         data-placeholder="{{ trans('rightsRelease.variante') }} {{$k+1}}"  
                                         @if( count($variants) > 1)
                                            required
                                        @endif multiple>
                                            @if($variant->approval_all_mandants == true && count($variants) <= 1 )
                                                
                                                <option value="0"></option>
                                                <option value="Alle" selected>Alle</option>
                                                    @foreach( $mandants as $mandant)
                                                        <option value="{{$mandant->id}}">({{ $mandant->mandant_number }}) {{ $mandant->kurzname }}</option>
                                                    @endforeach
                                            @elseif( ViewHelper::countComplexMultipleSelect($variant,'documentMandantMandants',true) == false)
                                               
                                                <option value="0"></option>
                                                    @if( count($variants) <= 1)
                                                        <option value="Alle" selected>Alle</option>
                                                    @endif
                                                 @foreach( $mandants as $mandant)
                                                    <option value="{{$mandant->id}}">({{ $mandant->mandant_number }}) {{ $mandant->kurzname }}</option>
                                                @endforeach
                                            @else
                                                <option value="0"></option>
                                                 @if( count($variants) < 2 )
                                                    <option value="Alle">Alle</option>
                                                 @endif
                                                @foreach($mandants as $mandant)
                                                    <option value="{{$mandant->id}}"
                                                          {!! ViewHelper::setComplexMultipleSelect($variant,'documentMandantMandants', $mandant->id, 'mandant_id',true) !!}
                                                    >({{ $mandant->mandant_number }}) {{ $mandant->kurzname }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="clearfix"></div>
                            @endforeach
                        @endif
                      
                        <div class="col-md-12">
                            <br>
                            @if( isset($backButton) )
                                <a href="{{$backButton}}" class="btn btn-info no-margin-bottom">
                                    <!--<span class="fa fa-chevron-left"></span> -->
                                    Zurück</a>
                            @endif
                            
                            
                            @if( $data->document_status_id != 3 )
                                @if( ViewHelper::universalHasPermission(array(8) )
                                || (ViewHelper::universalDocumentPermission($data, false,false, true) == true && $data->document_status_id ==2 ) )
                                    @if($data->documentType->publish_sending == true)
                                        @if(count($data->documentMandants))
                                            <a class="btn btn-info no-margin-bottom" data-toggle="modal" data-target="#publishModal" href="#">
                                                {{ trans('rightsRelease.fastPublish') }}
                                            </a>
                                        @else
                                            <button class="btn btn-info no-margin-bottom" disabled title="{{ trans('rightsRelease.rigtsSaveRequired') }}">
                                                {{ trans('rightsRelease.fastPublish') }}
                                            </button>
                                        @endif
                                    @else
                                        <button type="submit" class="btn btn-info no-margin-bottom no-validate" name="fast_publish" value="fast_publish">
                                            {{ trans('rightsRelease.fastPublish') }}
                                        </button>
                                    @endif
                                @endif
                                
                                {{-- NEPTUN-815, NEPTUN-817 --}}
                                @if($data->document_status_id == 1)
                                <button type="submit" class="btn btn-primary no-margin-bottom validate"  name="ask_publishers" value="ask_publishers">
                                    {{ trans('rightsRelease.share') }}
                                </button>
                                @endif
                                
                                {{-- NEPTUN-824 --}}
                                @if(in_array($data->document_status_id, [2, 6]))
                                <button type="submit" class="btn btn-primary no-margin-bottom no-validate"  name="reset_approval" value="reset_approval">
                                    {{ trans('rightsRelease.approvalReset') }}
                                </button>
                                @endif
                                
                            @endif
                            
                            <button type="submit" class="btn btn-primary no-margin-bottom no-validate"  name="save" value="save">
                                {{ trans('rightsRelease.save') }}
                            </button>
                            
                            @if( $data->document_status_id == 2 ) 
                                <a href="{{url('dokumente/'. $data->id .'/edit')}}" class="btn btn-primary no-margin-bottom">
                                    <!--<span class="fa fa-floppy-o"></span>  -->
                                    {{ trans('dataUpload.edit') }}
                                </a>
                            @endif
                            
                        </div>
                    </div>
                </div>
            </div>
        <div class="clearfix"></div>
        
            <!-- modal start -->   
            <div id="publishModal" class="modal fade draggable" tabindex="-1" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hiddetn="true">&times;</span>
                            </button>
                            <h4 class="modal-title">{{ trans('rightsRelease.fastPublish') }}</h4>
                        </div>
                        <div class="modal-body">
                            
                            <div class="clearfix"></div> <br>
                            
                            @foreach( $variants as $v => $variant)
                                <div class="attachments document-attachments">
                                    <strong>Variante {{$variant->variant_number}}: </strong> <br>
                                    <div>
                                        {{ trans('documentForm.email') }}: {{ ViewHelper::countSendingRecievers($variant->document_id, $variant->variant_number, 1) }} <br>
                                        {{ trans('documentForm.email-attachment') }}: {{ ViewHelper::countSendingRecievers($variant->document_id, $variant->variant_number, 2) }} <br>
                                        {{ trans('documentForm.fax') }}: {{ ViewHelper::countSendingRecievers($variant->document_id, $variant->variant_number, 3) }} <br>
                                        {{ trans('documentForm.mail') }}: {{ ViewHelper::countSendingRecievers($variant->document_id, $variant->variant_number, 4) }} <br>
                                        
                                        @if($data->pdf_upload)
                                            @foreach($data->documentUploads as $k => $attachment)
                                                @if($k > 0) @break @endif <a href="{{url('download/'. $data->id .'/'. $attachment->file_path)}}">PDF Rundschreiben ausdrucken</a><br>
                                            @endforeach
                                        @else
                                            <a href="{{ url('/dokumente/' . $variant->document_id . '/pdf/download/'. $variant->variant_number) }}">PDF ausdrucken</a><br>
                                        @endif
                                        
                                        <a href="{{ url('/dokumente/' . $variant->document_id . '/post-versand/'. $variant->variant_number) }}" target="_blank">PDF Liste aller Post Versand Personen</a><br>
                            
                                        @if( count( $variant->EditorVariantDocument ) )            
                                            Anlagen fűr Variante {{$variant->variant_number}}:<br>
                                            @foreach($variant->EditorVariantDocument as $k =>$docAttach)
                                                @if( $docAttach->document_id != $data->id )
                                                    @foreach( $docAttach->document->documentUploads as $key=>$docUpload)
                                                        @if( $key == 0 )
                                                            <div class="row flexbox-container">
                                                                <div class="col-md-12">
                                                                    <a href="{{ url('download/'. $docAttach->document->id .'/'.$docUpload->file_path) }}">
                                                                        {!! ViewHelper::stripTags($docAttach->document->name, array('p' ) ) !!}
                                                                    </a> <br>
                                                                </div>
                                                            </div>
                                                            <div class="clearfix"></div>
                                                        @endif
                                                    @endforeach
                                                @endif
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                                <div class="clearfix"></div> <br>
                            @endforeach
                        </div>
                        
                        <div class="modal-footer text-right">
                            <button type="submit" class="btn btn-info no-margin-bottom no-validate" name="fast_publish_send" value="fast_publish_send">
                                {{ trans('documentForm.fast-publish-send') }}
                            </button>
                            <button type="submit" class="btn btn-info no-margin-bottom no-validate" name="fast_publish" value="fast_publish">
                                {{ trans('documentForm.fast-publish-only') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>  <!-- modal end -->  
        
        </form>
        
        
        
    @stop
    
@if( isset( $data->document_type_id ) )
   @section('preScript')
       <!-- variable for expanding document sidebar-->
       <script type="text/javascript">
            var documentType = "{{ $data->documentType->name}}";
            var documentSlug = "{{ str_slug($data->documentType->name)}}";
      
              
       </script>
       
       <!--patch for checking iso category document-->
        @if( isset($document->isoCategories->name) )
            <script type="text/javascript">   
                if( documentType == 'ISO Dokument')
                    var isoCategoryName = '{{ $data->isoCategories->name}}';
            </script>
        @endif
       <!-- End variable for expanding document sidebar-->
   @stop
@endif