# Laravel Log Monitor

[![Laravel](https://img.shields.io/badge/Laravel-8%20to%2011-red.svg)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-7.3%2B-blue.svg)](https://php.net)
[![License](https://img.shields.io/badge/license-MIT-green.svg)](LICENSE)

A comprehensive Laravel package that monitors daily log files and sends beautiful email notifications when errors are detected. Perfect for keeping your development and production teams informed about application health.

## ✨ Features

- 📧 **Multiple Recipients** - Send reports to unlimited email addresses
- 🔍 **Smart Error Detection** - Monitors emergency, alert, critical, and error levels
- 📊 **Beautiful HTML Emails** - Professional email template with error details and stack traces
- 📎 **Log File Attachments** - Automatically attaches log files (with configurable size limits)
- ⏰ **Flexible Scheduling** - Run daily, hourly, or on custom schedules
- ⚙️ **Highly Configurable** - Extensive configuration options
- 🎯 **Conditional Sending** - Only send emails when errors are found (configurable)
- 🔄 **Laravel 8-11 Compatible** - Works seamlessly across all modern Laravel versions
- 💪 **Production Ready** - Battle-tested error handling and logging

## 📋 Requirements

- PHP 7.3 or higher
- Laravel 8.x, 9.x, 10.x, or 11.x
- Configured mail driver (SMTP, Mailgun, SES, etc.)

## 🚀 Installation

### Step 1: Install via Composer

```bash
composer require eheuristic/laravel-log-monitor
```

### Step 2: Publish Configuration

```bash
php artisan vendor:publish --tag=log-monitor-config
```

This creates `config/log-monitor.php`.

### Step 3: Configure Environment Variables

Add to your `.env` file:

```env
LOG_MONITOR_ENABLED=true
LOG_MONITOR_RECIPIENTS=admin@example.com,dev@example.com,support@example.com
LOG_MONITOR_ONLY_ERRORS=true
LOG_MONITOR_FROM_ADDRESS=noreply@example.com
LOG_MONITOR_FROM_NAME="Laravel Log Monitor"
```

**Important:** Replace email addresses with your actual recipients!

### Step 4: Schedule the Command

Add to `app/Console/Kernel.php`:

```php
protected function schedule(Schedule $schedule)
{
    // Send yesterday's log report daily at 1:00 AM
    $schedule->command('log:send-report')
             ->dailyAt('01:00')
             ->timezone('Asia/Kolkata'); // Adjust to your timezone
}
```

### Step 5: Set Up Cron (Production)

Add to your server's crontab:

```bash
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

## 📖 Usage

### Manual Command Execution

Send yesterday's report:
```bash
php artisan log:send-report
```

Send report for a specific date:
```bash
php artisan log:send-report --date=2025-01-22
```

Force send even if no errors found:
```bash
php artisan log:send-report --force
```

### Testing the Package

1. **Generate test errors:**
```bash
php artisan tinker
```

```php
use Illuminate\Support\Facades\Log;

Log::error('Test error message');
Log::critical('Critical test error');
Log::emergency('Emergency test error');
exit
```

2. **Send test report:**
```bash
php artisan log:send-report --date=2025-01-23 --force
```

3. **Check your email!** 📬

## ⚙️ Configuration

Edit `config/log-monitor.php` to customize:

### Basic Configuration

```php
'enabled' => true,  // Enable/disable monitoring
'recipients' => env('LOG_MONITOR_RECIPIENTS', ''),  // Email addresses
'send_only_on_errors' => true,  // Only send when errors exist
```

### Log Levels to Monitor

```php
'monitor_levels' => [
    'emergency',
    'alert',
    'critical',
    'error',
    // Add 'warning', 'notice', 'info', 'debug' if needed
],
```

### Email Customization

```php
'subject_template' => '[{app_name}] Log Report - {date} ({error_count} errors)',
'max_file_size_mb' => 10,  // Max attachment size
'max_errors_display' => 20,  // Max errors shown in email body
```

## 📧 Email Template

The package includes a beautiful, responsive HTML email template featuring:

- **Summary Section** - Total errors and breakdown by level
- **Error Details** - Color-coded error levels with timestamps
- **Stack Traces** - Full stack traces for debugging
- **File Information** - Log file path and size
- **Smart Truncation** - Shows top 20 errors, notes if more exist

### Customizing the Email Template

Publish the views:

```bash
php artisan vendor:publish --tag=log-monitor-views
```

Edit: `resources/views/vendor/log-monitor/emails/log-report.blade.php`

## 🔧 Advanced Usage

### Custom Scheduling

```php
// Hourly reports
$schedule->command('log:send-report')->hourly();

// Every 6 hours
$schedule->command('log:send-report')->everySixHours();

// Multiple times per day
$schedule->command('log:send-report')->dailyAt('09:00');
$schedule->command('log:send-report')->dailyAt('17:00');

// Weekdays only
$schedule->command('log:send-report')->dailyAt('09:00')->weekdays();
```

### Queue Support

For large log files, use queues:

```php
// In your scheduler
$schedule->command('log:send-report')->dailyAt('01:00')->runInBackground();
```

### Custom Log Paths

```php
// In config/log-monitor.php
'log_path' => storage_path('custom-logs'),
```

## 🌍 Timezone Configuration

Common timezone settings:

```php
// India
->timezone('Asia/Kolkata')

// US Eastern
->timezone('America/New_York')

// US Pacific  
->timezone('America/Los_Angeles')

// UK
->timezone('Europe/London')

// UTC
->timezone('UTC')
```

## 🔍 How It Works

1. **Scheduled Execution** - Laravel's task scheduler runs the command at the configured time
2. **Log Analysis** - The package analyzes the previous day's log file
3. **Error Extraction** - Extracts errors matching configured log levels with full stack traces
4. **Conditional Check** - Determines if email should be sent based on configuration
5. **Email Generation** - Creates beautiful HTML email with error details
6. **Multi-Recipient Send** - Sends to all configured email addresses
7. **Optional Attachment** - Attaches log file if size is within limits

**Timeline Example:**
- **Jan 23** - Your app generates logs → `laravel-2025-01-23.log`
- **Jan 24 at 1:00 AM** - Command runs and analyzes Jan 23 logs
- **Jan 24 at 1:00 AM** - Email sent to all recipients with Jan 23 errors

## 🛠️ Troubleshooting

### No emails received?

1. **Test mail configuration:**
```bash
php artisan tinker
```
```php
Mail::raw('Test', function($msg) { 
    $msg->to('test@example.com')->subject('Test'); 
});
```

2. **Check recipients:** Verify `LOG_MONITOR_RECIPIENTS` in `.env`
3. **Check log file exists:** `ls -lh storage/logs/`
4. **Run manually with force:** `php artisan log:send-report --force`
5. **Check Laravel logs:** `tail -f storage/logs/laravel-*.log`

### Scheduler not running?

1. **Verify cron:** `crontab -l`
2. **List scheduled tasks:** `php artisan schedule:list`
3. **Test manually:** `php artisan schedule:run`
4. **Check timezone:** Ensure timezone matches your server

### Log file not found?

1. **Check logging configuration:** Review `config/logging.php`
2. **Verify daily channel:** Ensure using 'daily' or 'stack' with daily
3. **Check permissions:** `chmod -R 775 storage/logs`

## 📊 Configuration Reference

| Option | Type | Default | Description |
|--------|------|---------|-------------|
| `enabled` | boolean | `true` | Enable/disable monitoring |
| `recipients` | string | `''` | Comma-separated emails |
| `log_path` | string | `storage/logs` | Log directory path |
| `monitor_levels` | array | See config | Log levels to monitor |
| `send_only_on_errors` | boolean | `true` | Send only if errors exist |
| `date_format` | string | `Y-m-d` | Log file date format |
| `subject_template` | string | See config | Email subject template |
| `max_file_size_mb` | integer | `10` | Max attachment size (MB) |
| `max_errors_display` | integer | `20` | Max errors in email |
| `from_address` | string | `MAIL_FROM_ADDRESS` | Sender email |
| `from_name` | string | `MAIL_FROM_NAME` | Sender name |

## 🤝 Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## 📄 License

This package is open-sourced software licensed under the [MIT license](LICENSE).

## 🙏 Credits

Developed with ❤️ for Laravel developers who want to stay informed about their application's health.

## 📞 Support

- **Issues:** [GitHub Issues](https://github.com/shyambaldha/laravel-log-monitor/issues)
- **Email:**
  - shyam.b@eheuristic.com
  - girish@eheuristic.com
- **Documentation:** [Full Documentation](https://github.com/shyambaldha/laravel-log-monitor)

## 🔗 Related Packages

- [Laravel Telescope](https://laravel.com/docs/telescope) - For development debugging
- [Sentry for Laravel](https://docs.sentry.io/platforms/php/guides/laravel/) - For production error tracking
- [Laravel Log Viewer](https://github.com/rap2hpoutre/laravel-log-viewer) - Web-based log viewer

---

**Star ⭐ this repository if you find it helpful!**
