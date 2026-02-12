<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml"
    xmlns:o="urn:schemas-microsoft-com:office:office">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="x-apple-disable-message-reformatting">
    <title>Daily Log Report - {{ $reportDate->format('Y-m-d') }}</title>
    <!--[if mso]>
    <style type="text/css">
        table {border-collapse: collapse !important;}
        .container {width: 600px !important;}
    </style>
    <noscript>
        <xml>
            <o:OfficeDocumentSettings>
                <o:PixelsPerInch>96</o:PixelsPerInch>
            </o:OfficeDocumentSettings>
        </xml>
    </noscript>
    <![endif]-->
    <style type="text/css">
        /* Reset and Base Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body,
        table,
        td,
        a {
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
        }

        table {
            border-collapse: collapse !important;
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }

        img {
            -ms-interpolation-mode: bicubic;
            border: 0;
            height: auto;
            line-height: 100%;
            outline: none;
            text-decoration: none;
            display: block;
        }

        body {
            margin: 0 !important;
            padding: 0 !important;
            width: 100% !important;
            height: 100% !important;
        }

        p {
            margin: 0;
            padding: 0;
        }

        /* Client-specific Styles */
        #outlook a {
            padding: 0;
        }

        .ExternalClass {
            width: 100%;
        }

        .ExternalClass,
        .ExternalClass p,
        .ExternalClass span,
        .ExternalClass font,
        .ExternalClass td,
        .ExternalClass div {
            line-height: 100%;
        }

        /* Mobile Styles */
        @media only screen and (max-width: 600px) {
            .email-container {
                width: 100% !important;
                margin: auto !important;
            }

            .fluid,
            .fluid-centered {
                width: 100% !important;
                max-width: 100% !important;
                height: auto !important;
                margin-left: auto !important;
                margin-right: auto !important;
                text-align: center !important;
            }

            .stack-column,
            .stack-column-center {
                display: block !important;
                width: 100% !important;
                max-width: 100% !important;
                direction: ltr !important;
            }

            .mobile-padding {
                padding: 15px !important;
            }

            .mobile-padding-small {
                padding: 10px !important;
            }

            .mobile-hide {
                display: none !important;
            }

            .mobile-center {
                text-align: center !important;
            }

            .mobile-font-size-large {
                font-size: 26px !important;
            }

            .mobile-font-size {
                font-size: 14px !important;
            }
        }
    </style>
</head>

<body
    style="margin: 0; padding: 0; background-color: #f4f7fa; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;">

    <!-- Preheader Text -->
    <div
        style="display: none; font-size: 1px; color: #fefefe; line-height: 1px; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif; max-height: 0px; max-width: 0px; opacity: 0; overflow: hidden;">
        @if ($analysis['error_count'] > 0)
            ⚠️ {{ $summary['total_errors'] }} error(s) detected in your Laravel application on
            {{ $reportDate->format('M d, Y') }}
        @else
            ✅ No errors detected - Your application is running smoothly
        @endif
    </div>

    <!-- Full Email Container -->
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%"
        style="margin: 0; padding: 0; background-color: #f4f7fa;">
        <tr>
            <td style="padding: 40px 15px;">

                <!-- Main Email Card -->
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="600"
                    class="email-container" align="center"
                    style="margin: auto; max-width: 600px; background-color: #ffffff; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.08); overflow: hidden;">

                    <!-- Header with Gradient -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 0;">
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                                <tr>
                                    <td style="padding: 40px 30px 35px 30px; text-align: center;">
                                        <!-- App Logo/Icon -->
                                        <div style="margin-bottom: 20px;">
                                            <div
                                                style="display: inline-block; width: 56px; height: 56px; background-color: rgba(255,255,255,0.2); border-radius: 14px; padding: 12px; backdrop-filter: blur(10px);">
                                                <div
                                                    style="width: 32px; height: 32px; border-radius: 6px; position: relative;">
                                                    <div
                                                        style="position: absolute; bottom: 4px; left: 4px; right: 4px; height: 10px; border-radius: 2px;">
                                                        <img src="{{ $assets['applicationLogReportIcon'] }}"
                                                            width="32" alt="Application Log Report Icon" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <h1 style="margin: 0 0 12px 0; color: #ffffff; font-size: 32px; font-weight: 700; letter-spacing: -0.5px; line-height: 1.2;"
                                            class="mobile-font-size-large">
                                            Application Log Report
                                        </h1>
                                        <p style="margin: 0 0 20px 0; color: rgba(255,255,255,0.95); font-size: 20px; font-weight: 600; letter-spacing: 0.3px;"
                                            class="mobile-font-size">
                                            {{ config('app.name') }}
                                        </p>
                                        <div
                                            style="margin:0; padding:12px 24px; background-color:rgba(255,255,255,0.15); border-radius:24px; display:inline-block;">
                                            <img src="{{ $assets['calendarIcon'] }}" width="20" height="20"
                                                style="display:inline-block;vertical-align:middle;margin-right:8px;border:0;"alt="Date">

                                            <span
                                                style="display:inline-block;vertical-align:middle;color:#ffffff;font-size:14px;font-weight:600;white-space:nowrap;">
                                                {{ $reportDate->format('l, F j, Y') }}
                                            </span>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Summary Section -->
                    <tr>
                        <td style="padding: 35px 30px 25px 30px;">
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                                <tr>
                                    <td style="padding-bottom:20px;">
                                        <table role="presentation" cellspacing="0" cellpadding="0" border="0">
                                            <tr>
                                                <td style="padding-right:10px; vertical-align:middle;">
                                                    <img src="{{ $assets['summaryOverviewIcon'] }}" width="30"
                                                        alt="Summary Overview Icon" />
                                                </td>

                                                <!-- TITLE -->
                                                <td style="vertical-align:middle;">
                                                    <h2 style="margin:0;color:#1a202c;font-size:22px;font-weight:700;">
                                                        Summary Overview
                                                    </h2>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>


                            <!-- Metric Cards Grid -->
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                                <tr>
                                    <!-- Total Errors Card -->
                                    <td width="100%" style="vertical-align: top; padding: 0 6px 14px 0;">
                                        <table role="presentation" cellspacing="0" cellpadding="0" border="0"
                                            width="100%"
                                            style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); border-radius: 12px; box-shadow: 0 4px 10px rgba(245, 87, 108, 0.25);">
                                            <tr>
                                                <td style="padding: 24px 20px; text-align: center;">
                                                    <div
                                                        style="font-size: 42px; font-weight: 800; color: #ffffff; line-height: 1; margin-bottom: 8px; letter-spacing: -1px;">
                                                        {{ $summary['total_errors'] }}
                                                    </div>
                                                    <div
                                                        style="font-size: 11px; font-weight: 700; color: rgba(255,255,255,0.95); text-transform: uppercase; letter-spacing: 1px;">
                                                        Total Errors
                                                    </div>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    @if ($analysis['exists'])
                        <!-- File Information -->
                        <tr>
                            <td style="padding: 0 30px 25px 30px;">
                                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%"
                                    style="background: linear-gradient(to right, #eff6ff, #dbeafe); border-left: 4px solid #3b82f6; border-radius: 10px; box-shadow: 0 1px 3px rgba(0,0,0,0.03);">
                                    <tr>
                                        <td style="padding: 18px 20px;">
                                            <table role="presentation" cellspacing="0" cellpadding="0" border="0"
                                                width="100%">
                                                <tr>
                                                    <td style="padding-bottom:10px;">
                                                        <img src="{{ $assets['folderIcon'] }}" width="20"
                                                            height="20"
                                                            style="display:inline-block;vertical-align:middle;margin-right:6px;border:0;"
                                                            alt="Folder">
                                                        <span
                                                            style="color:#1e40af;font-weight:800;font-size:12px;text-transform:uppercase;letter-spacing:1px;vertical-align:middle;">
                                                            Log File Details
                                                        </span>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td style="padding: 5px 0;">
                                                        <table role="presentation" cellspacing="0" cellpadding="0"
                                                            border="0" width="100%">
                                                            <tr>
                                                                <td style="width: 60px; vertical-align: top;">
                                                                    <span
                                                                        style="color: #1e293b; font-size: 13px; font-weight: 700;">Name:</span>
                                                                </td>
                                                                <td style="vertical-align: top;">
                                                                    <span
                                                                        style="color: #475569; font-size: 13px; font-family: 'Courier New', monospace; word-break: break-all;">{{ $analysis['filename'] }}</span>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="padding: 5px 0;">
                                                        <table role="presentation" cellspacing="0" cellpadding="0"
                                                            border="0" width="100%">
                                                            <tr>
                                                                <td style="width: 60px; vertical-align: top;">
                                                                    <span
                                                                        style="color: #1e293b; font-size: 13px; font-weight: 700;">Path:</span>
                                                                </td>
                                                                <td style="vertical-align: top;">
                                                                    <span
                                                                        style="color: #475569; font-size: 12px; font-family: 'Courier New', monospace; word-break: break-all; line-height: 1.5;">{{ $analysis['relative_path'] }}</span>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    @endif

                    <!-- Footer -->
                    <tr>
                        <td
                            style="padding: 32px 30px; background: linear-gradient(to bottom, #f9fafb, #f3f4f6); border-top: 1px solid #e5e7eb;">
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0"
                                width="100%">
                                <tr>
                                    <td style="text-align: center; padding-bottom: 14px;">
                                        <div
                                            style="color: #374151; font-size: 14px; line-height: 1.7; font-weight: 600;">
                                            {{ config('app.name') }}
                                        </div>
                                        <div
                                            style="color: #6b7280; font-size: 13px; line-height: 1.6; margin-top: 4px;">
                                            Automated Log Monitoring System
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td
                                        style="text-align: center; padding: 14px 0; border-top: 1px solid #e5e7eb; border-bottom: 1px solid #e5e7eb;">
                                        <div style="color: #6b7280; font-size: 12px; line-height: 1.6;">
                                            Generated on {{ now()->format('F j, Y') }} at {{ now()->format('g:i A') }}
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="text-align: center; padding-top: 14px;">
                                        <div style="font-size: 11px; color: #9ca3af; line-height: 1.6;">
                                            This is an automated email. Please do not reply.<br>
                                            For support, contact your development team.
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                </table>
                <!-- End Main Email Card -->

                <!-- Email Footer (Outside Card) -->
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="600"
                    class="email-container" align="center" style="margin: 20px auto 0; max-width: 600px;">
                    <tr>
                        <td style="text-align: center; padding: 20px 15px;">
                            <p style="margin: 0; color: #9ca3af; font-size: 11px; line-height: 1.5;">
                                © {{ now()->year }} {{ config('app.name') }}. All rights reserved.<br>
                                Powered by Laravel Framework
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>
