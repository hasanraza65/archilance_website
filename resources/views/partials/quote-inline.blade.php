@php
    $qc = \App\Support\QuoteEngine::config();

    // The sprite already carries a mark per discipline; reusing them here means
    // the first step reads as a menu of services rather than a wall of boxes.
    $svcIcons = [
        'architecture-design'        => 'i-svc-design',
        'revit-drafting'             => 'i-svc-revit',
        'construction-permit-sets'   => 'i-svc-permit',
        '3d-modeling-rendering'      => 'i-svc-render',
        '3d-architectural-animation' => 'i-svc-animation',
        'point-cloud-to-bim'         => 'i-svc-scan',
        'interior-design'            => 'i-svc-interior',
        'landscape-architecture'     => 'i-svc-landscape',
    ];

    $stepNames = ['Services', 'Project', 'Scope', 'Timing', 'Working', 'Details'];
@endphp

<section class="qi" id="quoteInline" aria-labelledby="qiTitle"
         data-estimate="{{ route('quote.estimate') }}" data-submit="{{ route('quote.store') }}">
  <div class="qi__card">

    {{-- ── offer header: the pitch the old card carried, compressed ───────── --}}
    <header class="qi__head">
      <div class="qi__intro">
        <p class="qi__tag"><span class="qi__pulse" aria-hidden="true"></span> {{ $page->text('offer.badge') }}</p>
        <h2 class="qi__title" id="qiTitle">{!! $page->text('offer.title') !!}</h2>
        <p class="qi__sub">{{ $page->text('offer.sub') }}</p>
      </div>
      <div class="qi__offer">
        <p class="qi__price">
          <span>{{ $page->text('offer.price_label') }}</span><b>${{ $page->text('offer.price') }}</b><span>{{ $page->text('offer.price_unit') }}</span>
        </p>
        <p class="qi__note">{{ $page->text('offer.price_note') }}</p>
      </div>
    </header>

    {{-- ── progress ──────────────────────────────────────────────────────── --}}
    <div class="qi__meter">
      <p class="qi__count" id="qiCount" aria-live="polite">
        Step <b id="qiCountNow">1</b> of {{ count($stepNames) }} <em>·</em> <span id="qiCountName">{{ $stepNames[0] }}</span>
      </p>
      <div class="qi__track" aria-hidden="true"><span class="qi__bar" id="qiBar"></span></div>
    </div>

    <form class="qi__body" id="quoteInlineForm" novalidate>
      @csrf

      {{-- 1 ── services -------------------------------------------------- --}}
      <fieldset class="qi__step is-on" data-step="0" data-name="{{ $stepNames[0] }}">
        <legend class="qi__q">What do you need drawn?</legend>
        <p class="qi__hint">Pick everything that applies — combining disciplines lowers the rate.</p>

        <div class="qi__grid qi__grid--2">
          @foreach($qc['services'] as $slug => $svc)
            <label class="qi__opt">
              <input type="checkbox" name="services[]" value="{{ $slug }}">
              <span class="qi__opt-in">
                <svg class="qi__opt-ico" aria-hidden="true"><use href="#{{ $svcIcons[$slug] ?? 'i-svc-design' }}"></use></svg>
                <b>{{ $svc['label'] }}</b>
                <svg class="qi__tick" aria-hidden="true"><use href="#i-check"></use></svg>
              </span>
            </label>
          @endforeach
        </div>

        {{-- the three promises the offer card used to make --}}
        <ul class="qi__perks">
          <li><svg class="ico" aria-hidden="true"><use href="#i-check-circle"></use></svg><span>{!! $page->text('offer.feat1') !!}</span></li>
          <li><svg class="ico" aria-hidden="true"><use href="#i-check-circle"></use></svg><span>{!! $page->text('offer.feat2') !!}</span></li>
          <li><svg class="ico" aria-hidden="true"><use href="#i-check-circle"></use></svg><span>{!! $page->text('offer.feat3') !!}</span></li>
        </ul>
      </fieldset>

      {{-- 2 ── project type + size --------------------------------------- --}}
      <fieldset class="qi__step" data-step="1" data-name="{{ $stepNames[1] }}">
        <legend class="qi__q">Tell us about the project</legend>
        <p class="qi__hint">Two quick facts and the estimate starts moving.</p>

        <p class="qi__label">Type</p>
        <div class="qi__grid qi__grid--2">
          @foreach(['Residential', 'Commercial', 'Mixed-use', 'Interior fit-out'] as $i => $type)
            <label class="qi__opt">
              <input type="radio" name="project_type" value="{{ $type }}" @checked($i === 0)>
              <span class="qi__opt-in">
                <b>{{ $type }}</b>
                <svg class="qi__tick" aria-hidden="true"><use href="#i-check"></use></svg>
              </span>
            </label>
          @endforeach
        </div>

        <p class="qi__label">Approximate size</p>
        <div class="qi__grid qi__grid--2">
          @foreach($qc['size'] as $key => $band)
            <label class="qi__opt">
              <input type="radio" name="size" value="{{ $key }}" @checked($key === 'medium')>
              <span class="qi__opt-in">
                <b>{{ $band['label'] }}</b>
                <svg class="qi__tick" aria-hidden="true"><use href="#i-check"></use></svg>
              </span>
            </label>
          @endforeach
        </div>
      </fieldset>

      {{-- 3 ── scope ----------------------------------------------------- --}}
      @php
        $scopeNotes = [
          'concept' => 'Massing and plan options to test the idea.',
          'design'  => 'Resolved plans, elevations and sections.',
          'permit'  => 'A full set formatted for your authority.',
          'full'    => 'Everything from first sketch to submission.',
        ];
      @endphp
      <fieldset class="qi__step" data-step="2" data-name="{{ $stepNames[2] }}" data-auto>
        <legend class="qi__q">How far should we take it?</legend>
        <p class="qi__hint">Pick one — we move on automatically.</p>

        <div class="qi__grid">
          @foreach($qc['scope'] as $key => $scope)
            <label class="qi__opt qi__opt--wide">
              <input type="radio" name="scope" value="{{ $key }}" @checked($key === 'design')>
              <span class="qi__opt-in">
                <span class="qi__opt-text">
                  <b>{{ $scope['label'] }}</b>
                  <small>{{ $scopeNotes[$key] ?? '' }}</small>
                </span>
                <svg class="qi__tick" aria-hidden="true"><use href="#i-check"></use></svg>
              </span>
            </label>
          @endforeach
        </div>
      </fieldset>

      {{-- 4 ── timeline -------------------------------------------------- --}}
      @php
        $timeNotes = [
          'standard' => 'Normal queue.',
          'priority' => 'Jumped up the queue.',
          'rush'     => 'All hands, fastest turnaround.',
        ];
      @endphp
      <fieldset class="qi__step" data-step="3" data-name="{{ $stepNames[3] }}" data-auto>
        <legend class="qi__q">When do you need it?</legend>
        <p class="qi__hint">Pick one — we move on automatically.</p>

        <div class="qi__grid">
          @foreach($qc['timeline'] as $key => $t)
            <label class="qi__opt qi__opt--wide">
              <input type="radio" name="timeline" value="{{ $key }}" @checked($key === 'standard')>
              <span class="qi__opt-in">
                <span class="qi__opt-text">
                  <b>{{ $t['label'] }}</b>
                  <small>{{ $timeNotes[$key] ?? '' }}</small>
                </span>
                <svg class="qi__tick" aria-hidden="true"><use href="#i-check"></use></svg>
              </span>
            </label>
          @endforeach
        </div>
      </fieldset>

      {{-- 5 ── engagement ------------------------------------------------ --}}
      @php
        $engNotes = [
          'subscription' => 'Best value — $11.84/hr effective.',
          'hourly'       => 'No commitment — $28/hr.',
          'fixed'        => 'One agreed price for the package.',
        ];
      @endphp
      <fieldset class="qi__step" data-step="4" data-name="{{ $stepNames[4] }}" data-auto>
        <legend class="qi__q">How would you like to work with us?</legend>
        <p class="qi__hint">Pick one — we move on automatically.</p>

        <div class="qi__grid">
          @foreach($qc['engagement'] as $key => $e)
            <label class="qi__opt qi__opt--wide">
              <input type="radio" name="engagement" value="{{ $key }}" @checked($key === 'subscription')>
              <span class="qi__opt-in">
                <span class="qi__opt-text">
                  <b>{{ $e['label'] }}</b>
                  <small>{{ $engNotes[$key] ?? '' }}</small>
                </span>
                <svg class="qi__tick" aria-hidden="true"><use href="#i-check"></use></svg>
              </span>
            </label>
          @endforeach
        </div>
      </fieldset>

      {{-- 6 ── contact --------------------------------------------------- --}}
      <fieldset class="qi__step" data-step="5" data-name="{{ $stepNames[5] }}">
        <legend class="qi__q">Where should we send it?</legend>
        <p class="qi__hint">Your estimate appears on the next screen.</p>

        {{-- an even two-column grid, so the fields always pair off instead of
             leaving the fourth one stranded on a row of its own --}}
        <div class="qi__fields">
          <p class="qi__field">
            <label for="qiName">Name <span class="qi__req">*</span></label>
            <input class="qi__ctrl" type="text" id="qiName" name="name" autocomplete="name" required>
          </p>
          <p class="qi__field">
            <label for="qiEmail">Email <span class="qi__req">*</span></label>
            <input class="qi__ctrl" type="email" id="qiEmail" name="email" autocomplete="email" required>
          </p>
          <p class="qi__field">
            <label for="qiCompany">Company <span class="qi__opt-tag">optional</span></label>
            <input class="qi__ctrl" type="text" id="qiCompany" name="company" autocomplete="organization">
          </p>
          <p class="qi__field">
            <label for="qiPhone">Phone <span class="qi__opt-tag">optional</span></label>
            <input class="qi__ctrl" type="tel" id="qiPhone" name="phone" autocomplete="tel">
          </p>
        </div>

        <p class="qi__field">
          <label for="qiNotes">Anything else? <span class="qi__opt-tag">optional</span></label>
          <textarea class="qi__ctrl" id="qiNotes" name="notes" rows="2"
                    placeholder="Deadlines, software, what you have drawn already…"></textarea>
        </p>

        <span class="hp" aria-hidden="true">
          <label for="qiUrl">Company URL</label>
          <input type="text" id="qiUrl" name="company_url" tabindex="-1" autocomplete="off">
        </span>
      </fieldset>

      {{-- result --------------------------------------------------------- --}}
      <div class="qi__result" data-step="6">
        <span class="qi__result-mark" aria-hidden="true">
          <svg class="ico"><use href="#i-check-circle"></use></svg>
        </span>
        <p class="qi__result-eyebrow">Your estimate</p>
        <p class="qi__result-price"><span id="qiPriceLow">—</span> <em>to</em> <span id="qiPriceHigh">—</span></p>
        <p class="qi__result-note" id="qiHours"></p>
        <p class="qi__result-plan" id="qiPlan"></p>
        <ul class="qi__summary" id="qiSummary"></ul>
        <p class="qi__disclaimer">
          An indication based on similar projects, not a formal quote. An architect confirms
          the scope, timeline and final price within 24 hours.
        </p>
      </div>

      {{-- outside the result panel so validation errors show on the step that
           caused them, not only once the estimate has been produced --}}
      <p class="qi__status" id="qiStatus" role="status" aria-live="polite"></p>
    </form>

    {{-- ── nav ───────────────────────────────────────────────────────────── --}}
    <footer class="qi__foot">
      {{-- deliberately no running total: a price that climbs while you answer
           reads as a meter running, which costs more leads than it wins --}}
      <p class="qi__assure" id="qiAssure">
        <svg class="ico" aria-hidden="true"><use href="#i-shield"></use></svg>
        <span>No card, no obligation</span>
      </p>
      <div class="qi__nav">
        <button class="btn-a btn-a--ghost-dark btn-a--sm" type="button" id="qiBack" hidden>
          <svg class="ico" aria-hidden="true"><use href="#i-arrow-left"></use></svg> Back
        </button>
        <button class="btn-a btn-a--ink btn-a--sm" type="button" id="qiNext" data-magnetic>
          Continue <svg class="ico ico--arrow" aria-hidden="true"><use href="#i-arrow-right"></use></svg>
        </button>
        <button class="btn-a btn-a--ink btn-a--sm" type="button" id="qiSubmit" hidden data-magnetic>
          {{ $page->text('offer.cta') }} <svg class="ico ico--arrow" aria-hidden="true"><use href="#i-arrow-right"></use></svg>
        </button>
        <a class="btn-a btn-a--ink btn-a--sm" href="{{ route('contact') }}" id="qiDone" hidden data-magnetic>
          Talk to an architect <svg class="ico ico--arrow" aria-hidden="true"><use href="#i-arrow-right"></use></svg>
        </a>
      </div>
    </footer>

    <div class="qi__tail">
      <a class="qi__alt" href="#pricing">{{ $page->text('offer.cta_alt') }}</a>
      <a class="qi__call" href="#contact">
        <span class="qi__call-ico"><svg class="ico" width="18" height="18" aria-hidden="true"><use href="#i-calendar"></use></svg></span>
        <span class="qi__call-text"><b>{{ $page->text('offer.call_title') }}</b><small>{{ $page->text('offer.call_sub') }}</small></span>
        <svg class="ico ico--arrow" aria-hidden="true"><use href="#i-arrow-right"></use></svg>
      </a>
    </div>
  </div>
</section>
