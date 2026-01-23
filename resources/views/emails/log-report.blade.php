<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log Report - {{ $reportDate->format('Y-m-d') }}</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            line-height: 1.6;
            color: #333333;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
        }

        .container {
            background-color: #ffffff;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .header {
            border-bottom: 3px solid #e74c3c;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }

        h1 {
            color: #2c3e50;
            margin: 0 0 10px 0;
            font-size: 28px;
            font-weight: 600;
        }

        .date {
            color: #7f8c8d;
            font-size: 14px;
        }

        .summary {
            background-color: #ecf0f1;
            border-left: 4px solid #3498db;
            padding: 15px 20px;
            margin: 20px 0;
            border-radius: 4px;
        }

        .summary h2 {
            margin: 0 0 10px 0;
            color: #2c3e50;
            font-size: 18px;
            font-weight: 600;
        }

        .summary-item {
            display: flex;
            justify-content: space-between;
            padding: 5px 0;
        }

        .summary-label {
            font-weight: 600;
            color: #555555;
        }

        .summary-value {
            color: #e74c3c;
            font-weight: 700;
        }

        .error-section {
            margin-top: 30px;
        }

        .error-section h2 {
            color: #2c3e50;
            font-size: 20px;
            margin-bottom: 20px;
        }

        .error-item {
            background-color: #fff5f5;
            border: 1px solid #fee;
            border-left: 4px solid #e74c3c;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 4px;
        }

        .error-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            flex-wrap: wrap;
            gap: 10px;
        }

        .error-level {
            background-color: #e74c3c;
            color: white;
            padding: 4px 12px;
            border-radius: 3px;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .error-level.emergency {
            background-color: #8e44ad;
        }

        .error-level.alert {
            background-color: #c0392b;
        }

        .error-level.critical {
            background-color: #e74c3c;
        }

        .error-level.error {
            background-color: #e67e22;
        }

        .error-timestamp {
            color: #7f8c8d;
            font-size: 13px;
        }

        .error-message {
            color: #2c3e50;
            font-weight: 500;
            margin: 10px 0;
            word-wrap: break-word;
        }

        .stack-trace {
            background-color: #2c3e50;
            color: #ecf0f1;
            padding: 12px;
            border-radius: 4px;
            font-family: 'Courier New', Consolas, Monaco, monospace;
            font-size: 12px;
            overflow-x: auto;
            margin-top: 10px;
            white-space: pre-wrap;
            word-wrap: break-word;
        }

        .no-errors {
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            border-left: 4px solid #28a745;
            color: #155724;
            padding: 20px;
            border-radius: 4px;
            text-align: center;
            font-size: 16px;
        }

        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            color: #7f8c8d;
            font-size: 13px;
            text-align: center;
        }

        .file-info {
            background-color: #e8f4f8;
            border-left: 4px solid #3498db;
            padding: 12px;
            margin: 15px 0;
            border-radius: 4px;
            font-size: 13px;
        }

        .file-info strong {
            color: #2c3e50;
        }

        .more-errors-notice {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            border-left: 4px solid #ffc107;
            color: #856404;
            padding: 15px;
            margin: 15px 0;
            border-radius: 4px;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>📋 Laravel Log Report</h1>
            <div class="date">{{ config('app.name') }} - {{ $reportDate->format('l, F j, Y') }}</div>
        </div>

        <div class="summary">
            <h2>Summary</h2>
            <div class="summary-item">
                <span class="summary-label">Total Errors:</span>
                <span class="summary-value">{{ $summary['total_errors'] }}</span>
            </div>
            @if (!empty($summary['by_level']))
                @foreach ($summary['by_level'] as $level => $count)
                    <div class="summary-item">
                        <span class="summary-label">{{ ucfirst($level) }}:</span>
                        <span class="summary-value">{{ $count }}</span>
                    </div>
                @endforeach
            @endif
            <div class="summary-item">
                <span class="summary-label">Log File Size:</span>
                <span>{{ $analysis['file_size_mb'] }} MB</span>
            </div>
        </div>

        @if ($analysis['exists'])
            <div class="file-info">
                <strong>📁 Log File:</strong> {{ $analysis['filename'] }}<br>
                <strong>📍 Path:</strong> {{ $analysis['filepath'] }}
            </div>
        @endif

        @if ($analysis['error_count'] > 0)
            <div class="error-section">
                @php
                    $maxDisplay = config('log-monitor.max_errors_display', 20);
                    $displayErrors = array_slice($analysis['errors'], 0, $maxDisplay);
                    $remainingCount = count($analysis['errors']) - count($displayErrors);
                @endphp

                <h2>Error Details (Top {{ count($displayErrors) }} shown)</h2>

                @foreach ($displayErrors as $index => $error)
                    <div class="error-item">
                        <div class="error-header">
                            <span class="error-level {{ strtolower($error['level']) }}">
                                {{ $error['level'] }}
                            </span>
                            <span class="error-timestamp">
                                🕐 {{ $error['timestamp'] ?? 'Unknown time' }}
                            </span>
                        </div>

                        <div class="error-message">
                            {{ $error['message'] }}
                        </div>

                        @if (!empty($error['stack_trace']))
                            <div class="stack-trace">{{ $error['stack_trace'] }}</div>
                        @endif
                    </div>
                @endforeach

                @if ($remainingCount > 0)
                    <div class="more-errors-notice">
                        ⚠️ <strong>{{ $remainingCount }} more error(s)</strong> found in the log file.
                        Please check the attached file or log file directly for complete details.
                    </div>
                @endif
            </div>
        @else
            <div class="no-errors">
                ✅ <strong>No errors found!</strong> Your application ran smoothly on this day.
            </div>
        @endif

        <div class="footer">
            <p>
                This is an automated report from <strong>{{ config('app.name') }}</strong><br>
                Generated at {{ now()->format('Y-m-d H:i:s') }}
            </p>
            @if ($analysis['file_size_mb'] > config('log-monitor.max_file_size_mb'))
                <p style="color: #e67e22; margin-top: 10px;">
                    ⚠️ Log file was too large ({{ $analysis['file_size_mb'] }}MB) to attach.
                    Please check the log file directly on the server.
                </p>
            @endif
        </div>
    </div>
</body>

</html>
