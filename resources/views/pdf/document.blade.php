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
                width: 100%;
                /*position: fixed;*/
            }
            .header {
                /*top: -15px;*/
                
               
            }
            .div-pusher{
                width:50%;
                /*padding-left:30%;*/
            }
            .header .div-pusher{
                width:80%;
                padding-left:30%;
            }
            .header .image-div {
                width:20%;
                float:right !important;
                padding-left:50px;
                height:auto;
            }
            .header .image-div img{
               margin-left:0px;
               width:100%;
               height:auto;
               display:block;
            }
            .footer {
                bottom: 5px;
            }
            .pagenum:before {
                content: counter(page);
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
                padding: 0 120px 10px 30px;
            }
            .document-title-row{
                width: 70%;
                float:left;
            }
            .document-date-row{
                width: 30%;
                float:right;
                font-size: 14px;
                padding-top: 7px;
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
            .footer .half-width{
               
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
            table {
                margin-left: 0 !important;
              
                /*width: 100% !important;*/
                /*margin-right: 30pt !important;*/
            }
            table td{
                 /*width: auto !important;*/
            }
            #absolute{
                 font-size: 10px !important;
                 margin-top: -125px;
                 /*margin-left: 600px;*/
                 float: right;
                 margin-right: -40px;
                 padding-bottom: 10px;
            }
             .footer { 
                 position: fixed; 
                 bottom: 5px; right: 0px;
                 /*background: red;*/
                 
             }
            .absolute, .absolute:nth-child(even){
                width: 125px;
                /*margin-top: -135px;*/
                /*margin-left: 300px;*/
                color: #808080;
            }
            .absolute p{
                margin-bottom: 0 !important;
                margin-top: 0 !important;
                text-align: left;
                 color: #808080;
                font-size: 10px !important;
            }
            .page-number{
                /*margin-left: 345px;*/
                text-align: center;
                margin-top: -28px;
                background: transparent;
                color: black;
                z-index:999999;
                font-size: 12px;
            }
        </style><!--end landscape css-->
        @else
            <style>
            @page {
                    margin-right: 20px !important;
                }
            body,p,h1,h2,h3,h4,h5{
                font-family: "Arial", sans-serif, "helvetica Neue", Helvetica !important;
            }
            p{
                font-size: 14px;
                /*margin-bottom: 25px;*/
            }
            /*.header,*/
            /*.footer {*/
            /*    width: 100%;*/
            /*    position: fixed;*/
            /*}*/
            .header {
                margin-top: -25px;
                padding:0 40px 0 30px
            }
            .div-pusher{
                width:50%;
                padding-left:30%;
            }
            .header .div-pusher{
                width:60%;
                padding-left:30%;
            }
            .header .image-div {
                width:40%;
                float:right !important;
                padding-left:55px;
                padding-top: 0px;
                margin-top: -20px;
                height:auto;
            }
            .header .image-div img{
               margin-left:0px;
               width:100%;
               height:auto;
               display:block;
            }
            .footer {
                bottom: 5px;
            }
            .pagenum:before {
                content: counter(page);
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
                padding: 0 115px 10px 30px;
            }
            .document-title-row{
                width: 70%;
                float:left;
            }
            .document-date-row{
                width: 30%;
                float:right;
                font-size: 14px;
                margin-top: -20px;
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
            
            .clearfix{
                clear: both !important;
                height:1px;
            }
            .half-width{
                width:50% !important;
                float:left;
            }
            .footer .half-width{
               
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
            table {
                margin-left: 0 !important;
                /*width: 100% !important;*/
                /*margin-right: 30pt !important;*/
            }
            table td{
                 /*width: auto !important;*/
            }
            #absolute{
                 font-size: 10px !important;
                 /*margin-top: -125px;*/
                 /*margin-left: 300px;*/
            }
            .footer { position: fixed; bottom: 10px; left: 350px; }
            .absolute, .absolute:nth-child(even){
                width: 105px;
                margin-top: -285px;
                margin-left: 650px;
                right: 0;
                color: #808080;
                font-size: 10px !important;
                padding-bottom: 10px;
                
                
            }
            .absolute p{
                margin-bottom: 0 !important;
                margin-top: 0 !important;
                text-align: left;
                font-size: 10px !important;
                
            }
            .page-number{
                /*margin-left: 345px;*/
                text-align: center;
                margin-top: -30px;
                color: black;
                z-index:999999;
                font-size: 12px;
                background: transparent;
                
            }
        </style>
        @endif
    </head>
    <body style=" fon-family: 'Arial', Arial">
        
        
     <!-- if you want header on every page  set the include pdf.header here -->
      
      <div >
           @include('pdf.header')
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
                              <span class="right-correction">{{$document->date_published}}</span>
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