<?php

namespace Eheuristic\LaravelLogMonitor\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;

class LogReportMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var array
     */
    public $analysis;

    /**
     * @var array
     */
    public $summary;

    /**
     * @var Carbon
     */
    public $reportDate;

    /**
     * @var bool
     */
    public $attachFile;

    /**
     * Create a new message instance.
     *
     * @param array $analysis
     * @param array $summary
     * @param Carbon $reportDate
     * @param bool $attachFile
     */
    public function __construct(array $analysis, array $summary, Carbon $reportDate, $attachFile = true)
    {
        $this->analysis = $analysis;
        $this->summary = $summary;
        $this->reportDate = $reportDate;
        $this->attachFile = $attachFile;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $config = config('log-monitor');

        $subject = str_replace(
            ['{app_name}', '{date}', '{error_count}'],
            [config('app.name'), $this->reportDate->format('Y-m-d'), $this->analysis['error_count']],
            $config['subject_template']
        );

        $mail = $this->from($config['from_address'], $config['from_name'])
            ->subject($subject)
            ->view('log-monitor::emails.log-report')
            ->with([
                'analysis' => $this->analysis,
                'summary' => $this->summary,
                'reportDate' => $this->reportDate,
            ]);

        // Attach log file if it exists and is not too large
        if (
            $this->attachFile &&
            $this->analysis['exists'] &&
            $this->analysis['file_size_mb'] <= $config['max_file_size_mb']
        ) {
            $mail->attach($this->analysis['filepath'], [
                'as' => $this->analysis['filename'],
                'mime' => 'text/plain',
            ]);
        }

        return $mail;
    }
}
