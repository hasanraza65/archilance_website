<div class="otip" id="otip" role="tooltip" aria-hidden="true">
  <div class="otip__inner">
    <div class="otip__head">
      <div class="otip__pic" id="otipPic"></div>
      <div class="otip__id">
        <p class="otip__tag" id="otipTag"></p>
        <p class="otip__name" id="otipName"></p>
        <p class="otip__role" id="otipRole"></p>
      </div>
    </div>
    <p class="otip__blurb" id="otipBlurb"></p>
    <p class="otip__extra" id="otipExtra" hidden></p>
    <p class="otip__reports" id="otipReports" hidden></p>
    {{-- a real link, so it opens the member's page in a new tab on middle
         click and shows the URL on hover like any other navigation --}}
    <a class="otip__cta" id="otipCta" href="#">
      View full profile
      <svg class="ico" aria-hidden="true"><use href="#i-arrow-right"></use></svg>
    </a>
  </div>
  <span class="otip__arrow" aria-hidden="true"></span>
</div>

<div class="person" id="person" role="dialog" aria-modal="true" aria-labelledby="personName" hidden>
  <div class="person__panel">
    <button class="person__close" type="button" id="personClose" aria-label="Close profile">
      <svg class="ico" aria-hidden="true"><use href="#i-close"></use></svg>
    </button>
    <div class="person__pic" id="personPic"></div>
    <p class="person__tag" id="personTag"></p>
    <h3 class="person__name" id="personName"></h3>
    <p class="person__role" id="personRole"></p>
    <p class="person__blurb" id="personBlurb"></p>
    <p class="person__extra" id="personExtra" hidden></p>
    <div class="person__reports" id="personReports" hidden>
      <h4>Direct reports</h4>
      <ul id="personReportsList"></ul>
    </div>

    {{-- touch devices never see the hover card, so the dialog carries the same
         way through to the full profile --}}
    <a class="btn-a btn-a--sm person__cta" id="personCta" href="#" hidden>
      View full profile
      <svg class="ico ico--arrow" aria-hidden="true"><use href="#i-arrow-right"></use></svg>
    </a>
  </div>
</div>