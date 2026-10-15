/**
 * Behaviour that used to live in inline on* attributes.
 *
 * FreeScout sends a Content-Security-Policy with a script nonce, so the
 * browser ignores onsubmit="..." / onclick="..." / onchange="..." in the
 * markup: confirmations were skipped and forms went straight through.
 * Views now carry data attributes and this file (loaded from 'self' through
 * the javascripts / eup.javascripts filters) acts on them:
 *
 *   data-orgportal-confirm="Text"  on a <form>: ask before submitting;
 *                                  on a button: ask before its click submits.
 *   data-orgportal-autosubmit      on a <select>: submit its form on change.
 *
 * Listeners sit on document in the capture phase, so they also cover rows
 * built later by scripts and run before any other submit handler.
 */
(function () {
    'use strict';

    if (window.orgportalCspHandlers) {
        return;
    }
    window.orgportalCspHandlers = true;

    function stop(e) {
        e.preventDefault();
        e.stopImmediatePropagation();
    }

    document.addEventListener('click', function (e) {
        var btn = e.target.closest ? e.target.closest('[data-orgportal-confirm]') : null;
        if (!btn || btn.tagName === 'FORM') {
            return;
        }
        if (!window.confirm(btn.getAttribute('data-orgportal-confirm'))) {
            stop(e);
            return;
        }
        // The form's own confirmation must not ask a second time.
        if (btn.form) {
            btn.form.setAttribute('data-orgportal-confirmed', '1');
        }
    }, true);

    document.addEventListener('submit', function (e) {
        var form = e.target;
        if (!form || !form.hasAttribute || !form.hasAttribute('data-orgportal-confirm')) {
            return;
        }
        if (form.getAttribute('data-orgportal-confirmed') === '1') {
            form.removeAttribute('data-orgportal-confirmed');
            return;
        }
        if (!window.confirm(form.getAttribute('data-orgportal-confirm'))) {
            stop(e);
        }
    }, true);

    document.addEventListener('change', function (e) {
        var el = e.target;
        if (el && el.hasAttribute && el.hasAttribute('data-orgportal-autosubmit') && el.form) {
            el.form.submit();
        }
    }, true);
})();
