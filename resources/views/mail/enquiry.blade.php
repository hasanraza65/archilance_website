<x-mail::message>
# New enquiry

**{{ $enquiry->name }}**@if($enquiry->company) — {{ $enquiry->company }}@endif
{{ $enquiry->email }}@if($enquiry->phone) · {{ $enquiry->phone }}@endif

<x-mail::panel>
{{ $enquiry->message }}
</x-mail::panel>

@php
    $rows = array_filter([
        'Service' => $enquiry->service,
        'Budget' => $enquiry->budget,
        'Timeline' => $enquiry->timeline,
        'Heard via' => $enquiry->source,
        'NDA requested' => $enquiry->nda ? 'Yes' : null,
        'Submitted from' => $enquiry->page,
    ]);
@endphp
@if($rows)
| | |
|:--|:--|
@foreach($rows as $label => $value)
| **{{ $label }}** | {{ $value }} |
@endforeach
@endif

<x-mail::button :url="route('admin.enquiries.show', $enquiry)">
Open in the admin panel
</x-mail::button>

Reply to this email to answer {{ \Illuminate\Support\Str::before($enquiry->name, ' ') }} directly.

Received {{ $enquiry->created_at->format('j M Y, H:i') }}
</x-mail::message>
