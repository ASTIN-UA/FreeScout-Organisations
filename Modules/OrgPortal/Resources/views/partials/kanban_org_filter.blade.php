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
                <style>
                    #orgportal-kn-list { border: 1px solid #e5e5e5; border-radius: 4px; padding: 4px 0; }
                    #orgportal-kn-list .orgportal-kn-org-row { padding: 0; margin: 0; }
                    #orgportal-kn-list .orgportal-kn-org-row label.checkbox {
                        display: block; margin: 0; padding: 6px 12px 6px 30px; font-weight: normal;
                    }
                    #orgportal-kn-list .orgportal-kn-org-row label.checkbox:hover { background: #f5f5f5; }
                    #orgportal-kn-list .orgportal-kn-org-row input[type=checkbox] { margin-left: -20px; }
                    #orgportal-kn-footer { display: flex; gap: 8px; justify-content: center; }
                </style>

                <div class="row-container">
                    <input type="text" class="form-control input-sm" id="orgportal-kn-search"
                           placeholder="{{ __('orgportal::messages.kanban_filter_org_search_placeholder') }}"
                           autocomplete="off" style="margin-bottom:10px;">

                    <div id="orgportal-kn-list" style="max-height:280px;overflow-y:auto;">
                        @foreach($organizations as $org)
                        <div class="orgportal-kn-org-row" data-name="{{ mb_strtolower($org->name) }}">
                            <label class="checkbox" for="orgportal_kn_org_{{ $org->id }}">
                                <input type="checkbox"
                                       name="orgportal_org[]"
                                       value="{{ $org->id }}"
                                       id="orgportal_kn_org_{{ $org->id }}">
                                {{ $org->name }}
                            </label>
                        </div>
                        @endforeach
                        <p class="text-muted orgportal-kn-no-match" style="font-size:12px;display:none;padding:6px 12px;margin:0;">
                            {{ __('orgportal::messages.kanban_filter_org_no_results') }}
                        </p>
                    </div>

                    <div id="orgportal-kn-footer" class="margin-top margin-bottom-10">
                        <button class="btn btn-primary btn-sm" id="orgportal-kn-apply">
                            {{ __('Apply Filter') }}
                        </button>
                        <button class="btn btn-default btn-sm" id="orgportal-kn-reset">
                            {{ __('orgportal::messages.kanban_filter_org_reset') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script{!! \Helper::cspNonceAttr() !!}>
(function () {
    // Real server-side filtering, not client-side hide/show: our injected
    // <li> below carries the exact same data-param-name/data-param-value
    // contract Kanban's own Status/Tag/Assignee filter <li>s use inside
    // .kn-param[data-param="filters"]. Kanban's own knGetParams() (see
    // Modules/Kanban/Public/js/module.js) walks every <li> in that dropdown
    // and sends it as kn[filters][<name>][] on every AJAX call it makes —
    // board load, sort/group-by reload, "load more", "Show Closed"
    // pagination, all of it, since they all call knGetParams() too. A
    // matching Eloquent global scope on App\Conversation
    // (OrgPortalServiceProvider::registerKanbanHooks()) reads
    // kn.filters.org off the request and applies the org filter to
    // whatever query Kanban is running — so this behaves exactly like
    // Kanban's native filters (including correctly filtering paginated/
    // closed cards), without a single line of Kanban itself being touched.
    //
    // Not persisted across page loads on purpose — a filter left applied
    // from a previous visit silently hides most of the board (see debug
    // session 2026-07-07), which is worse than just having to reselect it.
    var appliedIds = [];
    var FILTERS_SEPARATOR = '|'; // must match Kanban::FILTERS_SEPARATOR / KN_FILTERS_SEPARATOR

    function selectedIds() {
        var ids = [];
        document.querySelectorAll('#orgportal-kn-modal input[type=checkbox]:checked').forEach(function (cb) {
            ids.push(cb.value);
        });
        return ids;
    }

    // Move org badges to after the thread counter in each card
    function repositionBadges() {
        document.querySelectorAll('.kn-card-name').forEach(function (nameDiv) {
            var badge   = nameDiv.querySelector('.orgportal-org-badge');
            var counter = nameDiv.querySelector('.kn-conv-counter');
            if (badge && counter && counter.nextSibling !== badge) {
                counter.parentNode.insertBefore(badge, counter.nextSibling);
            }
        });
    }

    // Instant client-side filtering of the already-rendered org list in the
    // modal — no server round-trip needed, the full org list is small
    // enough to just hide/show rows as the user types.
    function filterList(query) {
        query = query.trim().toLowerCase();
        var rows = document.querySelectorAll('.orgportal-kn-org-row');
        var anyVisible = false;
        rows.forEach(function (row) {
            var match = !query || row.dataset.name.indexOf(query) !== -1;
            row.style.display = match ? '' : 'none';
            if (match) anyVisible = true;
        });
        var noMatch = document.querySelector('.orgportal-kn-no-match');
        if (noMatch) noMatch.style.display = anyVisible ? 'none' : '';
    }

    function inject() {
        if (document.querySelector('.orgportal-kn-filter-li')) return;

        var filterDropdown = document.querySelector('.kn-param[data-param="filters"] .dropdown-menu');
        if (!filterDropdown) return;

        // Build <li> matching Kanban's own filter <li> contract exactly
        // (data-param-name/data-param-value) so knGetParams() picks it up.
        var li = document.createElement('li');
        li.className = 'orgportal-kn-filter-li';
        li.setAttribute('data-param-name', 'org');
        li.setAttribute('data-param-value', '');

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

        repositionBadges();

        var searchInput = document.getElementById('orgportal-kn-search');
        var $li = $(li);

        function applyToLi(ids) {
            $li.attr('data-param-value', ids.join(FILTERS_SEPARATOR));
            $li.toggleClass('active', ids.length > 0);
            counter.textContent = ids.length ? '(' + ids.length + ')' : '';
        }

        // Click → close dropdown, open modal, restore checkboxes to match
        // what's currently applied (not blank) so reopening to add one more
        // org doesn't lose the existing selection.
        a.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();

            var $dropdown = $li.closest('.dropdown');
            if ($dropdown.length) $dropdown.removeClass('open');

            document.querySelectorAll('#orgportal-kn-modal input[type=checkbox]').forEach(function (cb) {
                cb.checked = appliedIds.indexOf(cb.value) !== -1;
            });
            searchInput.value = '';
            filterList('');

            $('#orgportal-kn-modal').modal('show');
            setTimeout(function () { searchInput.focus(); }, 300);
        });

        searchInput.addEventListener('input', function () {
            filterList(searchInput.value);
        });

        document.getElementById('orgportal-kn-apply').addEventListener('click', function () {
            appliedIds = selectedIds();
            applyToLi(appliedIds);
            $('#orgportal-kn-modal').modal('hide');
            if (typeof knShow === 'function') knShow();
        });

        document.getElementById('orgportal-kn-reset').addEventListener('click', function () {
            document.querySelectorAll('#orgportal-kn-modal input[type=checkbox]').forEach(function (cb) {
                cb.checked = false;
            });
            appliedIds = [];
            applyToLi(appliedIds);
            if (typeof knShow === 'function') knShow();
        });

        // The filter <li> lives outside #kn-board so knShow()'s board-only
        // reload doesn't wipe it — only badge repositioning needs to run
        // again after each reload, the filter itself is already server-side.
        var board = document.getElementById('kn-board');
        if (board) {
            var observer = new MutationObserver(function () {
                observer.disconnect();
                setTimeout(function () {
                    repositionBadges();
                    observer.observe(board, { childList: true, subtree: true });
                }, 150);
            });
            observer.observe(board, { childList: true, subtree: true });
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', inject);
    } else {
        inject();
    }
})();
</script>
