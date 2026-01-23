# Changelog

All notable changes to the Laravel Log Monitor package will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

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

## License

This package is open-sourced software licensed under the MIT license.

---

**Note:** Dates are in YYYY-MM-DD format following ISO 8601.
