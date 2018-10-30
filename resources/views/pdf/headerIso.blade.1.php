<html>
    <head>
<style type="text/css">
    @page { margin: 0px  }
    #header {
        position: fixed; 
        left: 0px; 
        top: 150px;
        width: 100%;
        margin-top: -180px;
        display:block;
        /*margin-left: 50px;*/
        /*right: -50px; */
        height: 800px; 
        background: red; 
        text-align: center; 
        z-index: 999999;
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
</style>

<div id="header" class="header"  style="">
    
    <div class="div-pusher">       
        <p>{!! $document->name_long !!}</p>
    </div>
        <div class="image-div">
        <div class="pull-right" style="">
         @if( $document->isoCategories != null) 
            <p>
                {{ $document->isoCategories->name }}
                @if( $document->iso_category_number != null)
                    / Kapitel-Nr:   {{ $document->iso_category_number }}@if( $document->additional_letter ){{ $document->additional_letter }} @endif
                @endif
                kapi
            </p>
         <p class="parent-pagenum"> 
                @if( $document->landscape == 1)
                     <script type="text/php">
                         $icn = '{{$document->iso_category_number}}';
                         $text = 'Seite '.$icn.'-{PAGE_NUM} von {PAGE_COUNT}';
                         
                         $font = Font_Metrics::get_font("arial", "italic");
                         $pdf->page_text(640, 40, $text, $font, 9);
                    </script>
                @else
                     <script type="text/php">
                         $icn = '{{$document->iso_category_number}}';
                         $text = 'Seite '.$icn.'-{PAGE_NUM} von {PAGE_COUNT}';
                         
                         $font = Font_Metrics::get_font("arial", "italic");
                         $pdf->page_text(390, 50, $text, $font, 9);
                    </script>
                @endif
            
         </p>
        @endif
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="border-wrapper">
        <div class="border-div"></div>
    </div>
    
</div>
<div class="dummy-div" style=""></div>

</head>
</html>