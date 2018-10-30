 <!-- input box-->
        <div class="col-lg-3"> 
            <div class="form-group">
                {!! ViewHelper::setSelect($collections,'steuernummer_lohn',$data,old('steuernummer_lohn'),
                trans('mandantenForm.role'), trans('mandantenForm.role')  ) !!}
            </div>   
        </div><!--End input box-->
         <div class="col-lg-3"> 
            <div class="form-group">
                {!! ViewHelper::setSelect($collections,'steuernummer_lohn',$data,old('steuernummer_lohn'),
                trans('mandantenForm.user'), trans('mandantenForm.user')  ) !!}
            </div>   
        </div><!--End input box-->
        <div class="clearfix"></div>