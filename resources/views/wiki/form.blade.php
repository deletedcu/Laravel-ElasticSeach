@section('page-title') {{ trans('controller.wiki') }} 
@if( Request::is('*/edit') ) {{ trans('benutzerForm.edit') }} @else - Seite anlegen @endif 
@stop

<input type="hidden" name="user_id" value="{{ Auth::user()->id }}" />
<div class="col-md-12 box-wrapper"> 
    <div class="box box-white">
        <div class="row">
            <!-- input box-->
            <div class="col-md-4 col-lg-4"> 
                <div class="form-group">
                    {!! ViewHelper::setInput('name',$data,old('name'),trans('wiki.name'),
                    trans('wiki.name') ,true  ) !!}
                </div>   
            </div><!--End input box-->
            
            <!-- input box-->
            <div class="col-md-4 col-lg-4"> 
            
               <div class="form-group">
                     {!! ViewHelper::setSelect($wikiStatuses,'status_id',$data,old('status_id'),
                            trans('wiki.status'), trans('wiki.status'),true ) !!}
                </div>   
            </div><!--End input box-->
            
            <!-- input box-->
            <div class="col-md-4 col-lg-4"> 
                <div class="form-group document-type-select">
                    {!! ViewHelper::setSelect($wikiCategories,'category_id',$data,old('category_id'),
                            trans('wiki.category'), trans('wiki.category'),true ) !!}
                </div>   
            </div><!--End input box-->
            
            
            <!-- input box-->
            <!--<div class="col-md-4 col-lg-4"> 
                <div class="form-group">
                    {!! ViewHelper::setInput('subject',$data,old('subject'),trans('wiki.subject'),
                    trans('wiki.subject') ,true  ) !!}
                </div>   
            </div>-->
            <!--End input box-->
            
         <!-- input box-->
            <!--<div class="col-md-4 col-lg-4"> -->
            <!--    <div class="form-group no-margin-top">-->
            <!--        {!! ViewHelper::setCheckbox('allow_all',$data,old('allow_all'),trans('wiki.allowAll') ) !!}-->
            <!--    </div>   -->
            <!--</div>-->
            <!--End input box-->
            
            <div class="clearfix"></div>
            <div class="col-xs-12">
                <div class="variant" data-id='content'>
                    @if( isset($data->content) )
                        {!! $data->content !!}
                    @endif
                </div>
            </div>
            <div class="clearfix"></div>
          
            
            
        </div><!--end row-->
    </div><!-- end box-->

            
            <div class="clearfix"></div>
            <br/>