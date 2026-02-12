<?php

namespace Eheuristic\LaravelLogMonitor\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\File;

class LogAnalyzer
{
    /**
     * @var array
     */
    protected $config;

    /**
     * @var string
     */
    protected $logPath;

    /**
     * @var array
     */
    protected $monitorLevels;

    /**
     * LogAnalyzer constructor.
     */
    public function __construct()
    {
        $this->config = config('log-monitor');
        $this->logPath = $this->config['log_path'];
        $this->monitorLevels = $this->config['monitor_levels'];
    }

    /**
     * Normalize path for display (convert backslashes to slashes).
     *
     * @param string $path
     * @return string
     */
    protected function normalizePath($path)
    {
        return str_replace('\\', '/', $path);
    }

    /**
     * Get log file for a specific date.
     *
     * @param Carbon $date
     * @return string|null
     */
    public function getLogFile(Carbon $date)
    {
        $dateFormat = $this->config['date_format'];
        $filename = 'laravel-' . $date->format($dateFormat) . '.log';
        $filepath = $this->logPath . '/' . $filename;

        if (File::exists($filepath)) {
            return $filepath;
        }

        return null;
    }

    /**
     * Analyze log file and extract errors.
     *
     * @param string $filepath
     * @return array
     */
    public function analyzeLogFile($filepath)
    {
        if (!File::exists($filepath)) {
            return [
                'exists' => false,
                'errors' => [],
                'error_count' => 0,
                'file_size' => 0,
            ];
        }

        $content = File::get($filepath);
        $fileSize = File::size($filepath);
        $errors = $this->extractErrors($content);

        return [
            'exists' => true,
            'filepath' => $filepath,
            'filename' => basename($filepath),
            'display_path'  => $this->normalizePath($filepath),
            'relative_path' => $this->normalizePath(
                ltrim(str_replace(base_path(), '', $filepath), DIRECTORY_SEPARATOR)
            ),
            'errors' => $errors,
            'error_count' => count($errors),
            'file_size' => $fileSize,
            'file_size_mb' => round($fileSize / 1024 / 1024, 2),
            'content' => $content,
        ];
    }

    /**
     * Extract errors from log content.
     *
     * @param string $content
     * @return array
     */
    protected function extractErrors($content)
    {
        $errors = [];
        $lines = explode("\n", $content);
        $currentError = null;
        $stackTrace = [];

        foreach ($lines as $index => $line) {
            // Check if line starts with a log level pattern
            if ($this->isLogEntryStart($line)) {
                // Save previous error if exists
                if ($currentError !== null) {
                    $currentError['stack_trace'] = implode("\n", $stackTrace);
                    $errors[] = $currentError;
                    $stackTrace = [];
                }

                // Check if this is an error level we monitor
                $level = $this->extractLogLevel($line);
                if (in_array(strtolower($level), $this->monitorLevels)) {
                    $currentError = [
                        'level' => $level,
                        'timestamp' => $this->extractTimestamp($line),
                        'message' => $this->extractMessage($line),
                        'line_number' => $index + 1,
                        'full_line' => $line,
                        'stack_trace' => '',
                    ];
                } else {
                    $currentError = null;
                }
            } elseif ($currentError !== null) {
                // This is part of stack trace or continuation
                $stackTrace[] = $line;
            }
        }

        // Don't forget the last error
        if ($currentError !== null) {
            $currentError['stack_trace'] = implode("\n", $stackTrace);
            $errors[] = $currentError;
        }

        return $errors;
    }

    /**
     * Check if line is the start of a log entry.
     *
     * @param string $line
     * @return bool
     */
    protected function isLogEntryStart($line)
    {
        // Laravel log format: [YYYY-MM-DD HH:MM:SS] environment.LEVEL: message
        return preg_match('/^\[\d{4}-\d{2}-\d{2}\s\d{2}:\d{2}:\d{2}\]/', $line) === 1;
    }

    /**
     * Extract log level from line.
     *
     * @param string $line
     * @return string
     */
    protected function extractLogLevel($line)
    {
        if (preg_match('/\]\s+\w+\.(\w+):/', $line, $matches)) {
            return $matches[1];
        }
        return 'unknown';
    }

    /**
     * Extract timestamp from line.
     *
     * @param string $line
     * @return string|null
     */
    protected function extractTimestamp($line)
    {
        if (preg_match('/\[(\d{4}-\d{2}-\d{2}\s\d{2}:\d{2}:\d{2})\]/', $line, $matches)) {
            return $matches[1];
        }
        return null;
    }

    /**
     * Extract message from line.
     *
     * @param string $line
     * @return string
     */
    protected function extractMessage($line)
    {
        if (preg_match('/\]\s+\w+\.\w+:\s+(.+)$/', $line, $matches)) {
            return trim($matches[1]);
        }
        return $line;
    }

    /**
     * Get summary statistics.
     *
     * @param array $analysis
     * @return array
     */
    public function getSummary($analysis)
    {
        $summary = [
            'total_errors' => $analysis['error_count'],
            'by_level' => [],
        ];

        foreach ($analysis['errors'] as $error) {
            $level = strtolower($error['level']);
            if (!isset($summary['by_level'][$level])) {
                $summary['by_level'][$level] = 0;
            }
            $summary['by_level'][$level]++;
        }

        return $summary;
    }
}
