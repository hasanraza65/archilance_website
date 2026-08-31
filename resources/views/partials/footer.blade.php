<!-- ================================ Footer ================================ -->
<footer class="site-footer">
  <div class="site-footer__bg" aria-hidden="true">
    <img src="{{ asset('assets/img/hero/footer-bg-sm.webp') }}" srcset="{{ asset('assets/img/hero/footer-bg-sm.webp') }} 900w, {{ asset('assets/img/hero/footer-bg.webp') }} 1600w" sizes="100vw" width="1600" height="900" alt="" loading="lazy" decoding="async">
  </div>

  <div class="shell">
    <div class="footer-top">
      <div class="footer-brand">
        <a class="brand" href="{{ route('home') }}" aria-label="Archilance LLC — home">
          <img class="brand__mark" src="{{ asset('assets/img/brand/logo-mark.webp') }}" width="260" height="247" alt="" loading="lazy" decoding="async" style="height:52px">
          <span class="brand__word"><b>{{ $s->brandName() }}</b><span>{{ $s->brandSuffix() }}</span></span>
        </a>
        <p style="margin-top:1.35rem">{{ $s->get('footer_blurb') }}</p>
        <div class="footer-brand__badges">
          <img src="{{ asset('assets/img/brand/upwork-badge.webp') }}" width="220" height="220" alt="5-star rated Upwork agency" loading="lazy" decoding="async">
          <img src="{{ asset('assets/img/brand/iso-9001.webp') }}" width="220" height="220" alt="ISO 9001 certified company" loading="lazy" decoding="async">
        </div>
        <div class="socials" style="margin-top:1.5rem">
          <a href="{{ $s->get('linkedin') }}" target="_blank" rel="noopener noreferrer" aria-label="Archilance on LinkedIn"><svg class="ico" aria-hidden="true"><use href="#i-linkedin"></use></svg></a>
          <a href="{{ $s->get('behance') }}" target="_blank" rel="noopener noreferrer" aria-label="Archilance on Behance"><svg class="ico" aria-hidden="true"><use href="#i-behance"></use></svg></a>
          <a href="https://wa.me/{{ $s->get('whatsapp') }}" target="_blank" rel="noopener noreferrer" aria-label="Archilance on WhatsApp"><svg class="ico" aria-hidden="true"><use href="#i-whatsapp"></use></svg></a>
        </div>
      </div>

      <nav class="footer-col" aria-labelledby="fServices">
        <h3 id="fServices">Services</h3>
        <ul>
          @foreach($navServices as $svc)
            <li><a href="{{ route('services.show', $svc) }}">{{ $svc->short_name }}</a></li>
          @endforeach
        </ul>
      </nav>

      <nav class="footer-col" aria-labelledby="fCompany">
        <h3 id="fCompany">Company</h3>
        <ul>
          <li><a href="{{ route('about') }}">About</a></li>
          <li><a href="{{ route('projects.index') }}">Projects</a></li>
          <li><a href="{{ route('services.index') }}">Services</a></li>
          <li><a href="{{ route('pricing') }}">Pricing</a></li>
          <li><a href="{{ route('home') }}#testimonials">Testimonials</a></li>
          <li><a href="{{ route('faq') }}">FAQs</a></li>
          <li><a href="{{ route('blog.index') }}">Blog</a></li>
          <li><a href="{{ route('contact') }}">Contact</a></li>
        </ul>
      </nav>

      <div class="footer-col">
        <h3>Offices</h3>
        <ul>
          <li>
            <address>
              <strong style="color:var(--text-on-dark);font-weight:600">{{ $s->get('office_us_title') }}</strong><br>
              {!! nl2br(e($s->get('office_us'))) !!}
            </address>
          </li>
          <li>
            <address>
              <strong style="color:var(--text-on-dark);font-weight:600">{{ $s->get('office_pk_title') }}</strong><br>
              {!! nl2br(e($s->get('office_pk'))) !!}
            </address>
          </li>
          <li><a href="tel:{{ $s->get('phone_link') }}"><svg class="ico" aria-hidden="true"><use href="#i-phone"></use></svg>{{ $s->get('phone') }}</a></li>
          <li><a href="mailto:{{ $s->get('email') }}"><svg class="ico" aria-hidden="true"><use href="#i-mail"></use></svg>{{ $s->get('email') }}</a></li>
        </ul>
      </div>
    </div>

    <div class="footer-bottom">
      <p style="margin:0">&copy; <span id="year">{{ date('Y') }}</span> {{ $s->get('site_name') }}. All rights reserved.</p>
      <nav aria-label="Legal">
        <a href="{{ route('page', 'privacy-policy') }}">Privacy Policy</a>
        <a href="{{ route('page', 'terms') }}">Terms</a>
        <a href="{{ route('sitemap') }}">Sitemap</a>
        <a href="{{ $s->get('customer_portal', '#') }}">Customer Portal</a>
      </nav>
    </div>
  </div>
</footer>
