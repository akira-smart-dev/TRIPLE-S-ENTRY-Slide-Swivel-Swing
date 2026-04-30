/* Triple S Entry — front-end interactions */
(function () {
  'use strict';

  // ---------- Mobile drawer ----------
  var toggle  = document.querySelector('.nav__toggle');
  var drawer  = document.getElementById('mobileDrawer');
  if (toggle && drawer) {
    toggle.addEventListener('click', function () {
      var open = drawer.classList.toggle('is-open');
      toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
      drawer.setAttribute('aria-hidden', open ? 'false' : 'true');
      document.body.style.overflow = open ? 'hidden' : '';
    });

    // Close drawer when a nav link is tapped
    drawer.addEventListener('click', function (e) {
      if (e.target.tagName === 'A') {
        drawer.classList.remove('is-open');
        toggle.setAttribute('aria-expanded', 'false');
        drawer.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';
      }
    });
  }

  // ---------- Footer year ----------
  var year = document.getElementById('year');
  if (year) {
    year.textContent = String(new Date().getFullYear());
  }

  // ---------- Reveal on scroll ----------
  var revealEls = document.querySelectorAll('.reveal');
  if ('IntersectionObserver' in window && revealEls.length) {
    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          entry.target.classList.add('is-visible');
          io.unobserve(entry.target);
        }
      });
    }, { rootMargin: '0px 0px -10% 0px', threshold: 0.08 });
    revealEls.forEach(function (el) { io.observe(el); });
  } else {
    revealEls.forEach(function (el) { el.classList.add('is-visible'); });
  }

  // ---------- Active section indicator on Services anchor nav ----------
  var anchorLinks = document.querySelectorAll('.svc-anchor-nav a');
  if (anchorLinks.length && 'IntersectionObserver' in window) {
    var sections = [];
    anchorLinks.forEach(function (link) {
      var id = link.getAttribute('href');
      if (id && id.charAt(0) === '#') {
        var el = document.querySelector(id);
        if (el) sections.push({ el: el, link: link });
      }
    });
    var sio = new IntersectionObserver(function (entries) {
      entries.forEach(function (e) {
        if (e.isIntersecting) {
          anchorLinks.forEach(function (l) { l.classList.remove('is-active'); });
          var match = sections.find(function (s) { return s.el === e.target; });
          if (match) match.link.classList.add('is-active');
        }
      });
    }, { rootMargin: '-40% 0px -55% 0px' });
    sections.forEach(function (s) { sio.observe(s.el); });
  }

  // ---------- Enquiry form ----------
  var form = document.getElementById('enquiryForm');
  var status = document.getElementById('formStatus');

  function setStatus(state, message) {
    if (!status) return;
    status.classList.remove('is-success', 'is-error');
    if (state) status.classList.add(state);
    status.textContent = message || '';
  }

  // Show status from query param (e.g. server redirect fallback)
  var params = new URLSearchParams(window.location.search);
  if (params.get('status') === 'success') {
    setStatus('is-success', 'Thanks — your enquiry has been sent. We will be in touch shortly.');
  } else if (params.get('status') === 'error') {
    setStatus('is-error', 'Sorry — we could not send your enquiry just now. Please call 0493 691 684 or try again.');
  }

  if (form) {
    var submitTime = Date.now();
    form.addEventListener('submit', function (e) {
      e.preventDefault();

      // Min fill time anti-bot (must take at least 2 seconds)
      if (Date.now() - submitTime < 2000) {
        setStatus('is-error', 'Form submitted too quickly. Please try again.');
        return;
      }

      // Basic client-side validation
      var required = form.querySelectorAll('[required]');
      var firstInvalid = null;
      required.forEach(function (input) {
        input.classList.remove('is-invalid');
        if (!input.value || (input.type === 'email' && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(input.value))) {
          input.classList.add('is-invalid');
          if (!firstInvalid) firstInvalid = input;
        }
      });
      if (firstInvalid) {
        setStatus('is-error', 'Please fill in all required fields.');
        firstInvalid.focus();
        return;
      }

      var btn = form.querySelector('button[type="submit"]');
      if (btn) { btn.disabled = true; btn.textContent = 'Sending…'; }
      setStatus(null, '');

      var data = new FormData(form);

      fetch(form.action, {
        method: 'POST',
        body: data,
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
      })
        .then(function (res) {
          return res.json().catch(function () { return { ok: res.ok, message: '' }; });
        })
        .then(function (json) {
          if (json && json.ok) {
            setStatus('is-success', json.message || 'Thanks — your enquiry has been sent. We will be in touch shortly.');
            form.reset();
            window.scrollTo({ top: status.getBoundingClientRect().top + window.scrollY - 120, behavior: 'smooth' });
          } else {
            setStatus('is-error', (json && json.message) || 'Something went wrong. Please call 0493 691 684 or try again.');
          }
        })
        .catch(function () {
          setStatus('is-error', 'Network error. Please call 0493 691 684 or try again shortly.');
        })
        .finally(function () {
          if (btn) { btn.disabled = false; btn.textContent = 'Send Enquiry'; }
        });
    });
  }
})();
