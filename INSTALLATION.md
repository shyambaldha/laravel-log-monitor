# Complete Installation Guide - Laravel Log Monitor

This guide provides detailed, step-by-step instructions for installing and configuring the Laravel Log Monitor package on Laravel 8-11.

## 📋 Prerequisites Checklist

Before starting, ensure you have:

- [ ] Laravel 8.x, 9.x, 10.x, or 11.x installed
- [ ] PHP 7.3+ (Laravel 8) or PHP 8.0+ (Laravel 9+)
- [ ] Composer installed
- [ ] Mail driver configured and tested
- [ ] Access to server crontab (for production)

## 🚀 Installation Steps

### Step 1: Install the Package

#### Via Composer (Recommended)

```bash
composer require eheuristic/laravel-log-monitor
```

#### Local Development Setup

If developing the package locally:

```bash
# 1. Create package directory in your Laravel project
mkdir -p packages/eheuristic/laravel-log-monitor

# 2. Copy all package files to this directory

# 3. Add to your main composer.json:
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
# 4. Install
composer update eheuristic/laravel-log-monitor
```

### Step 2: Verify Installation

```bash
# Check if package is installed
composer show eheuristic/laravel-log-monitor

# Verify command is registered
php artisan list | grep log:send-report
```

You should see:

```
log:send-report     Send daily log report via email
```

### Step 3: Publish Configuration

```bash
php artisan vendor:publish --tag=log-monitor-config
```

**Expected output:**

```
Copied File [/vendor/eheuristic/laravel-log-monitor/config/log-monitor.php]
To [/config/log-monitor.php]
```

**Verify:** Check that `config/log-monitor.php` exists.

### Step 4: Configure Environment Variables

Add these lines to your `.env` file:

```env
# Laravel Log Monitor Configuration
LOG_MONITOR_ENABLED=true
LOG_MONITOR_RECIPIENTS=admin@yourcompany.com,dev@yourcompany.com,team@yourcompany.com
LOG_MONITOR_ONLY_ERRORS=true
LOG_MONITOR_FROM_ADDRESS=noreply@yourcompany.com
LOG_MONITOR_FROM_NAME="Laravel Log Monitor"
```

**Important Notes:**

- Replace email addresses with your actual recipients
- Multiple recipients are separated by commas (no spaces needed)
- Use `LOG_MONITOR_ONLY_ERRORS=false` to receive daily emails regardless of errors

### Step 5: Configure Mail Driver

Ensure your mail configuration is set in `.env`:

#### SMTP (Gmail Example)

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourcompany.com
MAIL_FROM_NAME="${APP_NAME}"
```

#### Mailgun

```env
MAIL_MAILER=mailgun
MAILGUN_DOMAIN=your-domain.mailgun.org
MAILGUN_SECRET=your-secret-key
MAIL_FROM_ADDRESS=noreply@yourcompany.com
MAIL_FROM_NAME="${APP_NAME}"
```

#### AWS SES

```env
MAIL_MAILER=ses
AWS_ACCESS_KEY_ID=your-access-key
AWS_SECRET_ACCESS_KEY=your-secret-key
AWS_DEFAULT_REGION=us-east-1
MAIL_FROM_ADDRESS=noreply@yourcompany.com
MAIL_FROM_NAME="${APP_NAME}"
```

### Step 6: Test Mail Configuration

Before proceeding, test your mail setup:

```bash
php artisan tinker
```

```php
use Illuminate\Support\Facades\Mail;

Mail::raw('Test email from Laravel', function($message) {
    $message->to('your-email@example.com')
            ->subject('Test Email - Laravel Log Monitor');
});
```

Press `Ctrl+D` to exit Tinker.

**Check your inbox** - if you received the email, mail is configured correctly! ✅

### Step 7: Schedule the Command

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
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // Send yesterday's log report daily at 1:00 AM
        $schedule->command('log:send-report')
                 ->dailyAt('01:00')
                 ->timezone('Asia/Kolkata'); // Change to your timezone
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
```

**Laravel 11 Note:** You can also use `routes/console.php`:

```php
use Illuminate\Support\Facades\Schedule;

Schedule::command('log:send-report')
    ->dailyAt('01:00')
    ->timezone('Asia/Kolkata');
```

### Step 8: Configure Server Cron

On your production server, set up the cron job:

```bash
# Edit crontab
crontab -e

# Add this line (adjust path to your project)
* * * * * cd /var/www/your-laravel-project && php artisan schedule:run >> /dev/null 2>&1
```

**Important:** Replace `/var/www/your-laravel-project` with your actual project path.

**For Laravel Forge users:** This is already configured automatically.

### Step 9: Generate Test Errors

Create some test errors to verify the package works:

```bash
php artisan tinker
```

```php
use Illuminate\Support\Facades\Log;

// Generate different error levels
Log::error('Test error message #1');
Log::error('Database connection failed', ['host' => 'localhost', 'port' => 3306]);
Log::critical('Critical system error occurred');
Log::emergency('Emergency! System is down!');
Log::alert('Alert! High memory usage detected');

exit
```

### Step 10: Verify Log File Created

```bash
# Check today's log file exists
ls -lh storage/logs/

# View the log file
tail -20 storage/logs/laravel-$(date +%Y-%m-%d).log
```

You should see your test errors in the log file.

### Step 11: Test the Command Manually

Run the command for today's logs:

```bash
php artisan log:send-report --date=$(date +%Y-%m-%d) --force
```

**Expected output:**

```
Generating log report for: 2025-01-23
Analyzing log file: /path/to/storage/logs/laravel-2025-01-23.log
Found 5 error(s)

Error breakdown by level:
  - error: 2
  - critical: 1
  - emergency: 1
  - alert: 1

Sending email to: admin@yourcompany.com, dev@yourcompany.com
✓ Email sent successfully!
```

### Step 12: Check Your Email

Within a few moments, all recipients should receive an email with:

- Summary of errors
- Detailed error messages
- Stack traces
- Log file attached (if under 10MB)

## ✅ Installation Verification Checklist

- [ ] Package installed via Composer
- [ ] Configuration file published to `config/log-monitor.php`
- [ ] Environment variables set in `.env`
- [ ] Mail driver configured and tested
- [ ] Test errors generated in log file
- [ ] Command executed manually with success
- [ ] Email received by all recipients
- [ ] Email contains correct error information
- [ ] Schedule configured in `Kernel.php`
- [ ] Cron job set up (production only)
- [ ] Scheduled tasks verified with `php artisan schedule:list`

## 🔧 Post-Installation Configuration

### Customize Monitored Log Levels

Edit `config/log-monitor.php`:

```php
'monitor_levels' => [
    'emergency',
    'alert',
    'critical',
    'error',
    'warning',  // Add if you want to monitor warnings
    // 'notice',
    // 'info',
    // 'debug',
],
```

### Adjust Email Template (Optional)

Publish views:

```bash
php artisan vendor:publish --tag=log-monitor-views
```

Edit:

```
resources/views/vendor/log-monitor/emails/log-report.blade.php
```

### Change Schedule Time

Common scheduling options:

```php
// Every day at 2:00 AM
$schedule->command('log:send-report')->dailyAt('02:00');

// Every day at 9:00 AM
$schedule->command('log:send-report')->dailyAt('09:00');

// Every hour
$schedule->command('log:send-report')->hourly();

// Multiple times per day
$schedule->command('log:send-report')->dailyAt('09:00');
$schedule->command('log:send-report')->dailyAt('17:00');

// Weekdays only at 9 AM
$schedule->command('log:send-report')->dailyAt('09:00')->weekdays();
```

## 🌍 Common Timezones

```php
// India
->timezone('Asia/Kolkata')

// United States - Eastern
->timezone('America/New_York')

// United States - Pacific
->timezone('America/Los_Angeles')

// United Kingdom
->timezone('Europe/London')

// Australia - Sydney
->timezone('Australia/Sydney')

// UTC (Universal Time)
->timezone('UTC')
```

## 🧪 Testing Scheduled Tasks

Verify your schedule:

```bash
php artisan schedule:list
```

**Expected output:**

```
0 1 * * *  log:send-report ............................ Next Due: 11 hours from now
```

Test the scheduler manually:

```bash
php artisan schedule:run
```

**Note:** This runs ALL scheduled tasks, not just the log report.

## 🐛 Troubleshooting Common Issues

### Issue: Command not found

**Solution:**

```bash
composer dump-autoload
php artisan clear-compiled
php artisan config:clear
```

### Issue: No email received

**Checklist:**

1. Mail driver configured? Test with `php artisan tinker`
2. Recipients set in `.env`?
3. Log file exists for the date?
4. Run with `--force` flag
5. Check Laravel error logs

### Issue: "Class not found" errors

**Solution:**

```bash
composer dump-autoload
php artisan config:cache
php artisan route:cache
```

### Issue: Scheduler not running

**Solution:**

```bash
# Verify cron is running
sudo service cron status

# Check cron logs
grep CRON /var/log/syslog

# Test manually
php artisan schedule:run
```

### Issue: Permission denied on log files

**Solution:**

```bash
# Fix permissions
sudo chown -R www-data:www-data storage/logs
chmod -R 775 storage/logs

# For development
chmod -R 777 storage/logs
```

## 📚 Next Steps

1. **Monitor First Run** - Wait for tomorrow's scheduled run
2. **Review Email** - Check that emails are formatted correctly
3. **Adjust Configuration** - Fine-tune based on your needs
4. **Set Up Alerts** - Configure email filters for critical errors
5. **Document** - Share configuration with your team

## 🔗 Additional Resources

- [Laravel Task Scheduling](https://laravel.com/docs/scheduling)
- [Laravel Mail Configuration](https://laravel.com/docs/mail)
- [Laravel Logging](https://laravel.com/docs/logging)
- [Cron Expression Generator](https://crontab.guru/)

## 💡 Pro Tips

1. **Use Queues** - For large log files, run the command in the background
2. **Separate Environments** - Use different recipients for staging vs production
3. **Log Rotation** - Set up log rotation to manage disk space
4. **Test Regularly** - Periodically test with `--force` to ensure it's working
5. **Monitor Failures** - Set up notifications for failed schedule runs

---

**Installation complete! 🎉**

Your Laravel Log Monitor is now ready to keep you informed about your application's health.
