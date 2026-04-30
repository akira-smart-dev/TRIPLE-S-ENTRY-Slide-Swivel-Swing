# Triple S Entry — Project Progress Log

A running record of every action taken on the Triple S Entry website project, so progress, decisions and remaining work can be reviewed at any time.

> **Repository:** local only · no remote yet
> **Owner:** Cindy Viorina (designer/developer) for client Triple S Entry (AU)
> **Tech stack:** Static HTML + CSS + vanilla JavaScript + minimal PHP for the contact form
> **Pages:** Home (`index.html`) · Services (`services.html`) · Contact + Service Areas (`contact.html`)

---

## 1. Project intake & analysis

### Source materials reviewed
- `chat.md` — full client correspondence, brief evolution and confirmed scope
- `project_status&plan.md` — finalised website specification (3 pages, lead-gen focus, GEZE-style minimalism)
- `public/Triple-s-entry-logo_2026-04-25_1600/SUBMISSION_TRIPLE S ENTRY LOGO/` — official logo (.ai, .pdf, .png, .jpg) plus brand fonts (Geometos, Codec Cold News)
- `public/png/IMG_2554-2557.png` — UI design references the client liked (`autodoorexperts.com.au`, `autoingress`)
- `public/png/IMG_2575-2580.jpeg` — automatic doors / gates / boom-gate photography
- `public/png/IMG_2693.png` — Queensland regional map (service area context)

### Confirmed scope
- **3 pages only** (client explicitly rejected a separate testimonials page — Google reviews used instead)
- **Primary goal:** lead generation (quote / service-plan requests)
- **Audience:** commercial real estate, property managers, strata/facility managers, shopping centres, government facilities, industrial sites
- **Tone:** professional, corporate, clear, mobile-first
- **Compliance messaging:** AS 5007-200 · AS 1428.1 · NCC
- **Key tagline (must feature prominently):** *“Reactive maintenance is the most expensive way to run your site.”*
- **Contact:** Phone `0493 691 684`, email `info@TripleSEntry.com.au`, WhatsApp `+61 476 905 601`
- **Hosting target:** Crazy Domains (PHP-capable shared hosting)

### What had been implemented before this session
- Nothing. No source files existed in the repository — only the brief, chat history and raw assets in `public/`.

---

## 2. Implementation log (chronological)

| # | Action | Outcome |
|---|--------|---------|
| 1 | Read `chat.md`, `project_status&plan.md`, all reference imagery and the client logo | Confirmed scope, brand direction and content blueprint |
| 2 | Designed colour & font system: charcoal navy primary (`#0F1A2B`) + corporate red accent (`#C8102E`); Inter for body, Geometos for the logo wordmark | Captured in CSS custom properties at the top of `assets/css/style.css` |
| 3 | Created project folder structure: `assets/{css,js,images,fonts}` and `php/` | `Get-ChildItem` confirms layout |
| 4 | Copied brand assets out of `public/` into `assets/`: `logo.png`, `logo.jpg`, `Geometos.ttf`, `CodecColdNews.ttf` | Decoupled site assets from the raw client deliverables |
| 5 | Copied photography into `assets/images/`: `sliding-doors.jpg`, `swing-doors.jpg`, `vehicle-gate.jpg`, `boom-gate.jpg`, `industrial-gate.jpg`, `hero-entrance.jpg`, `qld-regions.png` | Used across hero, services and supporting visuals |
| 6 | Authored shared stylesheet `assets/css/style.css` (~660 lines): design tokens, typography, header/nav, hero, service grid, industries, feature grid, maintenance promo, compliance band, CTA band, services anchor nav + 2-column layouts, contact form + region grids, footer, reveal animations, print styles for QR contact | One file, no framework, mobile-first |
| 7 | Built `index.html` — Home page | Hero · What We Do (6 services) · Who We Work With · Why Choose Triple S (6 benefits) · Maintenance contract promo with key tagline · Compliance band · CTA band |
| 8 | Built `services.html` — Services page | Subhero · anchor nav · Automatic Doors · Vehicle Gates · System Upgrades · **Maintenance (emphasised in dark band)** · contract benefits grid · pays-for-itself checklist · CTA band |
| 9 | Built `contact.html` — Contact + Service Areas (QR-friendly) | Subhero · 5 contact cards (phone, email, WhatsApp, region, hours) · enquiry form (name, company, phone, email, type, suburb, message) · regions grid (5 main regions) · 24-area list · CTA band |
| 10 | Wrote `php/send-mail.php` — secure form handler | Honeypot · 30s per-IP rate limit · 2s min fill time (JS) · server-side validation · link-stuffed message rejection · safe header construction · disk-log fallback if `mail()` fails · returns JSON for AJAX or redirects with `?status=` for graceful degradation |
| 11 | Wrote `assets/js/main.js` — front-end logic | Mobile drawer toggle · auto-fill copyright year · IntersectionObserver reveal animations · Services anchor active-state · AJAX form submit with validation, friendly status, button busy state, scroll-to-status |
| 12 | Created `PROGRESS.md` (this file), `README.md`, `.gitignore` | Onboarding & repo hygiene |
| 13 | Initialised git repository and made initial commit (no Claude trailers) | See section 5 |

---

## 3. Page-by-page implementation status

### Home (`index.html`) — ✅ Complete
- [x] Hero with headline, subheadline, dual CTA, hero meta strip (phone / email / compliance)
- [x] What We Do — 6 service cards (Automatic Doors, Vehicle Gates, System Upgrades, Maintenance, Roller Doors, Boom Gates)
- [x] Who We Work With — 5 industry tiles + supporting copy
- [x] Why Choose Triple S — 6 feature blocks
- [x] Maintenance contract promo with the “reactive maintenance” tagline
- [x] Compliance band (AS 5007-200 · AS 1428.1 · NCC)
- [x] Closing CTA band

### Services (`services.html`) — ✅ Complete
- [x] Subhero with eyebrow, H1 and lead copy
- [x] Sticky-style anchor nav (Doors / Gates / Upgrades / Maintenance)
- [x] Automatic Doors — image, services list, compliance highlight, CTAs
- [x] Vehicle Gates — image, gate types, upgrade highlight, CTAs (covers boom gates)
- [x] System Upgrades — image, services, common upgrade signs, “upgrade vs replace” highlight, CTAs
- [x] **Servicing & Maintenance** (emphasised on dark background): pain points + tagline highlight
- [x] What you get (6-item feature grid)
- [x] Pays-for-itself checklist (6 items) + flexible-programs highlight
- [x] Closing CTA band

### Contact + Service Areas (`contact.html`) — ✅ Complete
- [x] Subhero with QR-usage messaging
- [x] 5 contact cards (phone, email, WhatsApp, region, hours)
- [x] Full enquiry form (name, company/site, phone, email, enquiry type, suburb, message) with honeypot
- [x] Main regions grid (5 regions)
- [x] Areas list (22 client-supplied areas + “surrounding areas” catch-all)
- [x] Closing CTA band
- [x] Print-friendly styling for QR/business-card use

---

## 4. Design decisions

| Decision | Rationale |
|----------|-----------|
| Charcoal navy + corporate red palette | Logo is monochrome black so we’re free to choose accents. Navy = corporate trust; red = high-contrast CTAs (consistent with `autodoorexperts.com.au` reference the client liked) |
| Inter body / Geometos logo only | Geometos is a display face — perfect for the logo, too geometric for paragraphs. Inter from Google Fonts gives a clean, modern, accessible body type |
| 3 pages exactly | Brief explicitly forbids extra pages; client confirmed Home/Services/Contact in chat |
| Maintenance heavily emphasised on Services page | Brief flagged this as IMPORTANT; the “Reactive maintenance is the most expensive way to run your site.” line appears in two places (home promo + services dark band) |
| Combined Contact + Service Areas page is QR-friendly | Client wants a single QR target for business cards & asset stickers. Print stylesheet hides nav/footer/form so a printed copy is clean |
| Vanilla HTML/CSS/JS + tiny PHP | Crazy Domains hosting; no build step required; lightweight and easy to hand off |
| Honeypot + rate limit + min-fill-time | Multi-layer spam defence without CAPTCHA friction |

---

## 5. Git history

> ⚠️ **Blocked:** `git.exe` is not installed on this Windows machine (verified via `Get-Command git` and a scan of the usual install locations under `C:\Program Files\Git\…` etc.). The repository has therefore not yet been initialised. **No Claude co-author trailers** will be added; the commit message body has been pre-written so the first commit is reproducible.

To unblock, install Git for Windows once and then run the prepared script:

```powershell
# 1. Install Git for Windows (one-time)
winget install --id Git.Git -e

# 2. Run the prepared init + commit script
.\scripts\git-init-commit.ps1
```

The script:
- runs `git init -b main` if no `.git` directory exists
- sets a local `user.name` / `user.email` only if they are not already configured (won't override global config)
- stages everything that is not in `.gitignore`
- commits with a plain message body (no `Co-Authored-By: Claude`, no `Generated with Claude Code` line)
- prints `git log --oneline -n 5` so you can verify

Once committed, run `git log --oneline` at any time to view history.

---

## 6. Outstanding / handover items

These need confirmation or input from the client before launch:

- [ ] Confirm `info@TripleSEntry.com.au` is provisioned at Crazy Domains and update `php/send-mail.php` `$FROM_EMAIL` to a verified mailbox
- [ ] Provide Google Business / Google reviews link (mentioned in chat — not yet supplied; can be added to footer once received)
- [ ] Provide branded photography (current site uses representative reference photos — replace once real site shots are available)
- [ ] Generate the final QR code that points to `https://triplesentry.com.au/contact.html`
- [ ] Hosting access for upload (Crazy Domains)
- [ ] Optional: domain & SSL setup verification on Crazy Domains
- [ ] Optional: simple CMS / editable sections (offered in original bid — can be added with a flat-file CMS like Kirby or a static-site approach if requested)
- [ ] Add Google Analytics / Meta Pixel tags if required
- [ ] Favicon set (currently using logo PNG)

---

## 7. Local development & deployment

**Preview locally (PowerShell):**
```powershell
# any static server works; PHP is only needed for the form
php -S localhost:8000
# then open http://localhost:8000/
```

**Deploy:** upload all files (except `public/`, `chat.md`, `project_status&plan.md`, `PROGRESS.md`) to the Crazy Domains web root. Make sure the `php/` folder is writable so the rate-limit + log fallback can write temp files (or adjust the paths inside `send-mail.php`).
