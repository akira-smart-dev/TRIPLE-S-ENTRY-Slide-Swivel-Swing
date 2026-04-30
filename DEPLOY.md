# Triple S Entry — Deployment Guide (GitHub + Vercel)

This guide takes the project from your local machine to a live URL on Vercel.

> **Repo:** https://github.com/akira-smart-dev/TRIPLE-S-ENTRY-Slide-Swivel-Swing.git

---

## Step 0 — Make sure Git is installed

Open PowerShell and run:

```powershell
git --version
```

If you see `git not recognized`, install once:

```powershell
winget install --id Git.Git -e
# Close and reopen PowerShell after install
```

---

## Step 1 — Verify the file list before pushing

From the project root:

```powershell
cd "E:\Freelancer Project\Cindy_4_29\Nicholas"
git status
```

Anything in `public/`, `chat.md`, `project_status&plan.md`, `scripts/` should **not** appear — they are excluded by `.gitignore`.

If something accidentally shows up that shouldn't, edit `.gitignore` before continuing.

---

## Step 2 — Initialise the repo locally and connect to GitHub

```powershell
# Initialise (only the first time)
git init -b main

# Identify yourself (skip if you already have a global git config)
git config user.name  "Cindy Viorina"
git config user.email "you@example.com"

# Connect to the GitHub repo
git remote add origin https://github.com/akira-smart-dev/TRIPLE-S-ENTRY-Slide-Swivel-Swing.git
```

If `origin` already exists, update it:

```powershell
git remote set-url origin https://github.com/akira-smart-dev/TRIPLE-S-ENTRY-Slide-Swivel-Swing.git
```

---

## Step 3 — Commit and push

```powershell
git add -A
git commit -m "Initial site build — Triple S Entry (3-page lead-gen website)"
git push -u origin main
```

When prompted, sign in to GitHub (a browser window will open). After this, every future push is just `git push`.

---

## Step 4 — Deploy on Vercel (web UI, easiest)

1. Go to **https://vercel.com** and sign in with your **GitHub** account.
2. Click **Add New… → Project**.
3. Find **TRIPLE-S-ENTRY-Slide-Swivel-Swing** in the list and click **Import**.
4. Settings panel:
   - **Framework Preset:** *Other* (Vercel will auto-detect "static")
   - **Root Directory:** `./`
   - **Build Command:** *leave blank*
   - **Output Directory:** *leave blank*
   - **Install Command:** *leave blank*
5. Click **Deploy**.

Vercel will give you a temporary URL (something like `triple-s-entry-slide-swivel-swing.vercel.app`) within ~30 seconds. Every future `git push` to `main` automatically redeploys.

---

## Step 5 — IMPORTANT: contact form on Vercel

> **Vercel does not run PHP.** The current `php/send-mail.php` file will return 404 on Vercel and the form will fail.

Pick one of these two paths:

### Option A — Use Formspree (recommended, ~5 minutes)

Free tier: 50 submissions/month, no code on your side.

1. Sign up at **https://formspree.io** with the inbox you want enquiries to land in (`info@TripleSEntry.com.au`).
2. Click **+ New Form** → name it "Triple S Entry Enquiries".
3. Copy your form endpoint — it looks like `https://formspree.io/f/abcd1234`.
4. Open `contact.html`, find this line near the top of the form:
   ```html
   <form class="form" id="enquiryForm" action="php/send-mail.php" method="post" novalidate>
   ```
   Change the `action` to your Formspree URL:
   ```html
   <form class="form" id="enquiryForm" action="https://formspree.io/f/abcd1234" method="post" novalidate>
   ```
5. Commit and push:
   ```powershell
   git add contact.html
   git commit -m "Switch contact form to Formspree endpoint"
   git push
   ```
6. Vercel auto-redeploys. Test the form on the live URL.

The existing JS in `assets/js/main.js` already speaks JSON via the `X-Requested-With: XMLHttpRequest` header — Formspree returns a compatible JSON response, so no JS changes are needed. Done.

### Option B — Vercel Serverless Function (more control)

If you want to keep the email logic in your own code: create `/api/contact.js` using Node.js + a transactional email service like **Resend** (free 100/day) or **SendGrid**. Ask if you want this — I can wire it up.

---

## Step 6 — Custom domain (optional, when ready)

1. In Vercel: **Project → Settings → Domains → Add**.
2. Enter the domain (e.g. `triplesentry.com.au`).
3. Vercel will show DNS records (an A record and/or CNAME).
4. In **Crazy Domains** → DNS for the domain → add the records exactly as shown.
5. Wait 5–60 min for DNS to propagate. Vercel issues an SSL certificate automatically.

---

## Cheat sheet — daily workflow after deploy

```powershell
# make changes...
git add -A
git commit -m "Short message about what changed"
git push
# Vercel auto-deploys in ~30 seconds
```

Watch deploys at: **https://vercel.com/akira-smart-dev/triple-s-entry-slide-swivel-swing**

---

## Common issues

| Symptom | Fix |
|--|--|
| `git push` rejected: "fetch first" | Run `git pull --rebase origin main` then `git push` |
| Form returns 404 on Vercel | You haven't done **Step 5** — switch the `action=` URL |
| Logo missing on live site | Confirm `assets/images/logo.png` was committed: `git ls-files assets/images/` |
| Vercel says "no framework detected" | That's fine — it's a static site. Click **Deploy** anyway. |
