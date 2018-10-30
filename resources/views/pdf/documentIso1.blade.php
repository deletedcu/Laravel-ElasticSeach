<!DOCTYPE html>
<html lang="de">
    <head>
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
      <title>@yield("title",'Neptun dokument')</title>
      {!! Html::script(elixir('js/script.js')) !!}
      <link rel="shortcut icon" href="/img/favicon.png">
        <style type="text/css">
            .list-style-dash { list-style-image: url('/img/icons/icon_list_dash.png') !important; }
            p,li{
                    font-size: 14px !important;
            }
            table td{
                vertical-align: middle !important;
                border-collapse: collapse !important;
            }
        </style>
        @if( $document->landscape == 1)
            <style>
            body,p,h1,h2,h3,h4,h5{
                font-family: "Arial", sans-serif, "helvetica Neue", Helvetica !important;
            }
            p{
                font-size: 14px;
            }
            
            table td p {
                margin-top: 5px;
                margin-bottom: 5px;
                line-height: 22px;
            }
            table {
                margin-left: 0 !important;
                /*width: 100% !important;*/
                /*margin-right: 30pt !important;*/
            }
            table td{
                 /*width: auto !important;*/
            }
             @page { 
                 margin-top: 130px;
                 margin-bottom: 20px;
                 font-family: "Arial", sans-serif, "helvetica Neue", Helvetica !important;
             }
             
            .clearfix{
                clear: both !important;
                height:1px;
            }
            .header,.footer {
                width: 100%;
                position: fixed;
            }
            .header{
                top: -130px;
                left: 0;
                padding-top: 10px;
                padding-bottom: 20px;
                font-size: 16px !important; 
                clear:both;
            }
            
            .border-wrapper{
                padding:0 135px 0 50px;
            }
             .border-div{
                border-bottom: 1px solid black;
                height: 1px;
                width: 100%;
                /*margin-top:55px;*/
             }
            .header .div-pusher{
                width:50%;
                float: left;
            }
            .header .div-pusher p{
                padding-top: 15px;
                padding-right: 60px;
                font-size: 14px !important; 
            }
            .header .image-div {
                width:50%;
                float:right;
                margin-right: 135px;
            }
            .parent-pagenum{
                margin-bottom:10px;
                padding-right: 10px;
            }
            .pagenum:before {
                content: counter(page);
            }
            .pull-right{
                text-align: right;
            }
            .div-pusher{
                width:50%;
                padding-left: 50px;
                float: left;
            }
            
            .first-title.first{
                /*margin-top: 70px;*/
                margin-bottom:0px;
            }
            .first-title.second{
                margin-top: 0;
                /*margin-bottom:50px;*/
            }
             .content-wrapper{
                padding: 0 135px 10px 50px;
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
            
            .footer {
                position: fixed; 
                bottom: 5px; 
                /*left: 350px; */
                
            }
            
            .footer p{
                /*padding-right: 135px;*/
                text-align: right;
                margin-bottom: 0px !important;
                padding-bottom: 0px !important;
                margin-top: 0 !important;
                padding: 3px 135px 3px 0 !important;
                font-size: 12px;
                line-height: 12px;
            }
        </style>
        @else
            <style>
            body,p,h1,h2,h3,h4,h5{
                font-family: "Arial", sans-serif, "helvetica Neue", Helvetica !important;
            }
            p{
                font-size: 14px;
                margin-bottom: 25px;
            }
            table {
                margin-left: 0 !important;
                /*width: 100% !important;*/
                /*margin-right: 30pt !important;*/
            }
            table td{
                 /*width: auto !important;*/
            }
             @page { 
                 /*margin-top: 150px;*/
                 /*margin-bottom: 100px;*/
                 /*font-family: "Arial", sans-serif, "helvetica Neue", Helvetica !important;*/
             }
             
            .clearfix{
                clear: both !important;
                height:1px;
            }
            .header,.footer {
                width: 100%;
                position: fixed;
            }
            /*.header{*/
            /*    top: -150px;*/
            /*    left: 0;*/
            /*    padding-top: 10px;*/
            /*    padding-bottom: 20px;*/
            /*    font-size: 16px !important; */
            /*    clear:both;*/
            /*}*/
            
            .border-wrapper{
                padding:0 135px 0 50px;
            }
             .border-div{
                border-bottom: 1px solid black;
                height: 1px;
                width: 100%;
                /*margin-top:55px;*/
             }
            .header .div-pusher{
                width:50%;
                float: left;
            }
            .header .div-pusher p{
                padding-top: 15px;
                padding-right: 60px;
                font-size: 14px !important; 
            }
            .header .image-div {
                width:50%;
                float:right;
                margin-right: 135px;
            }
            .parent-pagenum{
                margin-bottom:10px;
                padding-right: 10px;
            }
            .pagenum:before {
                content: counter(page);
            }
            .pull-right{
                text-align: right;
            }
            .div-pusher{
                width:50%;
                padding-left: 50px;
                float: left;
            }
            
            .first-title.first{
                /*margin-top: 70px;*/
                margin-bottom:0px;
            }
            .first-title.second{
                margin-top: 0;
                /*margin-bottom:50px;*/
            }
             .content-wrapper{
                padding: 0 135px 40px 50px;
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
            
            .footer {
                position: fixed; 
                bottom: 5px; 
                /*left: 350px; */
                
            }
            
            .footer p{
                /*padding-right: 135px;*/
                text-align: right;
                margin-bottom: 0px !important;
                padding-bottom: 0px !important;
                margin-top: 0 !important;
                padding: 3px 135px 3px 0 !important;
                font-size: 12px;
                line-height: 12px;
            }
        </style>
        @endif
    </head>
    <body>
    
      <div id="content" >
          <div class="content-wrapper" >
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