@extends('admin.layout')
@section('title', 'Quote for ' . $item->name)
@section('crumb', $item->created_at->format('j F Y, g:ia'))

@section('actions')
  <a class="btn" href="{{ route('admin.quotes.index') }}">Back</a>
  <a class="btn btn--primary" href="mailto:{{ $item->email }}?subject=Your Archilance quote">Reply by email</a>
@endsection

@section('content')
  @php $b = $item->breakdown ?? []; @endphp

  <div class="grid grid--form">
    <div>
      <div class="card">
        <div class="card__head"><h2>What they were shown</h2></div>
        <p class="qprice">
          ${{ number_format($item->price_low) }} – ${{ number_format($item->price_high) }}
          <small>{{ $item->hours_low }}–{{ $item->hours_high }} hours · {{ $item->recommended_plan }}</small>
        </p>
        <ul class="qmeta">
          @foreach((array) ($item->services ?? []) as $s)
            <li>{{ $b['services'][$loop->index] ?? $s }}</li>
          @endforeach
        </ul>
        <p style="font-size:.76rem;color:var(--muted);margin:0">
          Recalculated on the server when they submitted, so this is exactly the figure on screen.
        </p>
      </div>

      @if($item->notes)
        <div class="card">
          <div class="card__head"><h2>Their notes</h2></div>
          <p style="white-space:pre-wrap;font-size:.86rem;line-height:1.7;margin:0">{{ $item->notes }}</p>
        </div>
      @endif

      <div class="card">
        <div class="card__head"><h2>How the number was reached</h2></div>
        @foreach([
          'Base hours' => $b['base_hours'] ?? null,
          'Size factor' => $b['size_factor'] ?? null,
          'Scope factor' => $b['scope_factor'] ?? null,
          'Timeline factor' => $b['timeline_factor'] ?? null,
          'Multi-service saving' => $b['multi_service_discount'] ?? null,
          'Hourly rate applied' => isset($b['rate']) ? '$' . $b['rate'] : null,
        ] as $label => $value)
          @if($value !== null)
            <div style="display:flex;gap:.6rem;padding:.4rem 0;border-bottom:1px solid var(--line)">
              <span style="font-size:.72rem;color:var(--muted);min-width:150px">{{ $label }}</span>
              <span style="font-size:.78rem">{{ $value }}</span>
            </div>
          @endif
        @endforeach
      </div>
    </div>

    <div class="card">
      <div class="card__head"><h2>Details</h2></div>
      @foreach([
        'Name' => $item->name, 'Email' => $item->email, 'Phone' => $item->phone,
        'Company' => $item->company, 'Project type' => $item->project_type,
        'Size' => $item->size, 'Scope' => $item->scope, 'Timeline' => $item->timeline,
        'Engagement' => $item->engagement, 'IP' => $item->ip,
      ] as $label => $value)
        @if($value)
          <div style="display:flex;gap:.6rem;padding:.4rem 0;border-bottom:1px solid var(--line)">
            <span style="font-size:.72rem;color:var(--muted);min-width:110px">{{ $label }}</span>
            <span style="font-size:.78rem;word-break:break-word">{{ ucfirst($value) }}</span>
          </div>
        @endif
      @endforeach

      <form method="POST" action="{{ route('admin.quotes.destroy', $item) }}"
            data-confirm="Delete this quote permanently?" style="margin-top:1rem">
        @csrf @method('DELETE')
        <button class="btn btn--danger" type="submit" style="width:100%;justify-content:center">Delete quote</button>
      </form>
    </div>
  </div>
@endsection
