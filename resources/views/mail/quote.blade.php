<x-mail::message>
# New quote request

**{{ $quote->name }}**@if($quote->company) — {{ $quote->company }}@endif
{{ $quote->email }}@if($quote->phone) · {{ $quote->phone }}@endif

<x-mail::panel>
**Estimate: ${{ number_format((int) $quote->price_low) }} – ${{ number_format((int) $quote->price_high) }}**
{{ $quote->hours_low }}–{{ $quote->hours_high }} hours · {{ $quote->recommended_plan }}
</x-mail::panel>

@php
    $rows = array_filter([
        'Services' => is_array($quote->services) ? implode(', ', $quote->services) : $quote->services,
        'Project type' => $quote->project_type,
        'Size' => $quote->size,
        'Scope' => $quote->scope,
        'Timeline' => $quote->timeline,
        'Engagement' => $quote->engagement,
    ]);
@endphp
| | |
|:--|:--|
@foreach($rows as $label => $value)
| **{{ $label }}** | {{ $value }} |
@endforeach

@if($quote->notes)
**Notes**

{{ $quote->notes }}
@endif

<x-mail::button :url="route('admin.quotes.show', $quote)">
Open in the admin panel
</x-mail::button>

Reply to this email to answer {{ \Illuminate\Support\Str::before($quote->name, ' ') }} directly.

Received {{ $quote->created_at->format('j M Y, H:i') }}
</x-mail::message>
