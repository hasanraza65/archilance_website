<?php

namespace App\Support;

use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

use function Illuminate\Support\defer;

/**
 * Sends the studio's lead notifications.
 *
 * Delivery is deliberately best-effort: the enquiry or quote is already saved
 * by the time this runs, so a refused SMTP connection must never surface as an
 * error to the visitor or cost the studio the lead. Failures are logged and
 * the row is still in the admin panel either way.
 *
 * Deferred rather than queued: QUEUE_CONNECTION is `database` and nothing
 * guarantees a worker runs on the host, so a queued mail is a mail that may
 * never arrive. defer() runs the send after the response has been flushed, so
 * the visitor gets their confirmation immediately instead of waiting ~4s on
 * the SMTP round trip, and no worker is required.
 */
class LeadNotifier
{
    public static function send(Mailable $mail, string $context): bool
    {
        $recipients = self::recipients();

        if (! $recipients) {
            Log::warning("[{$context}] no ADMIN_EMAIL configured — notification skipped");

            return false;
        }

        defer(function () use ($recipients, $mail, $context) {
            try {
                Mail::to($recipients)->send($mail);
            } catch (\Throwable $e) {
                Log::error("[{$context}] notification failed: " . $e->getMessage());
            }
        });

        return true;
    }

    /** Blocking send, for the `mail:test` command where the result is the point. */
    public static function sendNow(Mailable $mail, string $context): bool
    {
        $recipients = self::recipients();

        if (! $recipients) {
            Log::warning("[{$context}] no ADMIN_EMAIL configured — notification skipped");

            return false;
        }

        try {
            Mail::to($recipients)->send($mail);

            return true;
        } catch (\Throwable $e) {
            Log::error("[{$context}] notification failed: " . $e->getMessage());

            return false;
        }
    }

    /** ADMIN_EMAIL may hold several comma-separated addresses. */
    public static function recipients(): array
    {
        $raw = (string) config('mail.admin_address');

        return array_values(array_filter(
            array_map('trim', explode(',', $raw)),
            fn ($a) => filter_var($a, FILTER_VALIDATE_EMAIL)
        ));
    }
}
