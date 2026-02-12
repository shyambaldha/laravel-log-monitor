# Complete Installation Guide - Laravel Log Monitor

This comprehensive guide provides step-by-step instructions for installing and configuring the Laravel Log Monitor package across Laravel 8.x through 11.x.

## 📋 Prerequisites Checklist

Before starting, ensure you have:

- [ ] Laravel 8.x, 9.x, 10.x, or 11.x installed
- [ ] PHP 7.3+ (Laravel 8), PHP 8.0+ (Laravel 9), PHP 8.1+ (Laravel 10), or PHP 8.2+ (Laravel 11)
- [ ] Composer installed and updated
- [ ] Mail driver configured and tested
- [ ] Access to server crontab (for production deployment)
- [ ] Write permissions on `storage/logs` directory

## 🔍 Version Requirements

| Laravel Version | Minimum PHP | Recommended PHP | Package Version |
| --------------- | ----------- | --------------- | --------------- |
| 8.x             | 7.3         | 7.4 - 8.1       | Latest          |
| 9.x             | 8.0         | 8.0 - 8.2       | Latest          |
| 10.x            | 8.1         | 8.1 - 8.3       | Latest          |
| 11.x            | 8.2         | 8.2 - 8.3       | Latest          |

## 🚀 Installation Steps

### Step 1: Verify Your Laravel Version

```bash
# Check Laravel version
php artisan --version

# Check PHP version
php -v

# Verify Composer is working
composer --version
```

### Step 2: Install the Package

#### Via Composer (Recommended)

```bash
composer require eheuristic/laravel-log-monitor
```

#### For Development/Testing

If you're developing or testing the package locally:

```bash
# 1. Create packages directory in your Laravel project
mkdir -p packages/eheuristic/laravel-log-monitor

# 2. Copy package files to this directory

# 3. Add to your root composer.json
```

```json
{
  "repositories": [
    {
      "type": "path",
      "url": "./packages/eheuristic/laravel-log-monitor"
    }
  ],
  "require": {
    "eheuristic/laravel-log-monitor": "@dev"
  }
}
```

```bash
# 4. Install from local path
composer update eheuristic/laravel-log-monitor
```

### Step 3: Verify Installation

```bash
# Check if package is installed
composer show eheuristic/laravel-log-monitor

# Verify Artisan command is registered
php artisan list | grep log:send-report
```

**Expected output:**

```
log:send-report     Send daily log report via email
```

If you see this command, the package is successfully installed! ✅

### Step 4: Publish Configuration File

```bash
php artisan vendor:publish --tag=log-monitor-config
```

**Expected output:**

```
Copied File [/vendor/eheuristic/laravel-log-monitor/config/log-monitor.php]
To [/config/log-monitor.php]
Publishing complete.
```

**Verify the file exists:**

```bash
# Linux/Mac
ls -lh config/log-monitor.php

# Windows
dir config\log-monitor.php
```

### Step 5: Configure Environment Variables

Add the following configuration to your `.env` file:

```env
# ============================================
# Laravel Log Monitor Configuration
# ============================================

# Enable/disable the log monitor
LOG_MONITOR_ENABLED=true

# Email recipients (comma-separated, no spaces)
LOG_MONITOR_RECIPIENTS=admin@yourcompany.com,dev@yourcompany.com,team@yourcompany.com

# Only send emails when errors are found
LOG_MONITOR_ONLY_ERRORS=true

# Email sender details
LOG_MONITOR_FROM_ADDRESS=noreply@yourcompany.com
LOG_MONITOR_FROM_NAME="Laravel Log Monitor"
```

**Configuration Notes:**

- **LOG_MONITOR_ENABLED**: Set to `false` in development if you don't want daily emails
- **LOG_MONITOR_RECIPIENTS**: Add all team members who should receive reports
- **LOG_MONITOR_ONLY_ERRORS**: Set to `false` to receive daily emails even with no errors
- **LOG_MONITOR_FROM_ADDRESS**: Must be a verified sender in your mail service
- **LOG_MONITOR_FROM_NAME**: Useful for identifying environment (Production, Staging, etc.)

### Step 6: Configure Mail Driver

Ensure your mail configuration is properly set in `.env`. Choose one of the following:

#### Option 1: SMTP (Gmail Example)

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-specific-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourcompany.com
MAIL_FROM_NAME="${APP_NAME}"
```

**Gmail Note:** You must use an [App Password](https://support.google.com/accounts/answer/185833), not your regular password.

````

### Step 7: Test Mail Configuration

Before proceeding, verify your mail setup works correctly:

```bash
php artisan tinker
````

Run this code in Tinker:

```php
use Illuminate\Support\Facades\Mail;

Mail::raw('Test email from Laravel Log Monitor setup', function($message) {
    $message->to('your-email@example.com')
            ->subject('Test Email - Laravel Log Monitor Installation');
});

// Expected output: null (means success)
```

Press `Ctrl+D` (Linux/Mac) or `Ctrl+Z` then Enter (Windows) to exit Tinker.

**Check your inbox** - you should receive the test email within moments. ✅

If you didn't receive it:

1. Check spam folder
2. Verify mail credentials in `.env`
3. Check `storage/logs/laravel.log` for mail errors
4. Ensure sender email is verified with your mail provider

### Step 8: Schedule the Command

The configuration differs slightly between Laravel versions:

#### For Laravel 8.x, 9.x, and 10.x

Edit `app/Console/Kernel.php`:

```php
<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Send yesterday's log report daily at 1:00 AM
        $schedule->command('log:send-report')
                 ->dailyAt('01:00')
                 ->timezone('Asia/Kolkata') // Change to your timezone
                 ->onFailure(function () {
                     // Optional: Log scheduling failures
                     \Log::error('Log monitor scheduled task failed');
                 });
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
```

#### For Laravel 11.x

Laravel 11 introduced a streamlined approach. You have two options:

**Option 1: Using routes/console.php (Recommended for Laravel 11)**

Edit `routes/console.php`:

```php
<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

// Add log monitor scheduling
Schedule::command('log:send-report')
    ->dailyAt('01:00')
    ->timezone('Asia/Kolkata') // Change to your timezone
    ->onFailure(function () {
        \Log::error('Log monitor scheduled task failed');
    });
```

**Option 2: Traditional Kernel.php (Also works in Laravel 11)**

If you prefer the traditional approach or if `app/Console/Kernel.php` already exists:

```php
<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('log:send-report')
                 ->dailyAt('01:00')
                 ->timezone('Asia/Kolkata');
    }
}
```

#### Common Scheduling Options

```php
// Every day at specific time
->dailyAt('01:00')

// Multiple times per day
->dailyAt('09:00');  // 9 AM
->dailyAt('17:00');  // 5 PM

// Every hour
->hourly()

// Every 6 hours
->everySixHours()

// Weekdays only
->dailyAt('09:00')->weekdays()

// Weekends only
->dailyAt('10:00')->weekends()

// Specific days
->dailyAt('09:00')->mondays()
->dailyAt('09:00')->fridays()

// With timezone
->dailyAt('01:00')->timezone('America/New_York')

// Only in production
->dailyAt('01:00')->environments(['production'])
```

### Step 9: Configure Server Cron Job

For the Laravel scheduler to work, you need a single cron entry on your server:

#### On Production Server (Linux)

```bash
# Edit crontab
crontab -e

# Add this single line (replace path with your actual project path)
* * * * * cd /var/www/your-laravel-project && php artisan schedule:run >> /dev/null 2>&1
```

**Important Notes:**

- Replace `/var/www/your-laravel-project` with your actual project path
- This runs every minute and Laravel determines which tasks to execute
- Only ONE cron entry is needed for ALL scheduled tasks

#### For Laravel Forge Users

✅ **No action needed** - Forge automatically configures the scheduler cron job.

#### For Laravel Vapor Users

✅ **No action needed** - Vapor handles scheduling automatically.

#### For Shared Hosting

```bash
# Full path example
* * * * * /usr/bin/php8.2 /home/username/public_html/artisan schedule:run >> /dev/null 2>&1
```

Adjust PHP version and paths according to your hosting setup.

#### Verify Cron Setup

```bash
# List current cron jobs
crontab -l

# Check cron service status (Linux)
sudo service cron status

# View cron logs (Linux)
grep CRON /var/log/syslog | tail -20
```

### Step 10: Generate Test Errors

Create sample errors to test the package functionality:

```bash
php artisan tinker
```

Generate various error levels:

```php
use Illuminate\Support\Facades\Log;

// Generate different error levels
Log::error('Database connection timeout', [
    'host' => 'localhost',
    'port' => 3306,
    'timeout' => 30
]);

Log::error('Failed to process payment', [
    'user_id' => 123,
    'amount' => 99.99,
    'error' => 'Payment gateway unreachable'
]);

Log::critical('Critical system error: Out of disk space', [
    'available' => '500MB',
    'required' => '2GB'
]);

Log::emergency('Emergency! Database server is down!', [
    'server' => 'mysql-primary',
    'last_seen' => now()
]);

Log::alert('Alert! High memory usage detected', [
    'current' => '95%',
    'threshold' => '80%'
]);

Log::warning('API rate limit approaching', [
    'current' => 980,
    'limit' => 1000
]);

// Exit Tinker
exit
```

### Step 11: Verify Log File

Check that errors were written to the log file:

```bash
# View today's log file
tail -50 storage/logs/laravel-$(date +%Y-%m-%d).log

# Or use this for readability
tail -50 storage/logs/laravel.log
```

You should see your test errors in the output.

### Step 12: Test the Command Manually

Run the command to send a report for today's logs:

```bash
# For today's logs (use --force to send even if no errors configured)
php artisan log:send-report --date=$(date +%Y-%m-%d) --force

# For a specific date
php artisan log:send-report --date=2025-01-23 --force

# For yesterday (without --force, respects only_errors setting)
php artisan log:send-report
```

**Expected output:**

```
Generating log report for: 2025-01-24
Analyzing log file: /path/to/storage/logs/laravel-2025-01-24.log
Found 6 error(s)

Error breakdown by level:
  - error: 2
  - critical: 1
  - emergency: 1
  - alert: 1
  - warning: 1

Sending email to: admin@yourcompany.com, dev@yourcompany.com
✓ Email sent successfully!
```

### Step 13: Verify Email Delivery

Within a few moments, all recipients should receive an email containing:

**Email Subject:**

```
[Production] Log Report - 2025-01-24 (6 errors)
```

**Email Contents:**

- Summary of total errors
- Breakdown by error level
- Top error messages with timestamps
- Full stack traces
- Context data for each error
- Attached log file (if under 10MB)

**✅ Success Indicators:**

- Email received by all recipients
- Error counts match your test errors
- Stack traces are visible and readable
- Log file attachment is present (if applicable)

### Step 14: Verify Scheduled Tasks

```bash
# List all scheduled tasks
php artisan schedule:list
```

**Expected output:**

```
  0 1 * * *  log:send-report ............................ Next Due: 11 hours from now
```

Test the scheduler manually:

```bash
# Run all scheduled tasks that are due
php artisan schedule:run
```

Test a specific scheduled task:

```bash
# Run scheduler in test mode (shows what would run)
php artisan schedule:test
```

## ✅ Complete Installation Verification Checklist

Go through this checklist to ensure everything is configured correctly:

- [ ] Package installed via Composer (`composer show eheuristic/laravel-log-monitor`)
- [ ] Configuration file exists at `config/log-monitor.php`
- [ ] Environment variables configured in `.env`
- [ ] Mail driver configured and test email received
- [ ] Recipients properly set (no spaces in comma-separated list)
- [ ] Test errors generated in log file
- [ ] Log file exists and is readable
- [ ] Command appears in `php artisan list`
- [ ] Manual command execution successful
- [ ] Email received with correct error information
- [ ] Email formatting looks professional
- [ ] Stack traces are visible and complete
- [ ] Log file attached (if under 10MB)
- [ ] Schedule configured in Kernel.php or routes/console.php
- [ ] Schedule appears in `php artisan schedule:list`
- [ ] Cron job configured on server (production)
- [ ] Cron service running (`service cron status`)
- [ ] Timezone correctly set
- [ ] FROM address matches your mail provider

## 🔧 Post-Installation Configuration

### Customize Monitored Log Levels

Edit `config/log-monitor.php` to adjust which log levels trigger emails:

```php
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
        'emergency',  // System is unusable
        'alert',      // Action must be taken immediately
        'critical',   // Critical conditions
        'error',      // Runtime errors
        'warning',    // Exceptional occurrences that are not errors
        // 'notice',  // Normal but significant events
        // 'info',    // Interesting events
        // 'debug',   // Detailed debug information
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
    | Log Monitor Package Assets
    |--------------------------------------------------------------------------
    |
    | These assets are used inside email templates and views.
    | Assets will be published to:
    |
    |   public/vendor/log-monitor/assets
    |
    | You can override these files by replacing them after publish
    | without touching the package code.
    |
    */

    'assets' => [
        'summaryOverviewIcon' => 'summary-overview.png',
        'applicationLogReportIcon' => 'application-log-report.png',
        'folderIcon' => 'folder.png',
        'calendarIcon' => 'calendar.png',
    ],
];
```

### Customize Email Template

Publish the email views:

```bash
php artisan vendor:publish --tag=log-monitor-views
```

This creates:

```
resources/views/vendor/log-monitor/emails/log-report.blade.php
```

Edit this file to customize:

- Email styling and branding
- Layout and structure
- Color scheme
- Additional information
- Footer content

### Environment-Specific Configuration

#### Development Environment

`.env.local` or `.env`:

```env
LOG_MONITOR_ENABLED=false
# Or send to development team only
LOG_MONITOR_RECIPIENTS=dev@yourcompany.com
LOG_MONITOR_ONLY_ERRORS=false
```

#### Staging Environment

`.env.staging`:

```env
LOG_MONITOR_ENABLED=true
LOG_MONITOR_RECIPIENTS=qa@yourcompany.com,dev@yourcompany.com
LOG_MONITOR_ONLY_ERRORS=true
LOG_MONITOR_FROM_NAME="${APP_NAME} Staging Log Monitor"
```

#### Production Environment

`.env.production`:

```env
LOG_MONITOR_ENABLED=true
LOG_MONITOR_RECIPIENTS=admin@yourcompany.com,ops@yourcompany.com,oncall@yourcompany.com
LOG_MONITOR_ONLY_ERRORS=true
LOG_MONITOR_FROM_NAME="${APP_NAME} Production Log Monitor"
```

## 🌍 Timezone Configuration

### Common Timezones

```php
// India Standard Time
->timezone('Asia/Kolkata')

// United States
->timezone('America/New_York')      // Eastern Time
->timezone('America/Chicago')       // Central Time
->timezone('America/Denver')        // Mountain Time
->timezone('America/Los_Angeles')   // Pacific Time

// Europe
->timezone('Europe/London')         // UK
->timezone('Europe/Paris')          // France/Central Europe
->timezone('Europe/Berlin')         // Germany

// Asia Pacific
->timezone('Asia/Tokyo')            // Japan
->timezone('Asia/Singapore')        // Singapore
->timezone('Asia/Dubai')            // UAE
->timezone('Australia/Sydney')      // Australia East
->timezone('Pacific/Auckland')      // New Zealand

// Universal
->timezone('UTC')                   // Coordinated Universal Time
```

### Set Application-Wide Timezone

In `config/app.php`:

```php
'timezone' => 'Asia/Kolkata',
```

## 🧪 Testing and Validation

### Test Different Scenarios

#### Test 1: Report with Errors

```bash
# Generate errors
php artisan tinker
Log::error('Test error 1');
Log::critical('Test critical error');
exit

# Send report
php artisan log:send-report --date=$(date +%Y-%m-%d) --force
```

**Expected:** Email received with 2 errors

#### Test 2: Report with No Errors

```bash
# Don't generate any errors
# Send report
php artisan log:send-report --date=$(date +%Y-%m-%d) --force
```

**Expected:**

- If `LOG_MONITOR_ONLY_ERRORS=true`: No email sent
- If `LOG_MONITOR_ONLY_ERRORS=false`: Email sent with "No errors found"

#### Test 3: Specific Date

```bash
# Send report for a past date
php artisan log:send-report --date=2025-01-20 --force
```

**Expected:** Email with errors from that specific date (if log file exists)

#### Test 4: Missing Log File

```bash
# Try a date with no log file
php artisan log:send-report --date=2020-01-01 --force
```

**Expected:** Message indicating log file not found

### Validate Scheduling

```bash
# Run scheduler manually
php artisan schedule:run

# Check if log report task ran
tail -20 storage/logs/laravel.log | grep "log:send-report"

# Test specific time (for testing, temporarily change schedule to current time)
# In Kernel.php: ->dailyAt('14:30') // Change to current time + 2 minutes
# Wait and check
php artisan schedule:run
```

## 🐛 Troubleshooting Guide

### Problem: Command Not Found

**Symptoms:**

```
Command "log:send-report" is not defined.
```

**Solutions:**

```bash
# 1. Clear all caches
php artisan clear-compiled
php artisan config:clear
php artisan cache:clear

# 2. Regenerate autoload files
composer dump-autoload

# 3. Verify package installation
composer show eheuristic/laravel-log-monitor

# 4. Reinstall if necessary
composer remove eheuristic/laravel-log-monitor
composer require eheuristic/laravel-log-monitor
```

### Problem: No Email Received

**Diagnostic Steps:**

```bash
# 1. Check Laravel logs
tail -50 storage/logs/laravel.log

# 2. Test mail configuration
php artisan tinker
Mail::raw('Test', function($m) { $m->to('test@example.com')->subject('Test'); });

# 3. Verify queue is running (if using queued mail)
php artisan queue:work --once

# 4. Check mail driver
php artisan config:clear
php artisan tinker
config('mail.default');
```

**Common Causes:**

1. **Mail driver not configured** - Check `.env` for MAIL\_\* variables
2. **Queue not running** - If using queue driver, ensure queue worker is active
3. **Recipients not set** - Verify LOG_MONITOR_RECIPIENTS in `.env`
4. **No errors to report** - Check LOG_MONITOR_ONLY_ERRORS setting
5. **Spam folder** - Check recipient spam/junk folder
6. **Invalid sender** - Ensure FROM address is verified with mail provider

### Problem: "Class Not Found" Errors

**Symptoms:**

```
Class 'Eheuristic\LaravelLogMonitor\...' not found
```

**Solutions:**

```bash
# Clear and rebuild autoload
composer dump-autoload -o

# Clear application cache
php artisan clear-compiled
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Verify composer.json has correct PSR-4 autoloading
composer validate

# Reinstall package
composer update eheuristic/laravel-log-monitor
```

### Problem: Scheduler Not Running

**Diagnostic Steps:**

```bash
# 1. Verify cron is running
sudo service cron status

# 2. Check cron logs
sudo grep CRON /var/log/syslog | tail -20

# 3. Verify crontab entry
crontab -l

# 4. Test schedule manually
php artisan schedule:run

# 5. List scheduled tasks
php artisan schedule:list

# 6. Run schedule with verbosity
php artisan schedule:run -v
```

**Common Causes:**

1. **Cron not configured** - Add cron entry
2. **Wrong path in cron** - Verify project path
3. **PHP path incorrect** - Use full path to PHP binary
4. **Permissions issue** - Ensure www-data can access project
5. **Timezone mismatch** - Check server timezone vs Laravel timezone

### Problem: Permission Denied on Log Files

**Symptoms:**

```
Permission denied: /path/to/storage/logs/laravel-2025-01-24.log
```

**Solutions:**

```bash
# For production (Linux)
sudo chown -R www-data:www-data storage/logs
sudo chmod -R 775 storage/logs

# For development
chmod -R 777 storage/logs

# Verify permissions
ls -lh storage/logs/

# Check storage directory permissions
ls -lh storage/
```

### Problem: Large Log File Not Attached

**Cause:** Log file exceeds 10MB default limit

**Solutions:**

1. **Increase attachment size limit** in `config/log-monitor.php`:

```php
'max_file_size_mb' => 25, // Increase to 25MB
```

### Problem: Wrong Timezone in Reports

**Solutions:**

```bash
# 1. Set in schedule
->timezone('Asia/Kolkata')

# 2. Set application timezone in config/app.php
'timezone' => 'Asia/Kolkata',

# 3. Verify server timezone
date
timedatectl  # Linux

# 4. Test in Tinker
php artisan tinker
now();  // Should show correct timezone
config('app.timezone');
```

### Problem: Queue Jobs Failing

**If using queued mail:**

```bash
# Check failed jobs
php artisan queue:failed

# Retry failed jobs
php artisan queue:retry all

# Monitor queue in real-time
php artisan queue:listen --verbose

# Clear failed jobs (careful!)
php artisan queue:flush
```

## 📚 Advanced Configuration

### Using with Queue System

For better performance on large log files:

#### 1. Configure Queue Driver

In `.env`:

```env
QUEUE_CONNECTION=database  # or redis, sqs, etc.
```

#### 2. Create Jobs Table (if using database)

```bash
php artisan queue:table
php artisan migrate
```

#### 3. Modify Schedule to Use Queue

In `Kernel.php` or `routes/console.php`:

```php
$schedule->command('log:send-report')
         ->dailyAt('01:00')
         ->runInBackground();  // Run in background
```

#### 4. Run Queue Worker

```bash
# In production, use supervisor or systemd
php artisan queue:work --daemon

# Or schedule queue worker
$schedule->command('queue:work --stop-when-empty')
         ->everyMinute()
         ->withoutOverlapping();
```

### Multiple Reports Per Day

```php
// Morning report (errors from midnight to 8 AM)
$schedule->command('log:send-report --date=' . now()->format('Y-m-d'))
         ->dailyAt('08:00');

// Evening report (errors from 8 AM to 5 PM)
$schedule->command('log:send-report --date=' . now()->format('Y-m-d'))
         ->dailyAt('17:00');

// Night report (errors from 5 PM to midnight)
$schedule->command('log:send-report --date=' . now()->format('Y-m-d'))
         ->dailyAt('23:30');
```

### Different Reports for Different Environments

In `Kernel.php`:

```php
// Production: Send to ops team
if (app()->environment('production')) {
    $schedule->command('log:send-report')
             ->dailyAt('01:00')
             ->timezone('UTC');
}

// Staging: Send to QA team
if (app()->environment('staging')) {
    $schedule->command('log:send-report')
             ->dailyAt('09:00')
             ->timezone('Asia/Kolkata');
}

// Development: Don't send automatically
// (manual testing only)
```

### Slack Notifications on Critical Errors

Extend functionality to send Slack alerts:

```php
// In app/Console/Kernel.php
$schedule->command('log:send-report')
         ->dailyAt('01:00')
         ->onFailure(function () {
             // Notify via Slack
             Notification::route('slack', config('services.slack.webhook'))
                 ->notify(new LogReportFailedNotification());
         });
```

## 💡 Best Practices

### 1. Environment Separation

- **Development**: Disable or send to dev team only
- **Staging**: Send to QA and dev teams
- **Production**: Send to ops and on-call teams

### 2. Log Rotation

Implement daily log rotation to prevent huge files:

```php
// In Kernel.php
$schedule->call(function () {
    $date = now()->subDays(7)->format('Y-m-d');
    $oldLog = storage_path("logs/laravel-{$date}.log");
    if (file_exists($oldLog)) {
        unlink($oldLog); // Delete logs older than 7 days
    }
})->daily();
```

### 3. Monitoring and Alerts

- Set up email filters to highlight critical/emergency errors
- Create Slack channels for different error severities
- Use monitoring tools (Sentry, Bugsnag) alongside this package

### 4. Regular Testing

```php
// Add to schedule (runs every Sunday at 2 AM)
$schedule->command('log:send-report --force')
         ->weeklyOn(0, '02:00')
         ->environments(['staging']);
```

### 5. Security Considerations

- Never commit `.env` file to version control
- Use environment-specific mail credentials
- Rotate mail service API keys regularly
- Restrict log file access permissions
- Consider encrypting sensitive data in logs

## 🔗 Additional Resources

### Official Documentation

- [Laravel Task Scheduling](https://laravel.com/docs/11.x/scheduling)
- [Laravel Mail](https://laravel.com/docs/11.x/mail)
- [Laravel Logging](https://laravel.com/docs/11.x/logging)
- [Laravel Queues](https://laravel.com/docs/11.x/queues)

###
