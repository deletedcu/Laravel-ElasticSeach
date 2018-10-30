<!DOCTYPE html>
<html lang="de">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>@yield("title",'Detailansicht - '.$mandant->name)</title>
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

        .footer {
            position: fixed;
            bottom: 10px;
            left: 350px;
        }

        #absolute {
            font-size: 10px !important;
            margin-top: -125px;
            margin-left: 300px;
        }

        .absolute, .absolute:nth-child(even) {
            width: 85px;
            margin-top: -125px;
            margin-left: 300px;
            color: #808080;

        }

        .absolute p {
            margin-bottom: 0 !important;
            margin-top: 0 !important;
            text-align: left;
        }

        .pagenum:before {
            content: counter(page);
        }

        .mandant-image {
            max-width: 100px;
        }

        .first-title.first {
            margin-top: 70px;
            margin-bottom: 70px;
        }

        table {
            width: 100% !important;
        }

        .col-1 {
            width: 30%;
            vertical-align: top;
            font-weight: 700 !important;
            font-size: 12px;
        }

        .col-2 {
            width: 70%;
            vertical-align: top;
            font-size: 12px;
        }

        div.page-break {
            page-break-inside: avoid;
            page-break-after: always;
        }

    </style>
</head>

<body>
{{-- @include('pdf.footer') --}}
<div id="content">
     @include('pdf.mandantHeader') 
    <div class="first-title first">
        <h2>{{$mandant->name}}</h2>
    </div>

    <div class="table-container">
        <h4>Allgemeine Informationen</h4>
        <table>
            
            <tr>
                <td class="col-1">Name</td>
                <td class="col-2">{{$mandant->name}}</td>
            </tr>
            
            <tr>
                <td class="col-1">Mandantnummer</td>
                <td class="col-2">{{$mandant->mandant_number}}</td>
            </tr>
            
            <tr>
                <td class="col-1">Mandantname Kurz</td>
                <td class="col-2">{{$mandant->kurzname}}</td>
            </tr>
            
            <tr>
                <td class="col-1">Adresszusatz</td>
                <td class="col-2">{{$mandant->adresszusatz}}</td>
            </tr>
            
            <tr>
                <td class="col-1">Strasse/ Nr.</td>
                <td class="col-2">{{$mandant->strasse}}/ {{$mandant->hausnummer}}</td>
            </tr>

            <tr>
                <td class="col-1">PLZ/ Ort</td>
                <td class="col-2">{{$mandant->plz}}/ {{$mandant->ort}}</td>
            </tr>

            <tr>
                <td class="col-1">Bundesland</td>
                <td class="col-2">{{$mandant->bundesland}}</td>
            </tr>

            <tr>
                <td class="col-1">Telefon</td>
                <td class="col-2">{{$mandant->telefon}}</td>
            </tr>
            
            <tr>
                <td class="col-1">Kurzwahl</td>
                <td class="col-2">{{$mandant->kurzwahl}}</td>
            </tr>
            
            <tr>
                <td class="col-1">Fax</td>
                <td class="col-2">{{$mandant->fax}}</td>
            </tr>

            <tr>
                <td class="col-1">E-Mail</td>
                <td class="col-2"><a href="mailto:{{$mandant->email}}">{{$mandant->email}}</a></td>
            </tr>

            <tr>
                <td class="col-1">Website</td>
                <td class="col-2"><a href="{{$mandant->website}}">{{$mandant->website}}</a></td>
            </tr>
            
        </table>
    </div>
</div>

@if(!$mandant->hauptstelle)
    <div class="table-container">
        <h4>Hauptstelle</h4>
        <table>
            
            <tr>
                <td class="col-1">Name</td>
                <td class="col-2">{{$hauptstelle->name}}</td>
            </tr>
            
            <tr>
                <td class="col-1">Mandantnummer</td>
                <td class="col-2">{{$hauptstelle->mandant_number}}</td>
            </tr>
            
            <tr>
                <td class="col-1">Mandantname Kurz</td>
                <td class="col-2">{{$hauptstelle->kurzname}}</td>
            </tr>
            
            <tr>
                <td class="col-1">Adresszusatz</td>
                <td class="col-2">{{$hauptstelle->adresszusatz}}</td>
            </tr>
            
            <tr>
                <td class="col-1">Strasse/ Nr.</td>
                <td class="col-2">{{$hauptstelle->strasse}}/ {{$hauptstelle->hausnummer}}</td>
            </tr>

            <tr>
                <td class="col-1">PLZ/ Ort</td>
                <td class="col-2">{{$hauptstelle->plz}}/ {{$hauptstelle->ort}}</td>
            </tr>

            <tr>
                <td class="col-1">Bundesland</td>
                <td class="col-2">{{$hauptstelle->bundesland}}</td>
            </tr>

            <tr>
                <td class="col-1">Telefon</td>
                <td class="col-2">{{$hauptstelle->telefon}}</td>
            </tr>
            
            <tr>
                <td class="col-1">Kurzwahl</td>
                <td class="col-2">{{$hauptstelle->kurzwahl}}</td>
            </tr>
            
            <tr>
                <td class="col-1">Fax</td>
                <td class="col-2">{{$hauptstelle->fax}}</td>
            </tr>

            <tr>
                <td class="col-1">E-Mail</td>
                <td class="col-2"><a href="mailto:{{$hauptstelle->email}}">{{$hauptstelle->email}}</a></td>
            </tr>

            <tr>
                <td class="col-1">Website</td>
                <td class="col-2"><a href="{{$hauptstelle->website}}">{{$hauptstelle->website}}</a></td>
            </tr>
            
        </table>
    </div>
@endif

@if( ViewHelper::universalHasPermission( array(19,20) ) == true  )
    <div class="table-container">
        <h4>Wichtige Informationen</h4>
        <table>
            @if(isset($mandantInfo))
                <tr>
                    <td class="col-1">Wichtiges</td>
                    <td class="col-2">{{$mandantInfo->info_wichtiges}}</td>
                </tr>
            @endif

            <tr>
                <td class="col-1">Geschäftsführer</td>
                <td class="col-2">{{$mandant->geschaftsfuhrer}}</td>
            </tr>

            <tr>
                <td class="col-1">Geschäftsführer-Informationen</td>
                <td class="col-2">{{$mandant->geschaftsfuhrer_infos}}</td>
            </tr>

            <tr>
                <td class="col-1">Geschäftsführer Von</td>
                <td class="col-2">{{$mandant->geschaftsfuhrer_von}}</td>
            </tr>

            <tr>
                <td class="col-1">Geschäftsführer Bis</td>
                <td class="col-2">{{$mandant->geschaftsfuhrer_bis}}</td>
            </tr>

            <tr>
                <td class="col-1">Geschäftsführerhistorie</td>
                <td class="col-2">{{$mandant->geschaftsfuhrer_history}}</td>
            </tr>

        </table>
    </div>

    @if(isset($mandantInfo))
            <!--<div class="page-break"></div>-->
        <div class="table-container">
            <h4>Weitere Informationen</h4>
            <table>
                <tr>
                    <td class="col-1">Prokura</td>
                    <td class="col-2">{{$mandantInfo->prokura}}</td>
                </tr>
                <tr>
                    <td class="col-1">Betriebsnummer</td>
                    <td class="col-2">{{$mandantInfo->betriebsnummer}}</td>
                </tr>
                <tr>
                    <td class="col-1">Handelsregisternummer</td>
                    <td class="col-2">{{$mandantInfo->handelsregister}}</td>
                </tr>
                <tr>
                    <td class="col-1">Handelsregistersitz</td>
                    <td class="col-2">{{$mandantInfo->Handelsregister_sitz}}</td>
                </tr>
                <tr>
                    <td class="col-1">Gewerbeanmeldung</td>
                    <td class="col-2">{{$mandantInfo->angemeldet_am}}</td>
                </tr>
                <tr>
                    <td class="col-1">Umgemeldet am</td>
                    <td class="col-2">{{$mandantInfo->umgemeldet_am}}</td>
                </tr>
                <tr>
                    <td class="col-1">Abgemeldet am</td>
                    <td class="col-2">{{$mandantInfo->abgemeldet_am}}</td>
                </tr>
                <tr>
                    <td class="col-1">Gewerbeanmeldung Historie</td>
                    <td class="col-2">{{$mandantInfo->gewerbeanmeldung_history}}</td>
                </tr>
                <tr>
                    <td class="col-1">Steuernummer</td>
                    <td class="col-2">{{$mandantInfo->steuernummer}}</td>
                </tr>
                <tr>
                    <td class="col-1">USt-IdNr.</td>
                    <td class="col-2">{{$mandantInfo->ust_ident_number}}</td>
                </tr>
                <tr>
                    <td class="col-1">Zusätzliche Informationen Steuer</td>
                    <td class="col-2">{{$mandantInfo->zausatzinfo_steuer}}</td>
                </tr>
                <tr>
                    <td class="col-1">Berufsgenossenschaft/ Mitgliedsnummer</td>
                    <td class="col-2">{{$mandantInfo->berufsgenossenschaft_number}}</td>
                </tr>
                <tr>
                    <td class="col-1">Zusätzliche Informationen Berufsgenossenschaft</td>
                    <td class="col-2">{{$mandantInfo->berufsgenossenschaft_zusatzinfo}}</td>
                </tr>
                <tr>
                    <td class="col-1">Erlaubnis zur Arbeitnehmerüberlassung</td>
                    <td class="col-2">{{ Carbon\Carbon::parse( $mandant->mandantInfo->erlaubniss_gultig_ab)->format('d.m.Y h:i:s') }}</td>
                </tr>
                <tr>
                    <td class="col-1">Unbefristet</td>
                    @if($mandantInfo->unbefristet)
                        <td class="col-2">Ja</td>
                    @else
                        <td class="col-2">Nein</td>
                    @endif
                </tr>
                <tr>
                    <td class="col-1">Befristet bis</td>
                    <td class="col-2">{{$mandantInfo->befristet_bis}}</td>
                </tr>
                <tr>
                    <td class="col-1">Zuständige Erlaubnisbehörde</td>
                    <td class="col-2">{{$mandantInfo->erlaubniss_gultig_von}}</td>
                </tr>
                <tr>
                    <td class="col-1">Informationen zum Geschäftsjahr</td>
                    <td class="col-2">{{$mandantInfo->geschaftsjahr_info}}</td>
                </tr>
                @if( ViewHelper::universalHasPermission( array(20) ) == true  )
                    <tr>
                        <td class="col-1">Bankverbindungen</td>
                        <td class="col-2">{!! str_replace(array('[',']'), array('','<br>'), $mandantInfo->bankverbindungen) !!}</td>
                    </tr>
                @endif
                <tr>
                    <td class="col-1">Sonstiges</td>
                    <td class="col-2">{{$mandantInfo->info_sonstiges}}</td>
                </tr>
            </table>
        </div>

    @endif
    
@endif

</body>

</html>