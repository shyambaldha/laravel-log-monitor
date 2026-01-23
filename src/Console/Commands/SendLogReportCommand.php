<?php

namespace Eheuristic\LaravelLogMonitor\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use Eheuristic\LaravelLogMonitor\Mail\LogReportMail;
use Eheuristic\LaravelLogMonitor\Services\LogAnalyzer;

class SendLogReportCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'log:send-report 
                            {--date= : Date to generate report for (Y-m-d format, default: yesterday)}
                            {--force : Send report even if no errors found}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send daily log report via email';

    /**
     * @var LogAnalyzer
     */
    protected $analyzer;

    /**
     * Execute the console command.
     *
     * @param LogAnalyzer $analyzer
     * @return int
     */
    public function handle(LogAnalyzer $analyzer)
    {
        $this->analyzer = $analyzer;

        // Check if monitoring is enabled
        if (!config('log-monitor.enabled')) {
            $this->warn('Log monitoring is disabled. Enable it in config/log-monitor.php');
            return 1;
        }

        // Get recipients
        $recipients = $this->getRecipients();
        if (empty($recipients)) {
            $this->error('No recipients configured. Set LOG_MONITOR_RECIPIENTS in .env');
            return 1;
        }

        // Determine the date for the report
        $reportDate = $this->option('date')
            ? Carbon::parse($this->option('date'))
            : Carbon::yesterday();

        $this->info("Generating log report for: {$reportDate->format('Y-m-d')}");

        // Get and analyze log file
        $logFile = $this->analyzer->getLogFile($reportDate);

        if (!$logFile) {
            $this->warn("No log file found for {$reportDate->format('Y-m-d')}");
            return 0;
        }

        $this->info("Analyzing log file: {$logFile}");
        $analysis = $this->analyzer->analyzeLogFile($logFile);

        // Check if we should send the report
        $sendOnlyOnErrors = config('log-monitor.send_only_on_errors');
        $force = $this->option('force');

        if (!$force && $sendOnlyOnErrors && $analysis['error_count'] === 0) {
            $this->info('No errors found. Email not sent (use --force to send anyway)');
            return 0;
        }

        // Get summary
        $summary = $this->analyzer->getSummary($analysis);

        $this->info("Found {$analysis['error_count']} error(s)");
        $this->displaySummary($summary);

        // Send email to all recipients
        $this->info('Sending email to: ' . implode(', ', $recipients));

        try {
            // Determine if we should attach the file
            $maxSize = config('log-monitor.max_file_size_mb');
            $attachFile = $analysis['file_size_mb'] <= $maxSize;

            if (!$attachFile) {
                $this->warn("Log file too large ({$analysis['file_size_mb']}MB). Not attaching to email.");
            }

            Mail::to($recipients)->send(
                new LogReportMail($analysis, $summary, $reportDate, $attachFile)
            );

            $this->info('✓ Email sent successfully!');
            return 0;
        } catch (\Exception $e) {
            $this->error('Failed to send email: ' . $e->getMessage());
            return 1;
        }
    }

    /**
     * Get recipient email addresses.
     *
     * @return array
     */
    protected function getRecipients()
    {
        $recipients = config('log-monitor.recipients');

        if (empty($recipients)) {
            return [];
        }

        // Split by comma and trim whitespace
        $emails = array_map('trim', explode(',', $recipients));

        // Filter out empty values
        return array_filter($emails);
    }

    /**
     * Display summary in console.
     *
     * @param array $summary
     * @return void
     */
    protected function displaySummary($summary)
    {
        if (empty($summary['by_level'])) {
            return;
        }

        $this->newLine();
        $this->line('Error breakdown by level:');

        foreach ($summary['by_level'] as $level => $count) {
            $this->line("  - {$level}: {$count}");
        }

        $this->newLine();
    }
}
