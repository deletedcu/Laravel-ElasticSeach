<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8"> <!-- utf-8 works for most cases -->
    <meta name="viewport" content="width=device-width"> <!-- Forcing initial-scale shouldn't be necessary -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge"> <!-- Use the latest (edge) version of IE rendering engine -->
    <meta name=”x-apple-disable-message-reformatting”>  <!-- Disable auto-scale in iOS 10 Mail entirely -->
    <title>{{ $request['subject'] }}</title> <!-- The title tag shows in email notifications, like Android 4.4. -->

    <!-- Web Font / @font-face : BEGIN -->
    <!-- NOTE: If web fonts are not required, lines 9 - 26 can be safely removed. -->
    
    <!-- Desktop Outlook chokes on web font references and defaults to Times New Roman, so we force a safe fallback font. -->
    <!--[if mso]>
        <style>
            * {
                font-family: sans-serif !important;
            }
        </style>
    <![endif]-->
    
    <!-- All other clients get the webfont reference; some will render the font and others will silently fail to the fallbacks. More on that here: http://stylecampaign.com/blog/2015/02/webfont-support-in-email/ -->
    <!--[if !mso]><!-->
        <!-- insert web font reference, eg: <link href='https://fonts.googleapis.com/css?family=Roboto:400,700' rel='stylesheet' type='text/css'> -->
    <!--<![endif]-->

    <!-- Web Font / @font-face : END -->
    
    <!-- CSS Reset -->
    <style>

        /* What it does: Remove spaces around the email design added by some email clients. */
        /* Beware: It can remove the padding / margin and add a background color to the compose a reply window. */
        html,
        body {
            margin: 0 auto !important;
            padding: 0 !important;
            height: 100% !important;
            width: 100% !important;
        }
        
        /* What it does: Stops email clients resizing small text. */
        * {
            -ms-text-size-adjust: 100%;
            -webkit-text-size-adjust: 100%;
        }
        
        /* What it does: Centers email on Android 4.4 */
        div[style*="margin: 16px 0"] {
            margin:0 !important;
        }
        
        /* What it does: Stops Outlook from adding extra spacing to tables. */
        table,
        td {
            mso-table-lspace: 0pt !important;
            mso-table-rspace: 0pt !important;
        }
                
        /* What it does: Fixes webkit padding issue. Fix for Yahoo mail table alignment bug. Applies table-layout to the first 2 tables then removes for anything nested deeper. */
        table {
            border-spacing: 0 !important;
            border-collapse: collapse !important;
            table-layout: fixed !important;
            margin: 0 auto !important;
        }
        table table table {
            table-layout: auto; 
        }
        
        /* What it does: Uses a better rendering method when resizing images in IE. */
        img {
            -ms-interpolation-mode:bicubic;
        }
        .email{
            color: #575756;
            font-weight: 700;
            text-decoration:none;
        }
        .email:visited {
            color: #575756;
            font-weight: 700;
            text-decoration:none;
        }
        /* What it does: A work-around for iOS meddling in triggered links. */
        .mobile-link--footer a,
        a[x-apple-data-detectors] {
            color:inherit !important;
            text-decoration: underline !important;
        }
          
    </style>
    
    <!-- Progressive Enhancements -->
    <style>
        
        /* What it does: Hover styles for buttons */
        .button-td,
        .button-a {
            transition: all 100ms ease-in;
        }
        .button-td:hover,
        .button-a:hover {
            background: #555555 !important;
            border-color: #555555 !important;
        }

        /* Media Queries */
        @media screen and (max-width: 480px) {

            /* What it does: Forces elements to resize to the full width of their container. Useful for resizing images beyond their max-width. */
            .fluid,
            .fluid-centered {
                width: 100% !important;
                max-width: 100% !important;
                height: auto !important;
                margin-left: auto !important;
                margin-right: auto !important;
            }
            /* And center justify these ones. */
            .fluid-centered {
                margin-left: auto !important;
                margin-right: auto !important;
            }

            /* What it does: Forces table cells into full-width rows. */
            .stack-column,
            .stack-column-center {
                display: block !important;
                width: 100% !important;
                max-width: 100% !important;
                direction: ltr !important;
            }
            /* And center justify these ones. */
            .stack-column-center {
                text-align: center !important;
            }
        
            /* What it does: Generic utility class for centering. Useful for images, buttons, and nested tables. */
            .center-on-narrow {
                text-align: center !important;
                display: block !important;
                margin-left: auto !important;
                margin-right: auto !important;
                float: none !important;
            }
            table.center-on-narrow {
                display: inline-block !important;
            }
                
        }

    </style>

</head>
<body width="100%" style="margin: 0;">
    
    <center style="width: 100%;" >

        <!-- Visually Hidden Preheader Text : BEGIN -->
        <div style="display:none;font-size:1px;line-height:1px;max-height:0px;max-width:0px;opacity:0;overflow:hidden;mso-hide:all;font-family: sans-serif;">
           
        </div>
        <!-- Visually Hidden Preheader Text : END -->

        <!--    
            Set the email width. Defined in two places:
            1. max-width for all clients except Desktop Windows Outlook, allowing the email to squish on narrow but never go wider than 680px.
            2. MSO tags for Desktop Windows Outlook enforce a 680px width.
            Note: The Fluid and Responsive templates have a different width (600px). The hybrid grid is more "fragile", and I've found that 680px is a good width. Change with caution.  
        -->
        <div style="max-width: 680px; margin: auto; /* background-color:rgb(244,244,244); */">
            <!--[if mso]>
            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="680" align="center">
            <tr>
            <td>
            <![endif]-->

            <!-- Email Body : BEGIN -->
            <table role="presentation" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="/* background-color:rgb(244,244,244); */ max-width: 680px;">
                
                <!-- Hero Image, Flush : BEGIN -->
                <!--
                <tr>
                    <td bgcolor="#ffffff" style="text-align:center; padding-top:20px;">
                        <img src="{{ $request['logo'] }}"  height="" alt="alt_text" border="0" align="center" class="fluid" style="  background: #dddddd; font-family: sans-serif; font-size: 15px; mso-height-rule: exactly; line-height: 20px; color: #555555;">
                    </td>
                </tr>
                -->
                <!-- Hero Image, Flush : END -->

                <!-- 1 Column Text + Button : BEGIN -->
                <tr>
                    <td bgcolor="#ffffff">
                        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                            <tr>
                                <td style="padding: 40px; text-align: center; font-family: sans-serif; font-size: 24px; mso-height-rule: exactly; line-height: 20px; color: #555555;">
                                   @lang('inventoryList.emailSubject')
                                    <br><br>
                                    <!-- Button : BEGIN -->
                                    <table role="presentation" cellspacing="0" cellpadding="0" border="0" align="center" style="margin: auto">
                                    </table>
                                    <!-- Button : END -->
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <!-- 1 Column Text + Button : END -->

                <!-- Thumbnail Left, Text Right : BEGIN -->
                <tr>
                    <!-- dir=ltr is where the magic happens. This can be changed to dir=rtl to swap the alignment on wide while maintaining stack order on narrow. -->
                    <td dir="ltr" bgcolor="#ffffff" align="center" height="100%" valign="top" width="100%" style="padding: 0 0;">
                        <!--[if mso]>
                        <table role="presentation" border="0" cellspacing="0" cellpadding="0" align="center" width="660">
                        <tr>
                        <td align="center" valign="top" width="660">
                        <![endif]-->
                        <table role="presentation" border="0" cellpadding="0" cellspacing="0" align="center" width="100%" style="max-width:660px;">
                            <tr>
                                <td align="center" valign="top" style="font-size:0; padding: 0 0;">
                                    
                                    <div style="display:inline-block; margin: 0 -2px; max-width:66.66%; min-width:320px; vertical-align:top;" class="stack-column">
                                        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                                            <tr>
                                                <td dir="ltr" style="font-family: sans-serif; font-size: 15px; mso-height-rule: exactly; line-height: 20px; color: #555555; padding: 10px 10px 0; text-align: left;" class="center-on-narrow">
                                                    Der Artikel {{$item->name}} hat den Mindestbestand erreicht.
                                                    Derzeit ist der Restbestand bei {{$item->value}} Artikeln. 
													<!-- Button : Begin -->
                                                    <table role="presentation" cellspacing="0" cellpadding="0" border="0" class="center-on-narrow" style="float:left;">
                                                        
                                                    </table>
                                                    <!-- Button : END -->
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                    <!--[if mso]>
                                    </td>
                                    </tr>
                                    </table>
                                    <![endif]-->
                                </td>
                            </tr>
                        </table>
                        <!--[if mso]>
                        </td>
                        </tr>
                        </table>
                        <![endif]-->
                    </td>
                </tr>
                <!-- Thumbnail Left, Text Right : END -->
                
				

                <!-- Clear Spacer : BEGIN -->
                <tr>
                    <td height="40" style="font-size: 0; line-height: 0;">
                        &nbsp;
                    </td>
                </tr>
                <!-- Clear Spacer : END -->

                

            </table>
            <!-- Email Body : END -->
          
            <!-- Email Footer : BEGIN -->
            <table role="presentation" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="max-width: 680px;">
                <tr>
                    <td style="padding: 40px 10px;width: 100%;font-size: 12px; font-family: sans-serif; mso-height-rule: exactly; line-height:18px; text-align: center; color: #888888; background-color:rgb(244,244,244);">
                        NEPTUN Verwaltungs- und Beteiligungsgesellschaft mbH<br>
                        <span class="mobile-link--footer">Eschenstraße 8, 82024 Taufkirchen</span><br>
                        <span class="mobile-link--footer">Telefon: +49 89 61 29 010</span>
                        
                    </td>
                </tr>
            </table>
            <!-- Email Footer : END -->

            <!--[if mso]>
            </td>
            </tr>
            </table>
            <![endif]-->
        </div>
    </center>
</body>
</html>

