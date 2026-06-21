{{-- Language switcher for EUP portal (injected when EupSwLang is not active). --}}
{{-- JS moves it into .navbar-right the same way EupSwLang does.              --}}
<div id="eup-lang-switcher-container" style="display:none;">
    <li class="dropdown" id="eup-lang-switcher">
        <a href="#"
           class="dropdown-toggle dropdown-toggle-icon"
           data-toggle="dropdown"
           title="{{ __('Language') }}">
            <i class="glyphicon glyphicon-globe"></i>
            <small class="eup-lang-name">{{ $locales[$currentLocale] ?? strtoupper($currentLocale) }}</small>
            <span class="caret"></span>
        </a>
        <ul class="dropdown-menu">
            @foreach ($locales as $code => $name)
                @php
                    $switchUrl = $baseUrl . '?' . http_build_query(array_merge($existingParams, ['eup_locale' => $code]));
                @endphp
                <li @if ($code === $currentLocale) class="active" @endif>
                    <a href="{{ $switchUrl }}">{{ $name }}</a>
                </li>
            @endforeach
        </ul>
    </li>
</div>
<script {!! \Helper::cspNonceAttr() !!}>
(function () {
    function injectLangSwitcher() {
        var container = document.getElementById('eup-lang-switcher-container');
        if (!container) return;
        var switcher = document.getElementById('eup-lang-switcher');
        if (!switcher) return;
        var navList = document.querySelector('.navbar .navbar-right');
        if (!navList) return;
        navList.insertBefore(switcher, navList.firstChild);
        switcher.style.display = '';
        container.parentNode.removeChild(container);
    }
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', injectLangSwitcher);
    } else {
        injectLangSwitcher();
    }
})();
</script>
