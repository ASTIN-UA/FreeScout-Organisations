{{--
    Injected via layout.body_bottom for EUP managers only.
    Adds "Company Tickets" and settings links to the EUP navbar without touching EUP source files.
--}}
<script>
(function () {
    var nav = document.querySelector('.nav.navbar-nav.navbar-right');
    if (!nav) return;

    var companyUrl = '{{ route("orgportal.portal.company-tickets", ["mailbox_id" => $mailbox_id]) }}';
    var settingsUrl = '{{ route("orgportal.portal.settings", ["mailbox_id" => $mailbox_id]) }}';
    var path = window.location.pathname;

    function makeItem(href, label, isActive) {
        var li = document.createElement('li');
        if (isActive) li.className = 'active';
        var a = document.createElement('a');
        a.href = href;
        a.textContent = label;
        li.appendChild(a);
        return li;
    }

    var isCompanyActive = path.indexOf('/org/company-tickets') !== -1;
    var isSettingsActive = path.indexOf('/org/settings') !== -1;

    // Insert before last nav item (user dropdown / login link)
    var anchor = nav.lastElementChild;
    nav.insertBefore(makeItem(settingsUrl,  '{{ __("orgportal::messages.org_settings_nav") }}', isSettingsActive), anchor);
    nav.insertBefore(makeItem(companyUrl,   '{{ __("orgportal::messages.company_tickets") }}',  isCompanyActive), anchor);
})();
</script>
