{{-- Injected on Kanban pages: adds org filter dropdown + client-side card filtering --}}
<script>
(function () {
    var orgs = {!! $organizations->map(fn($o) => ['id' => $o->id, 'name' => $o->name])->values()->toJson(JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) !!};
    var STORAGE_KEY = 'orgportal_kn_filter';
    var filterSelect = null;

    function buildSelect() {
        var sel = document.createElement('select');
        sel.className = 'form-control orgportal-kn-org-filter';

        var all = document.createElement('option');
        all.value = '';
        all.textContent = '{{ __("orgportal::messages.all_organizations") }}';
        sel.appendChild(all);

        orgs.forEach(function (org) {
            var opt = document.createElement('option');
            opt.value = org.id;
            opt.textContent = org.name;
            sel.appendChild(opt);
        });

        var saved = sessionStorage.getItem(STORAGE_KEY);
        if (saved) {
            sel.value = saved;
        }

        sel.addEventListener('change', function () {
            sessionStorage.setItem(STORAGE_KEY, this.value);
            applyFilter(this.value);
        });

        return sel;
    }

    function applyFilter(orgId) {
        document.querySelectorAll('.kn-card').forEach(function (card) {
            if (!orgId) {
                card.style.display = '';
                return;
            }
            var badge = card.querySelector('.orgportal-org-badge');
            card.style.display = (badge && String(badge.dataset.orgId) === String(orgId)) ? '' : 'none';
        });
    }

    function inject() {
        if (filterSelect) return;

        var headingItem = document.querySelector('#kn-heading .in-heading-item');
        if (!headingItem) return;

        var wrapper = document.createElement('span');
        wrapper.className = 'orgportal-kn-filter-wrap';
        filterSelect = buildSelect();
        wrapper.appendChild(filterSelect);
        headingItem.appendChild(wrapper);

        if (filterSelect.value) {
            applyFilter(filterSelect.value);
        }

        // Re-apply after Kanban AJAX board reload (board content replacement)
        var board = document.getElementById('kn-board');
        if (board) {
            new MutationObserver(function () {
                if (filterSelect && filterSelect.value) {
                    // Small delay to let Kanban finish rendering
                    setTimeout(function () { applyFilter(filterSelect.value); }, 80);
                }
            }).observe(board, { childList: true, subtree: false });
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', inject);
    } else {
        inject();
    }
})();
</script>
