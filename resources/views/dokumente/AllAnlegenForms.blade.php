<h3>Dokumente anlegen - Daten Eingabe - Dokumentenart - Editor</h3>
<!-- input box-->
<div class="col-lg-3"> 
    <div class="form-group">
        {!! ViewHelper::setCheckbox('show_name',$data,old('show_name'),trans('documentForm.showName') ) !!}
    </div>   
</div><!--End input box-->

<div class="clearfix"></div>

<!-- input box-->
<div class="col-lg-3"> 
    <div class="form-group">
        {!! ViewHelper::setSelect($collections,'adressat_id',$data,old('do'),
                trans('documentForm.documentType'), trans('documentForm.documentType') ) !!}
    </div>   
</div><!--End input box-->

<!-- input box-->
<!--<div class="col-lg-3"> -->
<!--    <div class="form-group">-->
<!--        {!! ViewHelper::setInput('betreff',$data,old('betreff'),trans('documentForm.subject') , -->
<!--               trans('documentForm.subject') , true  ) !!}-->
<!--    </div>   -->
<!--</div><!--End input box-->-->
<div class="clearfix"></div>
    <div class="parent-tabs col-xs-12 col-md-12">
    <hr/>
      <!-- Tab panes -->
    <a href="#" class="btn btn-primary add-tab">
        <!--<span class="fa fa-plus"></span> -->
        Neue Variante</a>

    <ul class="nav nav-tabs" id="tabs">
       
    </ul>
    
    <div class="tab-content">
        
    </div>
  </div>

<div class="clearfix"></div>


<p>pagination test</p>
<small>first</small>
<div class="pagination" >
    @if( isset($paginate) && !empty($paginate) ) 
       @foreach ($paginate as $d)
            {{ $d->name }}
            <hr/>
        @endforeach
    
        {!! $paginate->links() !!}
    
    @else
        <p class="text-danger">No data found</p>
    @endif
</div>
<hr/>
<small>second</small>
@if( isset($paginate2) && !empty($paginate2) ) 
       @foreach ($paginate2 as $d)
            {{ $d->name }}
            <hr/>
        @endforeach
    
        {!! $paginate2->links() !!}
    
    @else
        <p class="text-danger">No data found</p>
    @endif
<hr/>
<hr/>
<!-- input box-->
<div class="col-lg-3"> 
    <div class="form-group">
        {!! ViewHelper::setSelect($collections,'document_type_id',$data,old('document_type_id'),
                trans('documentForm.documentType'), trans('documentForm.type') ) !!}
    </div>   
</div><!--End input box-->

<!-- input box-->
<div class="col-lg-3"> 
    <div class="form-group">
        {!! ViewHelper::setInput('name',$data,old('name'),trans('documentForm.documentName') , 
               trans('documentForm.documentName') , true  ) !!}
    </div>   
</div><!--End input box-->


<!-- input box-->
<div class="col-lg-3"> 
    <div class="form-group">
        {!! ViewHelper::setSelect($collections,'owner_user_id',$data,old('owner_user_id'),
                trans('documentForm.owner'), trans('documentForm.owner') ) !!}
    </div>   
</div><!--End input box-->

<!-- input box-->
<div class="col-lg-3"> 
    <div class="form-group">
        {!! ViewHelper::setInput('search_tags',$data,old('search_tags'),trans('documentForm.searchTags') , 
               trans('documentForm.searchTags') , true  ) !!} <!-- add later data-role="tagsinput"-->
    </div>   
</div><!--End input box-->

<!-- input box-->
<div class="col-lg-12">
    <div class="form-group">
        {!! ViewHelper::setArea('summary',$data,old('summary'),trans('documentForm.summary') ) !!}
    </div>   
</div><!--End input box-->  


<!-- input box-->
<div class="col-lg-3"> 
    <div class="form-group">
        {!! ViewHelper::setInput('date_published',$data,old('date_published'),trans('documentForm.datePublished') ) !!}
    </div>   
</div><!--End input box-->


<!-- input box-->
<div class="col-lg-3"> 
    <div class="form-group">
        {!! ViewHelper::setInput('date_expired',$data,old('date_expired'),trans('documentForm.dateExpired') , 
               trans('documentForm.dateExpired') , true  ) !!}
    </div>   
</div><!--End input box-->

<!-- input box-->
<div class="col-lg-3"> 
    <div class="form-group">
        {!! ViewHelper::setSelect($collections,'iso_category_id',$data,old('iso_category_id'),
                trans('documentForm.isoCategory'), trans('documentForm.isoCategory') ) !!}
    </div>   
</div><!--End input box-->


<div class="clearfix"></div>
<hr/>
<hr/>

<h3>Dokumente anlegen - Daten Eingabe - Dokumentenart - Editor</h3>
<!-- input box-->
<div class="col-lg-3"> 
    <div class="form-group">
        {!! ViewHelper::setCheckbox('show_name',$data,old('show_name'),trans('documentForm.showName') ) !!}
    </div>   
</div><!--End input box-->

<div class="clearfix"></div>

<!-- input box-->
<div class="col-lg-3"> 
    <div class="form-group">
        {!! ViewHelper::setSelect($collections,'adressat_id',$data,old('do'),
                trans('documentForm.documentType'), trans('documentForm.documentType') ) !!}
    </div>   
</div><!--End input box-->

<!-- input box-->
<div class="col-lg-3"> 
    <div class="form-group">
        {!! ViewHelper::setInput('betreff',$data,old('betreff'),trans('documentForm.subject') , 
               trans('documentForm.subject') , true  ) !!}
    </div>   
</div><!--End input box-->

<!-- input box-->
<div class="col-lg-12"> 
   <div class="editable"></div>
</div><!--End input box-->

<div class="clearfix"></div>

<!-- input box-->
<div class="col-lg-3"> 
    <div class="form-group">
        {!! ViewHelper::setCheckbox('email_approval',$data,old('email_approval'),trans('documentForm.email_approval') ) !!}
    </div>   
</div><!--End input box-->

<!-- input box-->
<div class="col-lg-3"> 
    <div class="form-group">
        {!! ViewHelper::setCheckbox('approval_all_roles',$data,old('approval_all_roles'),trans('documentForm.approval_all_roles') ) !!}
    </div>   
</div><!--End input box-->

<!-- input box-->
<div class="col-lg-3"> 
    <div class="form-group">
        {!! ViewHelper::setCheckbox('approval_all_mandants',$data,old('approval_all_mandants'),trans('documentForm.approval_all_mandants') ) !!}
    </div>   
</div><!--End input box-->

<!-- input box-->
<div class="col-lg-3"> 
    <div class="form-group">
        {!! ViewHelper::setCheckbox('pdf_upload',$data,old('pdf_upload'),trans('documentForm.pdfUpload') ) !!}
    </div>   
</div><!--End input box-->

<div class="clearfix"></div>

<hr/>
<hr/>



<h3>Dokumente anlegen - Daten Eingabe - Dokumentenart - Editor</h3>
<!-- input box-->
<div class="col-lg-3"> 
    <div class="form-group">
        {!! ViewHelper::setCheckbox('show_name',$data,old('show_name'),trans('documentForm.showName') ) !!}
    </div>   
</div><!--End input box-->

<div class="clearfix"></div>

<!-- input box-->
<div class="col-lg-3"> 
    <div class="form-group">
        {!! ViewHelper::setSelect($collections,'adressat_id',$data,old('adressat_id'),
                trans('documentForm.adressat'), trans('documentForm.adressat') ) !!}
    </div>   
</div><!--End input box-->


<!-- input box-->
<div class="col-lg-3"> 
    <div class="form-group">
        {!! ViewHelper::setInput('betreff',$data,old('betreff'),trans('documentForm.subject') , 
               trans('documentForm.subject') , true  ) !!}
    </div>   
</div><!--End input box-->

<div class="clearfix"></div>

<!-- input box-->
<div class="col-lg-6"> 
    <div class="form-group">
        <input type="file" name="file" class="form-control" required />
    </div>   
</div><!--End input box-->