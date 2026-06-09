{{--
    OrgPortal wrapper around the ApiWebhooks settings view.

    Registered via the `settings.view` Eventy filter at a higher priority than
    ApiWebhooks itself, so this view is rendered instead of `apiwebhooks::settings`.
    We simply re-include the original ApiWebhooks view (no modification to that
    module) and append a server-rendered link to the OrgPortal API docs.

    Rendering the link as plain HTML (not via injected JS) avoids FreeScout's
    Content-Security-Policy blocking inline scripts that have no nonce, which is
    why the previous body_bottom <script> approach never executed.
--}}
@include('apiwebhooks::settings')

<script {!! \Helper::cspNonceAttr() !!}>
(function() {
    var existing = document.querySelector('a[href*="api-docs.freescout"]');
    if (!existing) return;
    var link = document.createElement('a');
    link.href    = '{{ url('orgportal/admin/api-docs') }}';
    link.target  = '_blank';
    link.rel     = 'noopener';
    link.textContent = '{{ __('orgportal::messages.api_docs_link') }}';
    var sep = document.createTextNode(' | ');
    existing.parentNode.insertBefore(sep, existing.nextSibling);
    existing.parentNode.insertBefore(link, sep.nextSibling);
})();
</script>
