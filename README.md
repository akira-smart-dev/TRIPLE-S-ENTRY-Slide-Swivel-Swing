# Triple S Entry — Website

Lead-generation website for Triple S Entry, a family-owned automatic door, vehicle gate and access-solutions business servicing Southern Queensland and Northern New South Wales.

## Stack
- Static HTML + CSS + vanilla JavaScript
- Tiny PHP handler for the contact form (`php/send-mail.php`)
- No build step, no framework

## Pages
| URL | File | Purpose |
|-----|------|---------|
| `/` | `index.html` | Home — hero, services overview, who we work with, why us, maintenance promo, compliance |
| `/services.html` | `services.html` | Combined services page (Automatic Doors, Vehicle Gates, System Upgrades, Maintenance) |
| `/contact.html` | `contact.html` | Contact + Service Areas (QR-friendly) — phone, email, form, regions, area list |

## Folder layout
```
.
├── index.html
├── services.html
├── contact.html
├── assets/
│   ├── css/style.css
│   ├── js/main.js
│   ├── fonts/        (Geometos.ttf, CodecColdNews.ttf)
│   └── images/       (logo, hero, services photography)
├── php/send-mail.php
├── public/           (raw client deliverables — not deployed)
├── PROGRESS.md       (chronological project log)
└── README.md
```

## Local preview
```powershell
php -S localhost:8000
# open http://localhost:8000/
```

## Deployment notes
- Upload everything **except** `public/`, `chat.md`, `project_status&plan.md`, `PROGRESS.md` to the Crazy Domains web root.
- Confirm PHP is enabled on the hosting plan.
- In `php/send-mail.php`, set `$FROM_EMAIL` to a mailbox verified by the host (Crazy Domains may reject mail otherwise).
- The handler logs to `logs/enquiries-YYYY-MM.log` if `mail()` fails, so submissions are never lost while DNS/SPF are being configured.

## Brand
- Logo wordmark: **Geometos**
- Body: **Inter** (Google Fonts)
- Primary: `#0F1A2B` (deep navy/charcoal)
- Accent: `#C8102E` (corporate red — CTAs)
- Tagline: *Slide · Swivel · Swing*
- Compliance: AS 5007-200 · AS 1428.1 · NCC

## Contact
- Phone: 0493 691 684
- Email: info@TripleSEntry.com.au
- WhatsApp: +61 476 905 601
