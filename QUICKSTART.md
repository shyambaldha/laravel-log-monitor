# Quick Start Guide - Laravel Log Monitor

Get up and running with Laravel Log Monitor in under 5 minutes! ⚡

## 🎯 Installation (2 minutes)

```bash
# 1. Install package
composer require eheuristic/laravel-log-monitor

# 2. Publish configuration
php artisan vendor:publish --tag=log-monitor-config

# 3. Add to .env
echo "LOG_MONITOR_RECIPIENTS=your-email@example.com" >> .env
```

## ⚙️ Configuration (1 minute)

Edit `app/Console/Kernel.php`:

```php
protected function schedule(Schedule $schedule)
{
    $schedule->command('log:send-report')->dailyAt('01:00');
}
```

## ✅ Test (2 minutes)

```bash
# Generate test errors
php artisan tinker
```

```php
Log::error('Test error');
exit
```

```bash
# Send test email
php artisan log:send-report --date=$(date +%Y-%m-%d) --force
```

**Check your email!** 📧

## 🚀 Production Setup

```bash
# Add cron job (run once)
(crontab -l 2>/dev/null; echo "* * * * * cd /path/to/project && php artisan schedule:run >> /dev/null 2>&1") | crontab -
```

**That's it!** You'll receive daily error reports every morning at 1 AM. 🎉

---

## 📋 Common Configurations

### Multiple Recipients

```env
LOG_MONITOR_RECIPIENTS=admin@example.com,dev@example.com,team@example.com
```

### Different Schedule Times

```php
// Every day at 9 AM
$schedule->command('log:send-report')->dailyAt('09:00');

// Twice daily
$schedule->command('log:send-report')->twiceDaily(9, 17);

// Every hour
$schedule->command('log:send-report')->hourly();
```

### Custom Log Levels

Edit `config/log-monitor.php`:

```php
'monitor_levels' => [
    'emergency',
    'alert',
    'critical',
    'error',
    'warning', // Add warning level
],
```

## 🔧 Troubleshooting

**No email received?**

```bash
# Test mail config
php artisan tinker
Mail::raw('Test', fn($m) => $m->to('test@example.com')->subject('Test'));
```

**Command not found?**

```bash
composer dump-autoload
php artisan config:clear
```

**Still need help?** Check the full [README.md](README.md) or [INSTALLATION.md](INSTALLATION.md)

## 📖 Full Documentation

- [Complete README](README.md) - Comprehensive guide
- [Installation Guide](INSTALLATION.md) - Detailed setup
- [Compatibility](COMPATIBILITY.md) - Laravel 8-11 info
- [Changelog](CHANGELOG.md) - Version history
