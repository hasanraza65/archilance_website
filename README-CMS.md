# Archilance CMS

Laravel 13 rebuild of the Archilance site. The public pages are the same design
as the static build; everything they show now comes from the database.

## Local setup

Database `archilance_site` on MySQL, user `root`, no password (see `.env`).

```
php artisan migrate:fresh --seed     # rebuild + reload all content
php artisan serve                    # http://127.0.0.1:8000
```

## Admin

`/admin` — redirects to the sign-in form when logged out.

    admin@archilance.net
    f17@AYDS

## What is editable

| Module | Route | Notes |
|---|---|---|
| Pages | `/admin/pages` | Hero copy + SEO for home, services, projects, about, faq, contact, blog. `content` holds page-specific blocks (About uses `story` + `values`). |
| Services | `/admin/services` | Full detail page: intro, stats, deliverables, process, tools, FAQs, related, images, SEO. |
| Projects | `/admin/projects` | Portfolio grid: images, filter keys, tags, grid span, SEO. |
| Blog | `/admin/posts` | Posts + categories, scheduled publishing, cover, SEO. |
| Team | `/admin/team` | Org chart **and** a profile page per person at `/team/<slug>`. `parent_id` builds the tree; `team` sets the colour group. Turn off *Give them a profile page* for a department card. |
| FAQs | `/admin/faqs` | Questions + categories. Feeds the FAQ page *and* its schema. |
| Testimonials | `/admin/testimonials` | Homepage slider. |
| Pricing plans | `/admin/plans` | Homepage pricing cards and the Offer schema. |
| Media | `/admin/media` | Uploads, plus a picker over the images already bundled with the design. |
| Settings | `/admin/settings` | Contact details, socials, offices, stats, SEO defaults, analytics snippets. Grouped and extensible — you can add new keys from the UI. |
| SEO audit | `/admin/seo` | Every item with metadata, worst first, with title/description length checks. |
| Redirects | `/admin/redirects` | 301/302 rules, applied on 404 only so live routes are never intercepted. |
| Enquiries | `/admin/enquiries` | Contact form submissions, CSV export. |
| Users | `/admin/users` | Panel accounts. |

## Team profiles

Every published member with *Give them a profile page* on gets `/team/<slug>`, built
from one template — sections hide themselves when the field behind them is empty, so a
half-filled record still renders a tidy page. The org chart's hover card and its dialog
both link out to it.

```
php artisan team:profiles              # fill blank profile fields only — safe to re-run
php artisan team:profiles --force      # overwrite existing copy too
php artisan team:profiles --slug=asad-abbasi
```

The generator writes from what the record already holds — role, the org-chart blurb, the
education note and the real reporting tree. It invents no biography: no years of service,
employers, awards or project counts. Toolsets are inferred from the role and are worth
confirming per person.

## Notifications

Contact enquiries and quote requests are emailed to the studio as well as stored.

```
ADMIN_EMAIL=someone@archilance.net     # recipient; comma-separate for several
php artisan mail:test                  # verify SMTP + ADMIN_EMAIL on any host
php artisan mail:test --to=me@x.com    # send a one-off elsewhere
```

Mail is sent with Laravel's `defer()` — after the response is flushed — so the visitor gets
their confirmation in about half a second rather than waiting on the SMTP round trip, and no
queue worker is needed. The enquiry is saved *before* mail is attempted and failures are only
logged, so a mail outage can never cost a lead. `MAIL_TIMEOUT` (default 15s) bounds a stalled
SMTP server. Replies go to the enquirer, not the site mailbox.

## Notes

- `sitemap.xml` is generated from the database, so new content appears immediately.
- Every content type shares one `ResourceController`; a new module needs a model,
  a subclass declaring rules, and two Blade views.
- Settings are cached (`settings.map`) and the cache clears on save.
- The contact form posts to `/enquiry`, stores the row and honours a honeypot.
