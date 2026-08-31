@extends('admin.layout')
@section('title', 'Enquiry from ' . $item->name)
@section('crumb', $item->created_at->format('j F Y, g:ia'))

@section('actions')
  <a class="btn" href="{{ route('admin.enquiries.index') }}">Back</a>
  <a class="btn btn--primary" href="mailto:{{ $item->email }}?subject=Re: your enquiry">Reply by email</a>
@endsection

@section('content')
  <div class="grid grid--form">
    <div class="card">
      <div class="card__head"><h2>Message</h2></div>
      <p style="white-space:pre-wrap;font-size:.86rem;line-height:1.7">{{ $item->message }}</p>
    </div>

    <div class="card">
      <div class="card__head"><h2>Details</h2></div>
      @foreach([
        'Name' => $item->name, 'Email' => $item->email, 'Phone' => $item->phone,
        'Company' => $item->company, 'Service' => $item->service, 'Budget' => $item->budget,
        'Timeline' => $item->timeline, 'Heard via' => $item->source,
        'NDA requested' => $item->nda ? 'Yes' : 'No',
        'Submitted from' => $item->page, 'IP' => $item->ip,
      ] as $label => $value)
        @if($value)
          <div style="display:flex;gap:.6rem;padding:.4rem 0;border-bottom:1px solid var(--line)">
            <span style="font-size:.72rem;color:var(--muted);min-width:110px">{{ $label }}</span>
            <span style="font-size:.78rem;word-break:break-word">{{ $value }}</span>
          </div>
        @endif
      @endforeach

      <form method="POST" action="{{ route('admin.enquiries.destroy', $item) }}"
            data-confirm="Delete this enquiry permanently?" style="margin-top:1rem">
        @csrf @method('DELETE')
        <button class="btn btn--danger" type="submit" style="width:100%;justify-content:center">Delete enquiry</button>
      </form>
    </div>
  </div>
@endsection
