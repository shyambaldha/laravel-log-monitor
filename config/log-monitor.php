<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Enable Log Monitor
    |--------------------------------------------------------------------------
    |
    | Enable or disable the log monitoring functionality.
    | Set to false to completely disable log monitoring.
    |
    */
    'enabled' => env('LOG_MONITOR_ENABLED', true),

    /*
    |--------------------------------------------------------------------------
    | Email Recipients
    |--------------------------------------------------------------------------
    |
    | Comma-separated list of email addresses to receive log reports.
    | Example: admin@example.com,dev@example.com,support@example.com
    |
    | Set this in your .env file:
    | LOG_MONITOR_RECIPIENTS=admin@example.com,dev@example.com
    |
    */
    'recipients' => env('LOG_MONITOR_RECIPIENTS', ''),

    /*
    |--------------------------------------------------------------------------
    | Log File Path
    |--------------------------------------------------------------------------
    |
    | Path to the Laravel log directory.
    | Default is storage/logs
    |
    */
    'log_path' => storage_path('logs'),

    /*
    |--------------------------------------------------------------------------
    | Log Levels to Monitor
    |--------------------------------------------------------------------------
    |
    | Specify which log levels should trigger email notifications.
    | Available levels: emergency, alert, critical, error, warning, notice, info, debug
    |
    | Only logs matching these levels will be included in the report.
    |
    */
    'monitor_levels' => [
        'emergency',
        'alert',
        'critical',
        'error',
    ],

    /*
    |--------------------------------------------------------------------------
    | Send Only If Errors Found
    |--------------------------------------------------------------------------
    |
    | If true, emails will only be sent if errors are found in the log file.
    | If false, daily reports will be sent regardless of whether errors exist.
    |
    | Set to false if you want daily confirmation emails even when no errors occur.
    |
    */
    'send_only_on_errors' => env('LOG_MONITOR_ONLY_ERRORS', true),

    /*
    |--------------------------------------------------------------------------
    | Date Format
    |--------------------------------------------------------------------------
    |
    | Format for log file date.
    | Laravel default log file naming is: laravel-Y-m-d.log
    |
    | Don't change this unless you've customized Laravel's log file naming.
    |
    */
    'date_format' => 'Y-m-d',

    /*
    |--------------------------------------------------------------------------
    | Subject Template
    |--------------------------------------------------------------------------
    |
    | Email subject template.
    | Available variables:
    |   {app_name}    - Application name from config/app.php
    |   {date}        - Report date (Y-m-d format)
    |   {error_count} - Number of errors found
    |
    */
    'subject_template' => '[{app_name}] Log Report - {date} ({error_count} errors)',

    /*
    |--------------------------------------------------------------------------
    | Max File Size (MB)
    |--------------------------------------------------------------------------
    |
    | Maximum log file size to attach to the email (in megabytes).
    | If the log file is larger than this, only a summary will be sent.
    |
    | This prevents email size issues with large log files.
    |
    */
    'max_file_size_mb' => 10,

    /*
    |--------------------------------------------------------------------------
    | From Address
    |--------------------------------------------------------------------------
    |
    | Override the default from address for log notification emails.
    | If not set, uses MAIL_FROM_ADDRESS from .env
    |
    */
    'from_address' => env('LOG_MONITOR_FROM_ADDRESS', env('MAIL_FROM_ADDRESS')),

    /*
    |--------------------------------------------------------------------------
    | From Name
    |--------------------------------------------------------------------------
    |
    | Override the default from name for log notification emails.
    | If not set, uses MAIL_FROM_NAME from .env
    |
    */
    'from_name' => env('LOG_MONITOR_FROM_NAME', env('MAIL_FROM_NAME')),

    /*
    |--------------------------------------------------------------------------
    | Max Errors to Display
    |--------------------------------------------------------------------------
    |
    | Maximum number of errors to display in the email body.
    | If more errors exist, a note will be shown indicating additional errors.
    |
    | This prevents extremely long emails when many errors occur.
    |
    */
    'max_errors_display' => 20,
];
