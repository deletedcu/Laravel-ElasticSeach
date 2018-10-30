@extends('master')

@section('page-title') Dokument anlegen -  Anlage/n @stop

@section('content')

        
        <div class="col-xs-12 col-md-12 box">
            <div class="tree-view" data-selector="test">
             <div  class="test hide" >{{$data}}</div>
            </div>
        </div>
        
        <div class="clearfix"></div>
        
        <div class="col-xs-12 col-md-6">
            {!! Form::open([
           'url' => '/search',
           'method' => 'POST',
           'class' => 'horizontal-form' ]) !!}
           
                <!-- input box-->
                <div class="col-lg-6">
                    <div class="input-group">
                        <!-- input box-->
                        {!! ViewHelper::setSelect($collections,'document_type_id',$data,old('document_type_id'),
                               'QMR', 'QMR' ) !!}
                    
                        <span class="custom-input-group-btn">
                            <button type="submit" class="btn btn-primary">
                                <!--<span class="fa fa-search"></span> -->
                                {{ trans('navigation.search') }} 
                            </button>
                        </span>
                    </div>   
                </div><!--End input box-->
                <!-- input box-->
                <div class="col-lg-6">
                    <div class="">
                      
                      
                    </div>   
                </div><!--End input box-->
           </form>
        </div>
        
        <div class="clearfix"></div>
        <div class="col-xs-12 col-md-12 box">
            <h3>Option 2:{{ trans('rundschreibenQmr.allQmr')}}</h3>
            {!! Form::open([
           'url' => '/search',
           'method' => 'POST',
           'class' => 'horizontal-form' ]) !!}
           
            <!-- input boxg-->
                <div class="col-lg-3"> 
                    <div class="form-group">
                        {!! ViewHelper::setSelect($collections,'document_type_id',$data,old('document_type_id'),
                                trans('documentForm.documentType'), trans('documentForm.type') ) !!}
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
                <div class="col-lg-3"> 
                    <div class="form-group">
                        {!! ViewHelper::setInput('search_tags',$data,1,trans('documentForm.Ersteller') , 
                               trans('documentForm.Ersteller') , true  ) !!} <!-- add later data-role="tagsinput"-->
                    </div>   
                </div><!--End input box-->
                
                <!-- input box-->
                <div class="col-lg-3"> 
                    <div class="form-group">
                        {!! ViewHelper::setInput('search_tags',$data,1,trans('documentForm.Erstellungsdatum (auto)') , 
                               trans('documentForm.Erstellungsdatum (auto)') , true  ) !!} <!-- add later data-role="tagsinput"-->
                    </div>   
                </div><!--End input box-->
                
                <!-- input box-->
                <div class="col-lg-3"> 
                    <div class="form-group">
                        {!! ViewHelper::setInput('search_tags',$data,1,trans('documentForm.Version (auto)') , 
                               trans('documentForm.Version (auto)') , true  ) !!} <!-- add later data-role="tagsinput"-->
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
                <div class="col-lg-6"> 
                <br/>
                    <div class="form-group">
                        <input type="file" name="file" class="form-control" required />
                    </div>   
                </div><!--End input box-->
                
                
                <div class="clearfix"></div>
                <div class="col-xs-12 col-md-4">
                    <a href="#" class="btn btn-primary">
                        <!--<span class="fa fa-angle-left"></span> -->
                        Back</a>    
                    <button type="submit" class="btn btn-primary">Rechte and freigabe</button>    
                </div>
            </form>    
        </div>
        <div class="clearfix"></div>
    @stop
    