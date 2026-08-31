<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Mail\QuoteReceived;
use App\Models\Quote;
use App\Support\LeadNotifier;
use App\Support\QuoteEngine;
use Illuminate\Http\Request;

class QuoteController extends Controller
{
    /** Live estimate while the visitor moves through the wizard. */
    public function estimate(Request $request)
    {
        return response()->json(QuoteEngine::estimate($request->all()));
    }

    public function store(Request $request)
    {
        // Honeypot — accept quietly so a bot learns nothing.
        if ($request->filled('company_url')) {
            return response()->json(['ok' => true]);
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:180'],
            'phone' => ['nullable', 'string', 'max:60'],
            'company' => ['nullable', 'string', 'max:150'],
            'services' => ['nullable', 'array'],
            'services.*' => ['string', 'max:80'],
            'project_type' => ['nullable', 'string', 'max:80'],
            'size' => ['nullable', 'string', 'max:40'],
            'scope' => ['nullable', 'string', 'max:40'],
            'timeline' => ['nullable', 'string', 'max:40'],
            'engagement' => ['nullable', 'string', 'max:40'],
            'notes' => ['nullable', 'string', 'max:3000'],
        ]);

        // Recalculate rather than trusting the numbers the browser posted.
        $estimate = QuoteEngine::estimate($data);

        $quote = Quote::create(array_merge($data, $estimate, ['ip' => $request->ip()]));

        LeadNotifier::send(new QuoteReceived($quote), 'quote');

        return response()->json([
            'ok' => true,
            'id' => $quote->id,
            'estimate' => $estimate,
        ]);
    }
}
