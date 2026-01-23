# Changelog

All notable changes to the Laravel Log Monitor package will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [1.0.0] - 2025-01-23

### Added

- Initial release of Laravel Log Monitor
- Support for Laravel 8.x, 9.x, 10.x, and 11.x
- Support for PHP 7.3, 7.4, 8.0, 8.1, 8.2, and 8.3
- Automatic log file analysis and error extraction
- Email notifications with beautiful HTML template
- Multiple recipient support via comma-separated email list
- Configurable log levels monitoring (emergency, alert, critical, error)
- Conditional email sending (only when errors found)
- Log file attachment support with size limits
- Stack trace extraction and display
- Command-line interface with options:
    - `--date` for specific date reports
    - `--force` to send regardless of errors
- Comprehensive configuration file with all options
- Auto-discovery support for Laravel 5.5+
- Publishable configuration file
- Publishable email template views
- Artisan command: `log:send-report`
- Service provider for package registration
- LogAnalyzer service for log parsing
- LogReportMail mailable class
- Detailed documentation (README.md, INSTALLATION.md, COMPATIBILITY.md)

### Features

- **Smart Error Detection**: Automatically detects and parses error log entries
- **Beautiful Emails**: Responsive HTML email template with color-coded error levels
- **Stack Trace Support**: Full stack trace extraction and formatting
- **File Attachments**: Automatically attaches log files under 10MB
- **Error Summary**: Statistical breakdown by error level
- **Configurable Scheduling**: Flexible Laravel task scheduling integration
- **Multi-Environment**: Different configurations for development, staging, production
- **Timezone Support**: Configurable timezone for scheduled tasks
- **Error Truncation**: Shows top 20 errors in email, notes if more exist
- **File Size Awareness**: Skips attachment if log file is too large

### Configuration Options

- Enable/disable monitoring
- Multiple email recipients
- Custom log file path
- Selectable log levels to monitor
- Conditional sending based on error presence
- Customizable email subject template
- Maximum file size for attachments
- Maximum errors to display in email
- Custom from address and name
- Configurable date format for log files

### Documentation

- Comprehensive README with quick start guide
- Detailed installation instructions
- Compatibility guide for Laravel 8-11
- Configuration reference
- Troubleshooting section
- Usage examples
- Code examples for all features

## [0.9.0] - 2025-01-20 (Beta)

### Added

- Beta release for testing
- Core log analysis functionality
- Basic email notification system
- Initial configuration options

### Fixed

- Type hints compatibility with PHP 7.3
- Property type declarations for PHP 7.3
- Return type compatibility across PHP versions

## Version History

### Version Compatibility

| Package Version | Laravel | PHP     | Status     |
| --------------- | ------- | ------- | ---------- |
| 1.0.x           | 8-11    | 7.3-8.3 | Active     |
| 0.9.x           | 9-11    | 8.0+    | Beta (EOL) |

## Upgrade Guide

### Upgrading to 1.0.0 from 0.9.0

1. Update your composer.json:

```bash
composer update eheuristic/laravel-log-monitor
```

2. Re-publish configuration (optional):

```bash
php artisan vendor:publish --tag=log-monitor-config --force
```

3. Update environment variables (if needed):

- Check for new configuration options in `config/log-monitor.php`
- Update `.env` file with any new variables

4. Clear caches:

```bash
php artisan config:clear
php artisan cache:clear
```

5. Test the package:

```bash
php artisan log:send-report --force
```

## Future Roadmap

### Planned for 1.1.0

- [ ] Database storage for error history
- [ ] Web dashboard for viewing error trends
- [ ] Slack integration for notifications
- [ ] Microsoft Teams integration
- [ ] Custom error grouping and filtering
- [ ] Error threshold alerts
- [ ] Weekly/monthly summary reports
- [ ] API endpoints for external monitoring

### Planned for 1.2.0

- [ ] Multi-language support
- [ ] Custom email templates via config
- [ ] Error severity scoring
- [ ] Auto-resolution tracking
- [ ] Integration with error tracking services (Sentry, Bugsnag)
- [ ] Performance metrics in reports
- [ ] Custom notification channels

### Planned for 2.0.0

- [ ] Laravel 12 support (when released)
- [ ] Real-time error monitoring
- [ ] Advanced analytics and reporting
- [ ] Machine learning error prediction
- [ ] Automated error resolution suggestions

## Contributing

See [CONTRIBUTING.md](CONTRIBUTING.md) for details on submitting pull requests.

## Security

See [SECURITY.md](SECURITY.md) for security vulnerability reporting.

## License

This package is open-sourced software licensed under the MIT license.

---

**Note:** Dates are in YYYY-MM-DD format following ISO 8601.
