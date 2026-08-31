@extends('layouts.front')

@section('content')


  <section class="page-hero page-hero--flat">
    <div class="page-hero__veil" aria-hidden="true"></div>
    <div class="shell">
      <nav aria-label="Breadcrumb">
        <ol class="crumbs">
          <li><a href="{{ route('home') }}">Home</a></li>
          <li><span aria-current="page">Contact</span></li>
        </ol>
      </nav>

      <p class="eyebrow" data-reveal>{{ $page?->eyebrow }}</p>
      <h1 class="page-hero__title" data-reveal>{{ $page?->h1_lead }} <em>{{ $page?->h1_gold }}</em></h1>
      <p class="page-hero__lede" data-reveal>
        {{ $page?->lede }}
      </p>
    </div>
  </section>

  <section class="section section--after-hero section--ink grid-veil" aria-labelledby="formHeading">
    <div class="shell">
      <div class="contact-grid">

        <div class="contact-aside" data-reveal="left">
          <h2 class="h-xl" id="formHeading">{!! $page->text('form.heading') !!}</h2>
          <p class="lede">{{ $page->text('form.lede') }}</p>

          <ul class="channels">
            <li>
              <a class="channel" href="mailto:{{ $s->get('email') }}">
                <span class="channel__ico"><svg class="ico" aria-hidden="true"><use href="#i-mail"></use></svg></span>
                <span class="channel__txt"><b>{{ $s->get('email') }}</b><small>Replies within 24 hours, usually much sooner</small></span>
                <svg class="ico channel__go" aria-hidden="true"><use href="#i-arrow-right"></use></svg>
              </a>
            </li>
            <li>
              <a class="channel" href="tel:{{ $s->get('phone_link') }}">
                <span class="channel__ico"><svg class="ico" aria-hidden="true"><use href="#i-phone"></use></svg></span>
                <span class="channel__txt"><b>{{ $s->get('phone') }}</b><small>US line · we cover US, UK, Gulf and APAC hours</small></span>
                <svg class="ico channel__go" aria-hidden="true"><use href="#i-arrow-right"></use></svg>
              </a>
            </li>
            <li>
              <a class="channel" href="https://wa.me/{{ $s->get('whatsapp') }}" target="_blank" rel="noopener noreferrer">
                <span class="channel__ico"><svg class="ico" aria-hidden="true"><use href="#i-whatsapp"></use></svg></span>
                <span class="channel__txt"><b>WhatsApp</b><small>Fastest for a quick question or a file</small></span>
                <svg class="ico channel__go" aria-hidden="true"><use href="#i-arrow-right"></use></svg>
              </a>
            </li>
          </ul>

          <div class="promise">
            <h3>{{ $page->text('promise.title') }}</h3>
            <ol class="promise__list">
              @foreach($page->text('promise.steps') ?? [] as $step)
                <li><b>{!! $step['title'] ?? '' !!}</b> {!! $step['text'] ?? '' !!}</li>
              @endforeach
            </ol>
          </div>

          <div class="assurance">
            <span><svg class="ico" aria-hidden="true"><use href="#i-shield"></use></svg>NDA on request</span>
            <span><svg class="ico" aria-hidden="true"><use href="#i-check-circle"></use></svg>ISO 9001 certified</span>
            <span><svg class="ico" aria-hidden="true"><use href="#i-star"></use></svg>5.0 on Upwork</span>
          </div>
        </div>

        <div class="contact-form-wrap" data-reveal="right">
          <form id="leadForm" method="POST" action="{{ route('enquiry.store') }}" novalidate data-endpoint="{{ route('enquiry.store') }}">
            @csrf
            <p class="form-intro">{!! $page->text('form.intro') !!}</p>

            <div class="field-row field-row--2">
              <div class="field">
                <label for="cName">Name <span class="req">*</span></label>
                <input class="ctrl" type="text" id="cName" name="name" autocomplete="name" placeholder="Jane Doe" required>
              </div>
              <div class="field">
                <label for="cEmail">Email <span class="req">*</span></label>
                <input class="ctrl" type="email" id="cEmail" name="email" autocomplete="email" placeholder="jane@studio.com" required>
              </div>
            </div>

            <div class="field-row field-row--2">
              <div class="field">
                <label for="cPhone">Phone <span style="opacity:.6">(optional)</span></label>
                <input class="ctrl" type="tel" id="cPhone" name="phone" autocomplete="tel" placeholder="+1 555 000 0000">
              </div>
              <div class="field">
                <label for="cCompany">Company <span style="opacity:.6">(optional)</span></label>
                <input class="ctrl" type="text" id="cCompany" name="company" autocomplete="organization" placeholder="Studio or firm">
              </div>
            </div>

            <div class="field">
              <label for="cService">What do you need? <span class="req">*</span></label>
              <select class="ctrl" id="cService" name="service" required>
                <option value="">Select a service</option>
                @foreach($services as $svc)
                  <option value="{{ $svc->name }}">{{ $svc->name }}</option>
                @endforeach
                <option>Not sure yet — help me choose</option>
              </select>
            </div>

            <div class="field-row field-row--2">
              <div class="field">
                <label for="cBudget">Budget</label>
                <select class="ctrl" id="cBudget" name="budget">
                  <option value="">Select a range</option>
                  <option>Under $1,000</option>
                  <option>$1,000 – $2,500</option>
                  <option>$2,500 – $5,000</option>
                  <option>$5,000 – $10,000</option>
                  <option>$10,000+</option>
                  <option>Monthly subscription</option>
                </select>
              </div>
              <div class="field">
                <label for="cWhen">When do you need it?</label>
                <select class="ctrl" id="cWhen" name="timeline">
                  <option value="">Select a timeline</option>
                  <option>Urgent — this week</option>
                  <option>Within 2 weeks</option>
                  <option>This month</option>
                  <option>Next quarter</option>
                  <option>Just exploring</option>
                </select>
              </div>
            </div>

            <div class="field">
              <label for="cQuery">Tell us about the project <span class="req">*</span></label>
              <textarea class="ctrl" id="cQuery" name="query" placeholder="Project type, software, scope, deadline — and anything you have already drawn…" required></textarea>
            </div>

            <div class="field">
              <label for="cSource">How did you hear about us?</label>
              <select class="ctrl" id="cSource" name="source">
                <option value="">Select an option</option>
                <option>Google search</option>
                <option>Upwork</option>
                <option>LinkedIn</option>
                <option>Behance</option>
                <option>Referral from a colleague</option>
                <option>Other</option>
              </select>
            </div>

            <label class="check">
              <input type="checkbox" name="nda" value="yes">
              <span>This project requires an NDA before we share details</span>
            </label>

            <!-- honeypot: bots fill this, people never see it -->
            <div class="hp" aria-hidden="true">
              <label for="cUrl">Company URL</label>
              <input type="text" id="cUrl" name="company_url" tabindex="-1" autocomplete="off">
            </div>

            <button class="btn-a btn-a--block" type="submit" data-magnetic>
              {{ $page->text('form.submit') }}<svg class="ico" aria-hidden="true"><use href="#i-arrow-right"></use></svg>
            </button>
            <p class="form-status" id="formStatus" role="status" aria-live="polite" @if(session('status')) data-state="ok" @endif>{{ session('status') }}</p>
            @if($errors->any())
              <p class="form-status" data-state="err">{{ $errors->first() }}</p>
            @endif
          </form>
        </div>

      </div>
    </div>
  </section>

  <section class="section section--tight section--ink-soft" aria-labelledby="officeHeading">
    <div class="shell">
      <div class="section-head section-head--center" data-reveal>
        <p class="eyebrow">{{ $page->text('offices.eyebrow') }}</p>
        <h2 id="officeHeading" class="h2">{!! $page->text('offices.heading') !!}</h2>
      </div>
      <div class="office-grid" data-stagger style="margin-top:clamp(2rem,4vw,3rem)">
        <article class="office">
          <h3>{{ $s->get('office_us_title') }}</h3>
            <address>{!! nl2br(e($s->get('office_us'))) !!}</address>
          <a class="link-a" href="tel:{{ $s->get('phone_link') }}"><svg class="ico" aria-hidden="true"><use href="#i-phone"></use></svg>{{ $s->get('phone') }}</a>
        </article>
        <article class="office">
          <h3>{{ $s->get('office_pk_title') }}</h3>
            <address>{!! nl2br(e($s->get('office_pk'))) !!}</address>
          <a class="link-a" href="mailto:{{ $s->get('email') }}"><svg class="ico" aria-hidden="true"><use href="#i-mail"></use></svg>{{ $s->get('email') }}</a>
        </article>
        <article class="office">
          <h3>{{ $page->text('offices.third_title') }}</h3>
          <address>{!! $page->text('offices.third_body') !!}</address>
          <a class="link-a" href="{{ route('faq') }}"><svg class="ico" aria-hidden="true"><use href="#i-globe"></use></svg>Read the FAQs</a>
        </article>
      </div>
    </div>
  </section>

  <section class="section cta-band" aria-labelledby="ctaHeading">
    <div class="shell shell--narrow text-center">
      <p class="eyebrow" data-reveal style="justify-content:center">{{ $page->text('cta.eyebrow') }}</p>
      <h2 id="ctaHeading" data-reveal>{!! $page->text('cta.heading') !!}</h2>
      <p data-reveal>{{ $page->text('cta.text') }}</p>
      <div class="cta-band__actions" data-reveal>
        <a class="btn-a" href="{{ route('pricing') }}" data-magnetic>{{ $page->text('cta.button1') }}<svg class="ico" aria-hidden="true"><use href="#i-arrow-right"></use></svg></a>
        <a class="btn-a btn-a--ghost" href="{{ route('projects.index') }}" data-magnetic>{{ $page->text('cta.button2') }}</a>
      </div>
    </div>
  </section>


@endsection

@push('schema')
<script type="application/ld+json">
{!! json_encode([
  '@context' => 'https://schema.org',
  '@graph' => [
    ['@type' => 'BreadcrumbList', 'itemListElement' => [
      ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => route('home')],
      ['@type' => 'ListItem', 'position' => 2, 'name' => 'Contact', 'item' => route('contact')],
    ]],
    ['@type' => 'ContactPage', 'url' => route('contact'),
     'name' => $page?->meta_title, 'description' => $page?->meta_description],
    ['@type' => 'Organization', 'name' => $s->get('site_name'), 'url' => route('home'),
     'email' => $s->get('email'), 'telephone' => $s->get('phone'),
     'contactPoint' => [[
        '@type' => 'ContactPoint', 'telephone' => $s->get('phone'), 'email' => $s->get('email'),
        'contactType' => 'sales', 'availableLanguage' => ['English'],
     ]]],
  ],
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
</script>
@endpush
