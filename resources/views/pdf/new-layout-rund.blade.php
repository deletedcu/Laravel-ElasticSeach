<!DOCTYPE html>
<html lang="hr">
    <head>
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
      <title>@yield("title",'Neptun dokument')</title>
      <link rel="shortcut icon" href="/img/favicon.png">
        <style type="text/css">
             @page {
                header: page-header;
                footer: page-footer;
                font-family: "Arial", sans-serif, "Helvetica Neue", Helvetica !important;
                
            }
            @page:first {
                margin-top: 0px !Important;
                
            }
            .number-1{
                display: none;
            }
            /*sub{*/
            /*    font-size: 22px !important;*/
            /*}*/
             ul, li, ul li{
                font-family: "Arial", sans-serif, "Helvetica Neue", Helvetica !important;
                font-size: 14px !important;
                margin-left: 7px;
                padding-left: 0px;
                line-height: 16px !important;
                
            }
            ol, ol li{
                margin-left: 5px;
                padding-left: 5px;
                line-height: 16px !important;
                font-size: 14px !important;
            
            }
            .list-style-dash{
                list-style-type: none;
                margin-left: -10px;
                padding-left: 5px;
                line-height: 16px !important;
             }
              a{
                    color: #337ab7;
                    text-decoration: none;
                }
             .list-style-dash li {
                background-image:  url('/img/icons/icon_list_dash.png') !important;
                background-repeat: no-repeat !important;
                background-position: 0px 50% !important;
                padding-left: 1em !important;
            }
            table td ul li, table td ol li, table td p, table td em, table td u, table td b, table td strong, table td i {
                font-family: "Arial", sans-serif, "Helvetica Neue", Helvetica !important;
                font-size: 14px !important;
                /*line-height: 18px !important;*/
            }
            
            body,table,p,strong,li,h1,h2,h3,span,b,i{
                font-family: "Arial", sans-serif, "Helvetica Neue", Helvetica !important;
            }
            p,li{
                font-size: 14px ;
                background: #fff !important;
                background-color: #fff !important;
            }
            h1{
                font-size: 2.1em !important;
                font-weight: normal !important;
                margin-top: 17px !important;
                margin-bottom: 4px !important;
                -webkit-margin-before:10px !important;
                -webkit-margin-after:20px !important;
                -webkit-margin-end:0px !important;
                -webkit-margin-start:0px !important;
            }
             h2{
                font-size: 1.6em !important;
                font-weight: normal !important;
                margin : 0 !important;
                -webkit-margin-before: 18.592px !important;
                -webkit-margin-after: 18.592px !important;
                -webkit-margin-start: 0px;
                -webkit-margin-end: 0px;
            }
             h3{
                font-size: 1.27em !important;
                font-weight: normal !important;
                margin : 0 !important;
                -webkit-margin-before: 17.78px !important;
                -webkit-margin-after: 17.78px !important;
                -webkit-margin-start: 0px;
                -webkit-margin-end: 0px;
            }
            
            h1,h3,h3{
                font-family: "Arial", sans-serif, "Helvetica Neue", Helvetica !important;
                font-weight: normal !important;
            }
            h1 em, h1 strong, h2 em, h2 strong, h3 em, h3 strong{
                font-size: 1em !important;
            }
             h1 > strong, h2 > strong , h3 > strong{
                font-weight: bolder	 !important;
            }
            h1 sub, h2 > sub, h2 > em > sub, h2 > strong > sub, h3 > sub, 
            h3 > em > sub, h3 > strong > sub{
               font-size: smaller !important;
            }
            
            p{
              margin: 14px 0;
            }
            table{
                /*font-size: 30px !important;*/
                /*height: auto !Important;*/
                border-collapse: collapse !important;
            }
            table td{
                margin: 5px 0 !important;
                padding: 5px !important;
                vertical-align: middle !important;
                font-size: 14px !important;
            }
            table td ul li, table td ol li, table td p, table td em, table td u, table td b, table td strong, table td i {
                font-family: "Arial", sans-serif, "Helvetica Neue", Helvetica !important;
                font-size: 14px !important;
                /*line-height: 18px !important;*/
            }
            table ol, table ul{
                padding: 5px !important;
                margin: 5px!important;
                /*line-height: 38px !important;*/
                /*font-size: 33px !important;*/
                -webkit-margin-before: 0 !important;
                -webkit-margin-after: 0 !important;
                -webkit-margin-start: 0 !important;
                -webkit-margin-end: 0 !important;
                -webkit-padding-start: 20px !important;
                moz-padding-start: 20px !important;
            }  
            
            .text-upper{
                text-transform: uppercase;
            }
            .bold{
                font-weight: bold;
            }
            .page-num-p{
                text-align:right; 
                font-size:14px;
            }
            .mb30{
                margin-bottom: 30px;
            }
            .mb60{
                margin-bottom: 60px;
            }
            .mb90{
                margin-bottom: 90px;
            }
            .clearfix{
                clear: both !important;
                height:1px;
            }
            .half-width{
                width:50% !important;
                float:left;
            }
            
            .footer-box{
                margin-top:10px;
                width: 19.5%;
                float: left;
                padding-left:10px;
                
            }
            .footer-box p{
                margin-top: 20px;
                /*padding-top: 10px;*/
                font-size: 9px !Important;
                color: #425a61;
            }
            
            .page-number{
                text-align: center;
                margin-top: -110px;
                color: black;
                z-index:999999;
                font-size: 12px;
                background: transparent;
            }
            .footer-line-right img{
                height: 100px;
            }
           
           .box-1, .box-3, .box-4{
                border-left: 1px solid #5f5f5f;
            }
            .box-2{
                border-left: 1px solid #1552ff;
            }
           
            
        </style>
        @if( $document->landscape == true)
            <style>
            body,p,h1,h2,h3,h4,h5{
                font-family: "Arial", sans-serif, "helvetica Neue", Helvetica !important;
            }
            p{
                font-size: 14px;
                /*margin-bottom: 25px;*/
            }
            .header,
            .footer {
                /*width: 100%;*/
                /*position: fixed;*/
            }
           .header{
               width: 120% !Important;
                margin: -0px -40px 0 -18px !important;
            }
            .div-pusher{
                width:50%;
                /*padding-left:30%;*/
            }
            
            .first-title.first{
                margin-top: 0px;
                 margin-bottom:0px;
            }
            .first-title.second{
                margin-top: 0;
                margin-bottom:10px;
            }
             .first-title, .content-wrapper{
                padding: 0 70px 10px 70px;
            }
            .document-title-row{
                width: 70%;
                float:left;
            }
            .document-date-row{
                width: 30%;
                float:right;
                font-size: 14px;
                padding-top: 0px;
            }
            .date-div{
                width:100%;
                float:right !important;
                text-align: right;
            }
            .date-div .right-correction{
                margin-right: -5px;
            }
            
            .clearfix{
                clear: both !important;
                height:1px;
            }
            .half-width{
                width:50% !important;
                float:left;
            }
           .footer { 
                position: fixed; 
                bottom: 0; 
                left: 0;
                margin-left: -155px;
                margin-right: -160px;
                padding-top: 50px;
                /*width: 1005px;*/
                
            }
            .absolute{
                margin-left: -35px;
                margin-right: -45px;
                right: 0;
                font-size: 10px !important;
                padding-bottom: 0px;
            }
            .page-number{
                margin-top: -30px;
            }
            .footer-box{
                margin-top: 40px !important;
            }
             .footer-line{
                width: 8.5%;
                float:left;
            }
            .footer-line-left  {
                /*border: 1px solid red;*/
                /*margin-top:10px;*/
                /*margin-left:-5px;*/
                width:3%;
            }
            .left-image{
                max-height: 135px;
            }
            .footer-line-right  {
                /*border: 1px solid red;*/
                /*padding-top:5px;*/
                /*margin-top:20px;*/
                margin-right: -40px;
                width:7.5%;
            }
            .box-1{
                width: 20%;
                /*margin-left: 15px;*/
            }
            
            .box-2{
                margin-top: -2px;
            }
            .box-3{
                margin-top: 5px;
            }
            .box-3 p{
                margin-top: 15px;
            }
            .box-4{
                width: 16%;
                margin-top: -5px;
                margin-left: 20px;
            }
        </style><!--end landscape css-->
        @else
            <style>
            body{ padding-top:20px; }
            .header{
                margin: -10px -40px 0 -40px;
            }
            .div-pusher{
                width:50%;
                padding-left:30%;
            }
            .footer { 
                position: fixed; 
                bottom: 0; 
                left: 0;
                margin-left: -155px;
                margin-right: -150px;
                padding-top: 30px;
                /*width: 1005px;*/
                
            }
            .absolute{
                
                /*margin-top: -45px;*/
                margin-left: -35px;
                margin-right: -45px;
                
                right: 0;
                font-size: 10px !important;
                padding-bottom: 0px;
            }
            .footer-line{
                width: 7%;
                float:left;
            }
            .footer-line-left  {
                /*border: 1px solid red;*/
                margin-top:10px;
                margin-left:-5px;
                width:8.5%;
            }
            .footer-line-right  {
                /*border: 1px solid red;*/
                /*padding-top:5px;*/
                margin-top:4px;
                /*margin-right: -20px;*/
                width:9.5%;
            }
            
            .box-1{
                width: 20%;
                margin-left: 15px;
            }
            
            .box-2{
                margin-top: -2px;
            }
            .box-3{
                margin-top: 5px;
            }
            .box-3 p{
                margin-top: 15px;
            }
            .box-4{
                width: 16%;
                margin-top: -5px;
                margin-left: 5px;
            }
           
           
            .first-title.first{
                margin-top: -10px;
                margin-bottom: 10px;
            }
            .first-title.second{
                margin-top: 0;
                margin-bottom:50px;
            }
            .content-wrapper{
                padding: 0 90px 10px 30px;
            }
             .first-title, .content-wrapper{
                padding: 0 20px 10px 30px;
            }
            .document-title-row{
                width: 70%;
                float:left;
            }
            .document-date-row{
                width: 30%;
                float:right;
                font-size: 14px;
                margin-top: -60px;
                /*padding-top: 7px;*/
            }
            .date-div{
                width:100%;
                float:right !important;
                text-align: right;
            }
            .date-div .right-correction{
                margin-right: -5px;
            }
            
         </style>
        @endif
    </head>
    <body style=" font-family: 'Arial', Arial">
        
        
     <!-- if you want header on every page  set the include pdf.header here -->
      
      <div style="">
           {{-- @include('pdf.new-layout-rund-header')--}}
          <h4 class="first-title first" style="float:left; ">
            @if( $document->document_type_id == 3 )
              QMR
            @else
                {{ $document->documentType->name }}
            @endif
              @if( $document->document_type_id == 3 )
                  @if( $document->qmr_number != null)
                      {{ $document->qmr_number }}@if( $document->additional_letter ){{ $document->additional_letter }}  @endif
                  @endif
              @elseif( $document->document_type_id == 4 )
                  @if( $document->iso_category_number != null)
                      {{ $document->iso_category_number }}@if( $document->additional_letter ){{ $document->additional_letter }}  @endif
                  @endif
              @endif
          </h4>
          <!--<div class="div-pusher"></div>-->
          <div class="content-wrapper">
              <div class="row">
                    <div class="document-title-row">
                           @if( $document->adressat_id != null && $document->show_name == 1 )
                          <h4 class="document-adressat">{{$document->documentAdressats->name}}</h4>
                        @endif
                    </div>
                  <div class="document-date-row">
                      <div class="date-div"><p>
                          @if( $document->date_published != null)
                              <span class="right-correction">{{$document->date_published}}</span>
                          @endif
                          <br/>
                            {{-- Inverted at the end of the project --}}
                            @if(!empty($document->owner->short_name)) {{ $document->owner->short_name }} @endif @if( !is_null($document->user) && !empty($document->user->short_name) )| {{ $document->user->short_name }} @endif
                          </p></div>
                  </div>
                  <div class="clearfix"></div>
                </div><!--end row-->  
              <div class="clearfix"></div>
              <div class="row">
                  <h4 class="document-title">{!! $document->name_long !!}</h4>
              </div>
              @if( count( $variants) )
                  @foreach( $variants as $v => $variant)
                      @if( isset($variant->hasPermission) && $variant->hasPermission == true )
                      <div>
                          {!! ($variant->inhalt) !!}
                      </div>
                      @endif
                  @endforeach
              @endif    
          </div>
          
      </div>

      <htmlpagefooter name="page-footer">
           @include('pdf.footer')      
       </htmlpagefooter>
     </body>
</html>