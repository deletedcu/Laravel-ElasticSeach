<!DOCTYPE html>
<html lang="de">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>@yield("title",'Abrechnen')</title>
    <link rel="shortcut icon" href="/img/favicon.png">
    <style type="text/css">
        body, p, h1, h2, h3, h4, h5 {
            font-family: 'Helvetica', 'Arial', sans-serif !important;
        }

        p {
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
            margin-bottom: 50px;
        }

        .div-pusher {
            width: 50%;
            padding-left: 30%;
        }

        .header .div-pusher {
            width: 60%;
            padding-left: 30%;
        }

        .header .image-div {
            width: 40%;
            float: right !important;
            padding-left: 50px;
            height: auto;
        }

        .header .image-div img {
            margin-left: 0px;
            width: 100%;
            height: auto;
            display: block;
        }

        table{
            width: 100%;
            border: 1px solid #d6d6d6;
        }
        table td, table th{
            text-align: center;
            border: 1px solid #d3d3d3;
            padding: 5px;
        }
        

    </style>
</head>

<body>
{{-- @include('pdf.footer') --}}
<div id="content">
     @include('pdf.abrechnenHeader') 

    @foreach( $mandants as $mandant)
        <div class="first-title first">
            @if($mandant->mandant)
                <h3>({{$mandant->mandant->mandant_number}}) {{$mandant->mandant->name}} </h3>
            @else
                <h3>({{$mandant->mandant_number}}) {{$mandant->name}} </h3>
            @endif
        </div>
    
        <div class="table-container">
           <table class="table" >
                            <thead>
                                <tr>
                                    <th  class="text-center valign">@lang('inventoryList.name')</th>
                                    <th  class="text-center valign">@lang('inventoryList.number')</th>
                                    <th class="text-center valign">@lang('inventoryList.size')</th>
                                    <th class="text-center valign">@lang('inventoryList.sellPrice')</th>
                                    <th class="text-center valign no-sort">@lang('inventoryList.dateWithdrawal')</th>
                                    <th class="text-center valign no-sort">@lang('inventoryList.billed')</th>
                                </tr>
                            </thead>
                            <tbody>
                             @if(count($mandant->items) )
                              
                                    @foreach($mandant->items as $k => $item)
                                    <tr>
                                  
                                    <input type="hidden" name="href" value="#collapseInventory{{$mandant->id}}" />
                                        <td class="text-center valign">
                                            {{ $item->item->name }}
                                        </td>
                                        <td class="text-center valign ">
                                            {{ $item->value }}
                                        </td>
                                        <td class="text-center valign ">
                                            {{ $item->size->name }}
                                        </td>
                                        <td class="text-center valign ">
                                            {{ $item->sell_price }} Euro
                                        </td>
                                        
                                        <td class="text-center valign"> 
                                             {{ $item->created_at->format('d.m.Y H:i:s') }}
                                        </td>
                                        <td class="text-center valign"> 
                                            @if($item->accounted_for == true) Ja @else Nein @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td class"valign"></td>
                                        <td class"valign"></td>
                                        <td class"valign"></td>
                                        <td class"valign">Keine Daten vorhanden</td>
                                        <td class"valign"></td>
                                        <td class"valign"></td>
                                    </tr>
                                @endif 
                            
                            </tbody>
                        </table> 
        </div>
    @endforeach
</div>
<div style="clear:both; margin-bottom: 30px;"></div>

</body>

</html>