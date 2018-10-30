<!DOCTYPE html>
<html lang="de">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>@yield("title",'Post Versand')</title>
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

        table {
            width: 100%;
            border: 1px solid #d6d6d6;
        }

        table td, table th {
            text-align: center;
            border: 1px solid #d3d3d3;
            padding: 5px;
        }


    </style>
</head>

<body>

<div id="content">

    <h2>Liste aller Post Versand Personen </h2>


    <div class="table-container">
        <table class="table">
            <thead>
            <!--<tr>-->
                <!--<th class="text-left valign">{{ trans('documentForm.mandants') }}</th>-->
                <!--<th class="text-center valign">{{ trans('documentForm.name') }}</th>-->
                <!--<th class="text-center valign">{{ trans('documentForm.adress') }}</th>-->
            <!--</tr>-->
            </thead>
            <tbody>
                
                @if(count($userSettings))
                    @foreach($userSettings as $setting)
                        @foreach($mandants as $mandant)
                            @if($mandant->id == $setting->mandant_id)
                                <tr>
                                    <td class="text-left valign" style="text-align: left;">
                                        {{ $mandant->mandant_number }} <br>
                                        {{ $mandant->name }} <br>
                                        {{ $mandant->adresszusatz }} <br>
                                        {{ $setting->user->title ." ". $setting->user->first_name ." ". $setting->user->last_name }} <br>
                                        {{ $mandant->strasse }} {{ $mandant->hausnummer }} <br>
                                        {{ $mandant->plz }} {{ $mandant->ort }} <br>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    @endforeach
                @else
                    <tr>
                        <td class="valign" colspan="3">Keine Daten vorhanden.</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
    
</div>
<div style="clear:both; margin-bottom: 30px;"></div>

</body>

</html>