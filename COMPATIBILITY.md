# Laravel 8-11 Compatibility Guide

This package is fully compatible with Laravel versions 8.x through 11.x and PHP 7.3 through 8.3.

## 📊 Version Support Matrix

| Laravel Version | PHP Version | Package Support | Release Date |
| --------------- | ----------- | --------------- | ------------ |
| Laravel 8.x     | PHP 7.3+    | ✅ Full Support | Sep 2020     |
| Laravel 9.x     | PHP 8.0+    | ✅ Full Support | Feb 2022     |
| Laravel 10.x    | PHP 8.1+    | ✅ Full Support | Feb 2023     |
| Laravel 11.x    | PHP 8.2+    | ✅ Full Support | Mar 2024     |

## 🔧 Compatibility Changes

The package has been specifically updated to ensure backward compatibility with Laravel 8 and PHP 7.3 while maintaining forward compatibility with Laravel 11 and PHP 8.3.

### Key Changes for Laravel 8 Compatibility

### 1. Type Hints Removed

**Before (Laravel 9+):**

```php
public function register(): void
public function analyzeLogFile(string $filepath): array
```

**After (Laravel 8+):**

```php
public function register()
public function analyzeLogFile($filepath)
```

### 2. Property Types Made Compatible

**Before (PHP 8+):**

```php
protected array $config;
protected string $logPath;
public bool $attachFile;
```

**After (PHP 7.3+):**

```php
protected $config;
protected $logPath;
public $attachFile;
```

### 3. Nullable Return Types

**Before:**

```php
public function getLogFile(Carbon $date): ?string
```

**After:**

```php
public function getLogFile(Carbon $date)
```

## Installation by Laravel Version

### Laravel 8.x

```bash
composer require eheuristic/laravel-log-monitor
```

**Kernel.php Location:** `app/Console/Kernel.php`

```php
protected function schedule(Schedule $schedule)
{
    $schedule->command('log:send-report')
             ->dailyAt('01:00');
}
```

### Laravel 9.x - 11.x

Same installation process. The package auto-detects the Laravel version.

```bash
composer require eheuristic/laravel-log-monitor
```

**Kernel.php Location (Laravel 11):** May use routes/console.php instead

## Testing Compatibility

### Test on Laravel 8

```bash
# Ensure PHP 7.3+ is active
php -v

# Install Laravel 8 project
composer create-project laravel/laravel:^8.0 test-laravel8
cd test-laravel8

# Add package
composer require eheuristic/laravel-log-monitor

# Publish and test
php artisan vendor:publish --tag=log-monitor-config
php artisan log:send-report --help
```

### Test on Laravel 9-11

```bash
# For Laravel 11
composer create-project laravel/laravel:^11.0 test-laravel11
cd test-laravel11

# Add package
composer require eheuristic/laravel-log-monitor

# Test
php artisan log:send-report --help
```

## Laravel 11 Specific Notes

Laravel 11 introduced some directory structure changes, but this package remains compatible:

### Scheduling in Laravel 11

If using the new `routes/console.php` approach:

```php
use Illuminate\Support\Facades\Schedule;

Schedule::command('log:send-report')
    ->dailyAt('01:00');
```

Or traditional `app/Console/Kernel.php` (still supported):

```php
protected function schedule(Schedule $schedule)
{
    $schedule->command('log:send-report')
             ->dailyAt('01:00');
}
```

### Service Provider Auto-Discovery

All Laravel versions (8-11) support auto-discovery. The package will be automatically registered.

## Feature Compatibility Matrix

| Feature           | L8  | L9  | L10 | L11 |
| ----------------- | --- | --- | --- | --- |
| Auto-discovery    | ✅  | ✅  | ✅  | ✅  |
| Config publishing | ✅  | ✅  | ✅  | ✅  |
| View publishing   | ✅  | ✅  | ✅  | ✅  |
| Artisan command   | ✅  | ✅  | ✅  | ✅  |
| Mail sending      | ✅  | ✅  | ✅  | ✅  |
| Task scheduling   | ✅  | ✅  | ✅  | ✅  |
| Log file analysis | ✅  | ✅  | ✅  | ✅  |

## Known Differences

### Carbon Namespace

All versions use `Carbon\Carbon` - no changes needed.

### Mail API

Mailable class structure is identical across all versions - no changes needed.

### File System

`Illuminate\Support\Facades\File` works identically across all versions.

### Command Class

Artisan command structure is backward compatible.

## Troubleshooting by Version

### Laravel 8

**Issue:** Service provider not found  
**Solution:** Run `composer dump-autoload`

**Issue:** Config not publishing  
**Solution:** Clear config cache: `php artisan config:clear`

### Laravel 9+

**Issue:** Type errors  
**Solution:** This shouldn't happen as all type hints are removed for compatibility

### Laravel 11

**Issue:** Schedule not running  
**Solution:** Check both `app/Console/Kernel.php` and `routes/console.php`

## Migration Between Versions

If upgrading your Laravel application:

1. The package continues working without changes
2. No configuration changes needed
3. No code modifications required
4. Re-publish config if needed: `php artisan vendor:publish --tag=log-monitor-config --force`

## Testing on Multiple Versions

To test the package across versions:

```bash
# Laravel 8 (PHP 7.3)
docker run -v $(pwd):/app -w /app php:7.3-cli composer test-laravel8

# Laravel 9 (PHP 8.0)
docker run -v $(pwd):/app -w /app php:8.0-cli composer test-laravel9

# Laravel 10 (PHP 8.1)
docker run -v $(pwd):/app -w /app php:8.1-cli composer test-laravel10

# Laravel 11 (PHP 8.2)
docker run -v $(pwd):/app -w /app php:8.2-cli composer test-laravel11
```

## Backward Compatibility Promise

This package maintains backward compatibility with:

- ✅ Laravel 8.0 to 8.x
- ✅ Laravel 9.0 to 9.x
- ✅ Laravel 10.0 to 10.x
- ✅ Laravel 11.0 to 11.x
- ✅ PHP 7.3 to PHP 8.3

## Future Laravel Versions

The package architecture is designed to be forward-compatible. When Laravel 12 releases, we expect minimal or no changes needed.

## Getting Help

If you encounter version-specific issues:

1. Check your Laravel version: `php artisan --version`
2. Check your PHP version: `php -v`
3. Clear all caches: `php artisan cache:clear && php artisan config:clear`
4. Re-publish config: `php artisan vendor:publish --tag=log-monitor-config --force`

## Reporting Version Issues

When reporting issues, please include:

```bash
php artisan --version
php -v
composer show | grep laravel
```

This helps identify version-specific problems quickly.
