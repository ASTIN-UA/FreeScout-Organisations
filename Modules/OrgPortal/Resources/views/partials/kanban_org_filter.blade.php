{{-- Injects org filter into Kanban's filter dropdown, styled identically to other Kanban filters --}}

{{-- Modal (hidden, appended once to body) --}}
<div class="modal fade" id="orgportal-kn-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title">{{ __('orgportal::messages.kanban_filter_org') }}</h4>
            </div>
            <div class="modal-body">
                <div class="row-container">
                    <div class="kn-filters-inputs">
                        @foreach($organizations as $org)
                        <div class="control-group">
                            <label class="checkbox" for="orgportal_kn_org_{{ $org->id }}">
                                <input type="checkbox"
                                       name="orgportal_org[]"
                                       value="{{ $org->id }}"
                                       id="orgportal_kn_org_{{ $org->id }}">
                                {{ $org->name }}
                            </label>
                        </div>
                        @endforeach
                    </div>
                    <div class="form-group text-center margin-top margin-bottom-10">
                        <button class="btn btn-primary btn-inline" id="orgportal-kn-apply">
                            {{ __('Apply Filter') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script{!! \Helper::cspNonceAttr() !!}>
(function () {
    var STORAGE_KEY = 'orgportal_kn_filter';

    function getSelected() {
        try { return JSON.parse(sessionStorage.getItem(STORAGE_KEY) || '[]'); } catch(e) { return []; }
    }
    function setSelected(ids) {
        sessionStorage.setItem(STORAGE_KEY, JSON.stringify(ids));
    }

    function applyFilter(selectedIds) {
        document.querySelectorAll('.kn-card').forEach(function (card) {
            if (!selectedIds.length) {
                card.style.display = '';
                return;
            }
            var badge = card.querySelector('.orgportal-org-badge');
            var orgId = badge ? String(badge.dataset.orgId) : '';
            card.style.display = selectedIds.indexOf(orgId) !== -1 ? '' : 'none';
        });
    }

    function updateCounter(selectedIds) {
        var counter = document.querySelector('.orgportal-kn-filter-li .kn-filter-counter');
        if (!counter) return;
        counter.textContent = selectedIds.length ? '(' + selectedIds.length + ')' : '';
        var li = document.querySelector('.orgportal-kn-filter-li');
        if (li) li.classList.toggle('active', selectedIds.length > 0);
    }

    function inject() {
        if (document.querySelector('.orgportal-kn-filter-li')) return;

        var filterDropdown = document.querySelector('.kn-param[data-param="filters"] .dropdown-menu');
        if (!filterDropdown) return;

        // Build <li> matching Kanban's filter items style
        var li = document.createElement('li');
        li.className = 'orgportal-kn-filter-li';

        var a = document.createElement('a');
        a.href = '#';

        var counter = document.createElement('span');
        counter.className = 'kn-filter-counter';

        a.appendChild(document.createTextNode('{{ __("orgportal::messages.kanban_filter_org") }} '));
        a.appendChild(counter);

        li.appendChild(a);

        // Insert before reset divider, or at end
        var divider = filterDropdown.querySelector('.kn-reset-filter');
        if (divider) {
            filterDropdown.insertBefore(li, divider);
        } else {
            filterDropdown.appendChild(li);
        }

        // Restore saved state
        var saved = getSelected();
        updateCounter(saved);
        if (saved.length) applyFilter(saved);

        // Click → close dropdown, open modal
        a.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();

            // Close the Kanban dropdown
            var $dropdown = $(li).closest('.dropdown');
            if ($dropdown.length) $dropdown.removeClass('open');

            // Restore checkboxes from saved state
            var current = getSelected();
            document.querySelectorAll('#orgportal-kn-modal input[type=checkbox]').forEach(function (cb) {
                cb.checked = current.indexOf(cb.value) !== -1;
            });

            // Show modal
            $('#orgportal-kn-modal').modal('show');
        });

        // Apply button
        document.getElementById('orgportal-kn-apply').addEventListener('click', function () {
            var selected = [];
            document.querySelectorAll('#orgportal-kn-modal input[type=checkbox]:checked').forEach(function (cb) {
                selected.push(cb.value);
            });
            setSelected(selected);
            applyFilter(selected);
            updateCounter(selected);
            $('#orgportal-kn-modal').modal('hide');
        });

        // Re-apply filter after Kanban AJAX board reload
        var board = document.getElementById('kn-board');
        if (board) {
            new MutationObserver(function () {
                var ids = getSelected();
                if (ids.length) {
                    setTimeout(function () { applyFilter(ids); }, 80);
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
