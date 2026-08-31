@extends('admin.layout')
@section('title', 'SEO audit')
@section('crumb', 'Every page with search metadata, worst first')

@section('content')
  <div class="grid grid--stats">
    <div class="stat-card"><b>{{ $total }}</b><span>Items with SEO fields</span></div>
    <div class="stat-card"><b style="color:var(--ok)">{{ $clean }}</b><span>Fully optimised</span></div>
    <div class="stat-card"><b style="color:{{ $total - $clean ? 'var(--warn)' : 'var(--ok)' }}">{{ $total - $clean }}</b><span>Need attention</span></div>
  </div>

  <div class="card" style="margin-top:1rem">
    <div class="card__head"><h2>Findings</h2></div>
    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th>Type</th><th>Item</th><th>Title</th><th>Description</th><th>Issues</th><th></th>
          </tr>
        </thead>
        <tbody>
          @foreach($rows as $r)
            <tr>
              <td><span class="pill pill--off">{{ $r['type'] }}</span></td>
              <td><b style="font-size:.8rem">{{ Str::limit($r['name'], 40) }}</b></td>
              <td style="font-size:.74rem;color:var(--muted)">
                {{ $r['title'] ? Str::limit($r['title'], 46) : '—' }}
                <br><span style="font-size:.68rem">{{ $r['titleLen'] }} chars</span>
              </td>
              <td style="font-size:.74rem;color:var(--muted)">
                {{ $r['desc'] ? Str::limit($r['desc'], 56) : '—' }}
                <br><span style="font-size:.68rem">{{ $r['descLen'] }} chars</span>
              </td>
              <td>
                @forelse($r['issues'] as $issue)
                  <span class="pill pill--err" style="margin:1px">{{ $issue }}</span>
                @empty
                  <span class="pill pill--on">Good</span>
                @endforelse
              </td>
              <td class="actions"><a class="btn btn--sm" href="{{ $r['edit'] }}">Fix</a></td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>

  <div class="card">
    <div class="card__head"><h2>Site-wide SEO</h2></div>
    <p style="font-size:.8rem;color:var(--muted);margin-top:-.4rem">
      Defaults, robots directive, analytics snippets and the canonical base live in
      <a href="{{ route('admin.settings.index') }}?group=seo" style="color:var(--gold)">Settings → SEO</a>.
      The sitemap is generated from the database at
      <a href="{{ route('sitemap') }}" target="_blank" rel="noopener" style="color:var(--gold)">/sitemap.xml</a>.
    </p>
  </div>
@endsection
