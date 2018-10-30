<!DOCTYPE html>
<html lang="hr">
    <head>
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
      <title>@yield("title",'Neptun dokument')</title>
      <link rel="shortcut icon" href="/img/favicon.png">
          <style type="text/css">
             .list-style-dash {
                    list-style-image: url('/img/icons/icon_list_dash.png') !important;
                }
                body,table,p,strong,li,h1,h2,h3,span,b,i{
                    font-family: "Arial", sans-serif, "Helvetica Neue", Helvetica !important;
                }
                p,li{
                    font-size: 14px ;
                    line-height: 16px;
                }
                
                p{
                    margin: 14px 0 12px 0!important;/*This is actually 14px 0 but 15 is set as an print correction*/
                }
                
                table td p {
                    /*margin-top: 5px;*/
                    /*margin-bottom: 5px;*/
                    /*line-height: 22px;*/
                }
                
                 h1{
                        font-size: 2.1em !important;
                    }
                     h2{
                        font-size: 1.6em !important;
                    }
                     h3{
                        font-size: 1.27em !important;
                    }
                img,h1,h2,h3,h4,p,div{
                    display:block !important;
                    clear: both !important;
                }
                table{
                    font-size: 14px;
                    /*height: auto !Important;*/
                    border-collapse: collapse !important;
                    max-width: 100% !important;
                    vertical-align: middle !important;
                }
                
            table td{
                font-family: "Arial", sans-serif, "Helvetica Neue", Helvetica !important;
                /*vertical-align: middle !important;*/
                padding: 1px;
                line-height: 16px !important;
                font-size: 14px !important;
            }
            
          </style>
        @if( $document->landscape == true)
            <style>
            body,p,h1,h2,h3,h4,h5{
                font-family: "Arial", sans-serif, "helvetica Neue", Helvetica !important;
            }
            p{
                font-size: 14px;
                margin-bottom: 25px;
            }
            .header,
            .footer {
                width: 100%;
                position: fixed;
            }
            .header {
                top: -15px;
            }
            .div-pusher{
                width:50%;
                padding-left:30%;
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
                margin-top: 40px;
                 margin-bottom:40px;
            }
            .first-title.second{
                margin-top: 0;
                margin-bottom:20px;
            }
             .first-title, .content-wrapper{
                padding: 0 80px 10px 30px;
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
                 margin-left: 300px;
            }
             .footer { position: fixed; bottom: 5px; left: 680px; }
            .absolute, .absolute:nth-child(even){
                width:85px;
                margin-top: -125px;
                margin-left: 300px;
                color: #808080;
            }
            .absolute p{
                margin-bottom: 0 !important;
                margin-top: 0 !important;
                text-align: left;
            }
        </style><!--end landscape css-->
        @else
            <style>
            body,p,h1,h2,h3,h4,h5{
                font-family: "Arial", sans-serif, "helvetica Neue", Helvetica !important;
            }
            p{
                font-size: 14px;
                margin-bottom: 25px;
            }
            .header,
            .footer {
                width: 100%;
                position: fixed;
            }
            .header {
                top: -15px;
                position: relative;
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
                position: absolute;
                margin-bottom: 70px;
                top: 70px;
                z-index: 100;
            }
            .first-title.second{
                margin-top: 0;
                margin-bottom:50px;
            }
             .first-title, .content-wrapper{
                padding: 0 80px 10px 31px;
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
            /*#absolute{*/
            /*     font-size: 10px !important;*/
            /*     margin-top: -125px;*/
            /*     margin-left: 300px;*/
            /*}*/
            /*.footer { position: fixed; bottom: 10px; left: 350px; }*/
            /*.absolute, .absolute:nth-child(even){*/
            /*    width:85px;*/
            /*    margin-top: -125px;*/
            /*    margin-left: 300px;*/
            /*    color: #808080;*/
                
            /*}*/
            .absolute p{
                margin-bottom: 0 !important;
                margin-top: 0 !important;
                text-align: left;
            }
        </style>
        @endif
        
        
    </head>
    <body>
        
     <!-- if you want header on every page  set the include pdf.header here -->
      
      <div id="content">
           @include('pdf.header')
          <h4 class="first-title first">
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
              <div class="row ">
                    <div class="document-title-row">
                         @if( $document->adressat_id != null && $document->show_name == 1 )
                          <h4 class="document-adressat">{{$document->documentAdressats->name}}</h4>
                        @endif
                    </div>
                  <div class="document-date-row">
                      <div class="date-div"><p>
                           @if( $document->published_at != null)
                              <span class="right-correction">{{$document->published_at}}</span>
                           @elseif( $document->date_published != null)
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

     </body>
</html>