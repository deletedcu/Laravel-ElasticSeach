<!DOCTYPE html>
<html lang="hr">
    <head>
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
      <title>@yield("title",'Neptun dokument')</title>
      {!! Html::script(elixir('js/script.js')) !!}
      <link rel="shortcut icon" href="/img/favicon.png">
        <style type="text/css">
           @page {
                header: page-header;
                footer: page-footer;
                font-family: "Arial", sans-serif, "Helvetica Neue", Helvetica !important;
                
            }
           @page {
                header: page-header;
                footer: page-footer;
                font-family: "Arial", sans-serif, "Helvetica Neue", Helvetica !important;
                
            }
            @page:first {
                margin-top: 0px !Important;
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
              font-size:14px;
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
               
            .footer{
                color: #808080;
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
                 /*margin-top: 130px;*/
                 /*margin-bottom: 20px;*/
                 font-family: "Arial", sans-serif, "helvetica Neue", Helvetica !important;
             }
             
            .clearfix{
                clear: both !important;
                height:1px;
            }
            .header,.footer {
                width: 100%;
                /*position: fixed;*/
            }
            .header{
                /*top: -150px;*/
                left: 0;
                width: 100%;
                padding-top: 10px;
                padding-bottom: 0px;
                /*margin-left: 45px;*/
                /*margin-right: 135px;*/
                
                clear:both;
            }
            
            .border-wrapper{
                padding:0 0 0 50px;
            }
             .border-div{
                border-bottom: 1px solid black;
                height: 1px;
                width: 100%;
                margin-left:0;
                padding-left: 0;
                /*margin-top:55px;*/
             }
            .header .div-pusher{
                width:42%;
                padding-left: 50px;
                float: left;
            }
            .header .div-pusher p{
                padding-top: 0;
                /*padding-right: 60px;*/
                font-size: 16px !important; 
                
            }
            .header .image-div {
                width:50%;
                float:left;
                /*margin-right: 125px;*/
                text-align: right;
                
            }
            .header .image-div p{
                padding-top: 0;
                font-size: 16px !important; 
            }
            .header .image-div p.parent-pagenum{
                margin-bottom:10px;
                padding-right: 0;
                font-size: 12px;
            }
            .pagenum:before {
                content: counter(page);
            }
            .pull-right{
                text-align: right;
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
                padding: 0 0 10px 50px;
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
            @page { 
                 /*margin: 120px 30px 80px 30px !important;*/
                 font-family: "Arial", sans-serif, "helvetica Neue", Helvetica !important;
             }
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
             
             
            .clearfix{
                clear: both !important;
                height:1px;
            }
            .header,.footer {
                width: 100%;
                /*position: fixed;*/
            }
            .header{
                /*top: -150px;*/
                left: 0;
                width: 75%;
                padding-top: 10px;
                padding-bottom: 0px;
                margin-left: 45px;
                /*margin-right: 135px;*/
                
                clear:both;
            }
            
            .border-wrapper{
                /*padding:0 135px 0 50px;*/
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
                padding-top: 0;
                padding-right: 60px;
                font-size: 16px !important; 
                
            }
            .header .image-div {
                width:50%;
                float:left;
                /*margin-right: 125px;*/
                text-align: right;
                
            }
            .header .image-div p{
                padding-top: 0;
                font-size: 16px !important; 
            }
            .header .image-div p.subcategory-title{
                margin-bottom: 10px !important;
                padding-bottom: 10px !important;
            }
            .header .image-div p.parent-pagenum{
                margin-bottom:10px;
                padding-right: 0;
                font-size: 12px;
            }
            .pagenum:before {
                content: counter(page);
            }
            .pull-right{
                text-align: right;
            }
            .div-pusher{
                width:50%;
                /*padding-left: 50px;*/
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
                float:right;
                 padding-bottom: 15px;
                
            }
            
            .footer p{
                /*padding-right: 135px;*/
                text-align: right;
                margin-bottom: 0px !important;
                padding-bottom: 0px !important;
                margin-top: 0 !important;
                margin-right: 140px;
                /*padding: 3px 135px 3px 0 !important;*/
                font-size: 12px;
                line-height: 14px;
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