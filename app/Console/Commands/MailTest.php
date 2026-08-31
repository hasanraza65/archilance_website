<?php

namespace App\Console\Commands;

use App\Mail\EnquiryReceived;
use App\Models\Enquiry;
use App\Support\LeadNotifier;
use Illuminate\Console\Command;

/**
 * Verifies the SMTP credentials and ADMIN_EMAIL wiring on whatever host it is
 * run from — the fastest way to prove notifications work after a deploy.
 */
class MailTest extends Command
{
    protected $signature = 'mail:test {--to= : Override the recipient for this run}';

    protected $description = 'Send a test notification to ADMIN_EMAIL to check the mail setup';

    public function handle(): int
    {
        $to = $this->option('to') ? [$this->option('to')] : LeadNotifier::recipients();

        $this->line('  mailer     : ' . config('mail.default') . ' → '
            . config('mail.mailers.smtp.host') . ':' . config('mail.mailers.smtp.port')
            . ' (timeout ' . config('mail.mailers.smtp.timeout') . 's)');
        $this->line('  from       : ' . config('mail.from.address'));
        $this->line('  recipients : ' . ($to ? implode(', ', $to) : 'NONE'));

        if (! $to) {
            $this->error('ADMIN_EMAIL is not set — nothing to send to.');

            return self::FAILURE;
        }

        // Use a real row when there is one so the template renders as it will
        // in production; otherwise build an unsaved stand-in.
        $enquiry = Enquiry::latest()->first() ?: new Enquiry([
            'name' => 'Mail Test',
            'email' => $to[0],
            'message' => 'This is a test notification from php artisan mail:test.',
        ]);
        $enquiry->created_at ??= now();
        $enquiry->id ??= 0;

        $this->newLine();
        $start = microtime(true);

        // sendNow, not send: here the result is the whole point, so it must not
        // be deferred past the end of the command.
        $ok = $this->option('to')
            ? $this->sendTo($to, $enquiry)
            : LeadNotifier::sendNow(new EnquiryReceived($enquiry), 'mail:test');

        $secs = round(microtime(true) - $start, 1);

        if ($ok) {
            $this->info("Sent in {$secs}s. Check the inbox (and the spam folder).");

            return self::SUCCESS;
        }

        $this->error("Failed after {$secs}s — see storage/logs/laravel.log for the reason.");

        return self::FAILURE;
    }

    protected function sendTo(array $to, Enquiry $enquiry): bool
    {
        try {
            \Illuminate\Support\Facades\Mail::to($to)->send(new EnquiryReceived($enquiry));

            return true;
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('[mail:test] ' . $e->getMessage());
            $this->line('  <fg=red>' . $e->getMessage() . '</>');

            return false;
        }
    }
}
