<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Mail\EnquiryReceived;
use App\Models\Enquiry;
use App\Support\LeadNotifier;
use Illuminate\Http\Request;

class EnquiryController extends Controller
{
    public function store(Request $request)
    {
        // Honeypot: bots fill it, people never see it. Accept and drop silently
        // so the sender gets no signal that the submission was rejected.
        if ($request->filled('company_url')) {
            return $request->expectsJson()
                ? response()->json(['ok' => true])
                : back()->with('status', 'Thank you — your enquiry has been sent.');
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:180'],
            'phone' => ['nullable', 'string', 'max:60'],
            'company' => ['nullable', 'string', 'max:150'],
            'service' => ['nullable', 'string', 'max:150'],
            'budget' => ['nullable', 'string', 'max:80'],
            'timeline' => ['nullable', 'string', 'max:80'],
            'source' => ['nullable', 'string', 'max:80'],
            'query' => ['required', 'string', 'max:5000'],
            'nda' => ['nullable'],
        ]);

        $enquiry = Enquiry::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'company' => $data['company'] ?? null,
            'service' => $data['service'] ?? null,
            'budget' => $data['budget'] ?? null,
            'timeline' => $data['timeline'] ?? null,
            'source' => $data['source'] ?? null,
            'nda' => $request->boolean('nda'),
            'message' => $data['query'],
            'ip' => $request->ip(),
            'page' => $request->headers->get('referer'),
        ]);

        // The row is saved; telling the studio is best-effort and must never
        // turn a captured lead into an error page.
        LeadNotifier::send(new EnquiryReceived($enquiry), 'enquiry');

        return $request->expectsJson()
            ? response()->json(['ok' => true, 'id' => $enquiry->id])
            : back()->with('status', 'Thank you — your enquiry is with the design team. We reply within 24 hours.');
    }
}
