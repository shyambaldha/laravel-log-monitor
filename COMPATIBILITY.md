# Laravel 8-11 Compatibility Guide

This package provides full compatibility with Laravel versions 8.x through 11.x and PHP 7.3 through 8.3.

## 📊 Version Support Matrix

| Laravel Version | PHP Requirements | Support Status     | Released       |
| --------------- | ---------------- | ------------------ | -------------- |
| 8.x             | 7.3 - 8.1        | ✅ Fully Supported | September 2020 |
| 9.x             | 8.0 - 8.2        | ✅ Fully Supported | February 2022  |
| 10.x            | 8.1 - 8.3        | ✅ Fully Supported | February 2023  |
| 11.x            | 8.2 - 8.3        | ✅ Fully Supported | March 2024     |

## Installation

### Standard Installation (All Versions)

```bash
composer require eheuristic/laravel-log-monitor
```

The package automatically detects your Laravel version and adapts accordingly.

### Publish Configuration

```bash
php artisan vendor:publish --tag=log-monitor-config
```

### Publish Views

```bash
php artisan vendor:publish --tag=log-monitor-views
```

### Publish Assets

```bash
php artisan vendor:publish --tag=log-monitor-assets
```

## Configuration by Laravel Version

### Laravel 8.x - 10.x

**Scheduling Commands**

Edit `app/Console/Kernel.php`:

```php
<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('log:send-report')
                 ->dailyAt('01:00');
    }
}
```

### Laravel 11.x

Laravel 11 introduced streamlined application structure with two scheduling options:

**Option 1: Using routes/console.php (Recommended)**

```php
<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('log:send-report')->dailyAt('01:00');
```

**Option 2: Traditional Kernel.php (Still Supported)**

If you prefer the traditional approach, create `app/Console/Kernel.php`:

```php
<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('log:send-report')
                 ->dailyAt('01:00');
    }
}
```

## Testing Installation

### Laravel 8.x

```bash
# Create test project
composer create-project laravel/laravel:^8.0 test-project
cd test-project

# Verify PHP version (7.3+)
php -v

# Install package
composer require eheuristic/laravel-log-monitor

# Publish and verify
php artisan vendor:publish --tag=log-monitor-config
php artisan log:send-report --help
```

### Laravel 9.x

```bash
# Create test project
composer create-project laravel/laravel:^9.0 test-project
cd test-project

# Verify PHP version (8.0+)
php -v

# Install package
composer require eheuristic/laravel-log-monitor

# Test command
php artisan log:send-report --help
```

### Laravel 10.x

```bash
# Create test project
composer create-project laravel/laravel:^10.0 test-project
cd test-project

# Verify PHP version (8.1+)
php -v

# Install package
composer require eheuristic/laravel-log-monitor

# Test command
php artisan log:send-report --help
```

### Laravel 11.x

```bash
# Create test project
composer create-project laravel/laravel:^11.0 test-project
cd test-project

# Verify PHP version (8.2+)
php -v

# Install package
composer require eheuristic/laravel-log-monitor

# Test command
php artisan log:send-report --help
```

## Feature Compatibility

All features work identically across all supported Laravel versions:

| Feature                  | Laravel 8 | Laravel 9 | Laravel 10 | Laravel 11 |
| ------------------------ | --------- | --------- | ---------- | ---------- |
| Package Auto-Discovery   | ✅        | ✅        | ✅         | ✅         |
| Configuration Publishing | ✅        | ✅        | ✅         | ✅         |
| View Publishing          | ✅        | ✅        | ✅         | ✅         |
| Artisan Commands         | ✅        | ✅        | ✅         | ✅         |
| Email Notifications      | ✅        | ✅        | ✅         | ✅         |
| Task Scheduling          | ✅        | ✅        | ✅         | ✅         |
| Log File Parsing         | ✅        | ✅        | ✅         | ✅         |
| Custom Channels          | ✅        | ✅        | ✅         | ✅         |

## Laravel 11 Specific Changes

Laravel 11 introduced architectural improvements while maintaining backward compatibility:

### Directory Structure

- **Config files**: Published to `config/` (unchanged)
- **Views**: Published to `resources/views/vendor/log-monitor` (unchanged)
- **Console scheduling**: Can use either `routes/console.php` or `app/Console/Kernel.php`

### Service Provider Registration

Service providers are automatically discovered via `composer.json`. No manual registration required.

### Bootstrap Changes

Laravel 11's streamlined bootstrap doesn't affect this package. All functionality works without modification.

## Upgrading Between Laravel Versions

When upgrading your Laravel application, the package continues working without changes:

### From Laravel 8 to 9

```bash
# Upgrade Laravel
composer update

# No package changes needed
# Optionally re-publish config
php artisan vendor:publish --tag=log-monitor-config --force
```

### From Laravel 9 to 10

```bash
# Upgrade Laravel and PHP (8.1+)
composer update

# Clear caches
php artisan config:clear
php artisan cache:clear
```

### From Laravel 10 to 11

```bash
# Upgrade Laravel and PHP (8.2+)
composer update

# If migrating to routes/console.php, move schedule from Kernel.php
# Otherwise, no changes needed
```

## Troubleshooting

### Common Issues

**Service Provider Not Found (Laravel 8)**

```bash
composer dump-autoload
php artisan config:clear
```

**Config Not Publishing**

```bash
php artisan config:clear
php artisan vendor:publish --tag=log-monitor-config --force
```

**Scheduled Task Not Running (Laravel 11)**

Check both scheduling locations:

- `routes/console.php`
- `app/Console/Kernel.php` (if it exists)

Verify scheduler is running:

```bash
php artisan schedule:list
```

**Type Errors**

Ensure your PHP version matches Laravel requirements. The package removes type hints for maximum compatibility.

### Version-Specific Debugging

Check your environment:

```bash
# Laravel version
php artisan --version

# PHP version
php -v

# Installed packages
composer show | grep laravel

# Package installation
composer show eheuristic/laravel-log-monitor
```

## API Compatibility Notes

### Consistent APIs Across Versions

The following Laravel components work identically across all versions:

- **Carbon**: Uses `Carbon\Carbon` namespace consistently
- **Mail**: Mailable class structure unchanged
- **Filesystem**: `Illuminate\Support\Facades\File` API stable
- **Commands**: Console command structure backward compatible
- **Config**: Configuration API unchanged
- **Logging**: Log channel configuration consistent

### No Breaking Changes

This package does not use any Laravel features that changed between versions 8-11, ensuring seamless compatibility.

## Backward Compatibility Guarantee

We commit to maintaining compatibility with:

- ✅ Laravel 8.0+ (minimum PHP 7.3)
- ✅ Laravel 9.0+ (minimum PHP 8.0)
- ✅ Laravel 10.0+ (minimum PHP 8.1)
- ✅ Laravel 11.0+ (minimum PHP 8.2)

No configuration changes or code modifications are required when upgrading Laravel.

## Forward Compatibility

The package is designed with forward compatibility in mind:

- Follows Laravel best practices
- Avoids deprecated features
- Uses stable, long-term APIs
- Regular updates for new Laravel releases

## Reporting Issues

When reporting compatibility issues, please include:

```bash
# Environment information
php artisan --version
php -v
composer show | grep laravel
composer show eheuristic/laravel-log-monitor

# Laravel environment
php artisan env
```

### Issue Template

```markdown
**Laravel Version**: X.X
**PHP Version**: X.X.X
**Package Version**: X.X.X
**Issue Description**:
**Steps to Reproduce**:

1.
2.
3. **Expected Behavior**:
   **Actual Behavior**:
```

## Support

For version-specific help:

1. Check this compatibility guide
2. Review the [main README](README.md)
3. Check [GitHub Issues](https://github.com/shyambaldha/laravel-log-monitor/issues)
4. Open a new issue with version details

## License

This package is open-sourced software licensed under the [MIT license](LICENSE).
