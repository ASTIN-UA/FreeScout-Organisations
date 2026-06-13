{{-- Notification bell — injected into EUP navbar for all authenticated portal customers --}}
<style{!! \Helper::cspNonceAttr() !!}>
#orgportal-notif-wrap { position:relative; }
#orgportal-notif-wrap > a {
    display:flex; align-items:center; justify-content:center;
    padding:15px 12px; color:#fff; position:relative;
}
#orgportal-notif-wrap > a:hover { color:#fff; opacity:.85; }
#orgportal-notif-badge {
    position:absolute; top:8px; right:4px;
    background:#e74c3c; color:#fff;
    font-size:10px; font-weight:700;
    min-width:16px; height:16px; border-radius:8px;
    padding:0 4px; line-height:16px; text-align:center;
    display:none;
}
#orgportal-notif-panel {
    display:none; position:absolute; right:-10px; top:100%;
    width:340px; background:#fff;
    border:1px solid #e0e0e0; border-radius:3px;
    box-shadow:0 4px 16px rgba(0,0,0,.12);
    z-index:9999;
}
#orgportal-notif-panel .notif-header {
    display:flex; align-items:center; justify-content:space-between;
    padding:10px 14px; border-bottom:1px solid #eee;
    font-size:13px;
}
#orgportal-notif-panel .notif-header strong { font-size:14px; }
#orgportal-notif-panel .notif-header a {
    color:#3c8dbc; font-size:12px; text-decoration:none;
}
#orgportal-notif-panel .notif-header a:hover { text-decoration:underline; }
#orgportal-notif-list { max-height:380px; overflow-y:auto; }
.notif-date-group {
    padding:5px 14px 3px;
    font-size:11px; font-weight:700; color:#aaa;
    text-transform:uppercase; letter-spacing:.5px;
    background:#fafafa; border-bottom:1px solid #f0f0f0;
}
.notif-item {
    display:flex; align-items:flex-start;
    padding:10px 14px; border-bottom:1px solid #f5f5f5;
    cursor:pointer; position:relative;
    transition:background .1s;
}
.notif-item:hover { background:#f0f7ff; }
.notif-item .notif-avatar {
    width:34px; height:34px; border-radius:50%;
    background:#bbb; color:#fff; flex-shrink:0;
    display:flex; align-items:center; justify-content:center;
    font-size:16px; margin-right:10px; margin-top:1px;
}
.notif-item .notif-body { flex:1; min-width:0; }
.notif-item .notif-title {
    font-size:13px; color:#333; line-height:1.35;
    white-space:nowrap; overflow:hidden; text-overflow:ellipsis;
}
.notif-item .notif-title strong { font-weight:600; }
.notif-item .notif-preview {
    font-size:12px; color:#888; margin-top:2px;
    white-space:nowrap; overflow:hidden; text-overflow:ellipsis;
}
.notif-item .notif-time {
    font-size:11px; color:#bbb; flex-shrink:0;
    margin-left:8px; margin-top:2px; white-space:nowrap;
}
.notif-item .notif-dismiss {
    position:absolute; top:8px; right:8px;
    font-size:14px; color:#ccc; line-height:1;
    cursor:pointer; padding:2px 4px; display:none;
}
.notif-item:hover .notif-dismiss { display:block; }
.notif-item .notif-dismiss:hover { color:#999; }
#orgportal-notif-panel .notif-empty {
    padding:24px 14px; text-align:center; color:#aaa; font-size:13px;
}
</style>
<script{!! \Helper::cspNonceAttr() !!}>
(function () {
    var POLL_INTERVAL  = 15000;
    var notifUrl       = '{{ route("orgportal.portal.notifications.index", ["mailbox_id" => $mailbox_id]) }}';
    var readAllUrl     = '{{ route("orgportal.portal.notifications.read-all", ["mailbox_id" => $mailbox_id]) }}';
    var readBaseUrl    = '{{ rtrim(route("orgportal.portal.notifications.index", ["mailbox_id" => $mailbox_id]), "/") }}';
    var csrfToken      = '{{ csrf_token() }}';
    var labelToday     = '{{ __("orgportal::messages.notif_today") }}';
    var labelYesterday = '{{ __("orgportal::messages.notif_yesterday") }}';
    var labelNoNotif   = '{{ __("orgportal::messages.no_notifications") }}';
    var labelNotif     = '{{ __("orgportal::messages.notifications") }}';
    var labelMarkAll   = '{{ __("orgportal::messages.notif_mark_all_read") }}';
    var typeLabels     = {
        'new_ticket':     '{{ __("orgportal::messages.notif_new_ticket") }}',
        'new_reply':      '{{ __("orgportal::messages.notif_new_reply") }}',
        'customer_reply': '{{ __("orgportal::messages.notif_customer_reply") }}',
    };

    // Auto-mark read when author visits EUP ticket page
    (function autoMarkRead() {
        var m = window.location.pathname.match(/\/help\/[^/]+\/ticket\/(\d+)/);
        if (!m) return;
        var convId = m[1];
        fetch(readBaseUrl + '/' + convId + '/read', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
            credentials: 'same-origin',
        }).catch(function () {});
    })();

    window.addEventListener('load', function () {
        var nav = document.querySelector('.nav.navbar-nav.navbar-right');
        if (!nav) return;

        // Bell wrapper
        var li = document.createElement('li');
        li.id = 'orgportal-notif-wrap';

        var bellLink = document.createElement('a');
        bellLink.href = '#';
        bellLink.setAttribute('aria-label', labelNotif);
        bellLink.innerHTML =
            '<svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">' +
            '<path d="M12 22c1.1 0 2-.9 2-2h-4a2 2 0 002 2zm6-6V11c0-3.07-1.64-5.64-4.5-6.32V4a1.5 1.5 0 00-3 0v.68C7.63 5.36 6 7.92 6 11v5l-2 2v1h16v-1l-2-2z"/>' +
            '</svg>' +
            '<span id="orgportal-notif-badge"></span>';

        var badge = null;

        // Panel
        var panel = document.createElement('div');
        panel.id = 'orgportal-notif-panel';

        // Header
        var header = document.createElement('div');
        header.className = 'notif-header';
        var headerTitle = document.createElement('strong');
        headerTitle.textContent = labelNotif;
        var markAllLink = document.createElement('a');
        markAllLink.href = '#';
        markAllLink.textContent = labelMarkAll;
        markAllLink.style.display = 'none';
        markAllLink.addEventListener('click', function (e) {
            e.preventDefault();
            fetch(readAllUrl, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                credentials: 'same-origin',
            }).then(function () { poll(); }).catch(function () {});
        });
        header.appendChild(headerTitle);
        header.appendChild(markAllLink);

        var list = document.createElement('div');
        list.id = 'orgportal-notif-list';

        panel.appendChild(header);
        panel.appendChild(list);

        li.appendChild(bellLink);
        li.appendChild(panel);

        nav.insertBefore(li, nav.lastElementChild);
        badge = document.getElementById('orgportal-notif-badge');

        // Toggle on bell click
        bellLink.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            panel.style.display = panel.style.display === 'block' ? 'none' : 'block';
        });

        document.addEventListener('click', function (e) {
            if (!li.contains(e.target)) {
                panel.style.display = 'none';
            }
        });

        function dateGroupLabel(key) {
            if (key === 'today') return labelToday;
            if (key === 'yesterday') return labelYesterday;
            return key;
        }

        function renderList(data) {
            list.innerHTML = '';

            if (!data.items || data.items.length === 0) {
                var empty = document.createElement('div');
                empty.className = 'notif-empty';
                empty.textContent = labelNoNotif;
                list.appendChild(empty);
                document.querySelector('#orgportal-notif-panel .notif-header strong').textContent = labelNotif;
                markAllLink.style.display = 'none';
                return;
            }

            markAllLink.style.display = '';
            document.querySelector('#orgportal-notif-panel .notif-header strong').textContent =
                labelNotif + ' ' + data.count;

            var lastGroup = null;
            data.items.forEach(function (n) {
                if (n.date_group !== lastGroup) {
                    lastGroup = n.date_group;
                    var grp = document.createElement('div');
                    grp.className = 'notif-date-group';
                    grp.textContent = dateGroupLabel(n.date_group);
                    list.appendChild(grp);
                }

                var item = document.createElement('div');
                item.className = 'notif-item';
                item.dataset.convId = n.conversation_id;
                item.dataset.url = n.url;

                var avatar = document.createElement('div');
                avatar.className = 'notif-avatar';
                avatar.innerHTML = '<svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z"/></svg>';

                var body = document.createElement('div');
                body.className = 'notif-body';

                var typeLabel = typeLabels[n.type] || n.type;
                var title = document.createElement('div');
                title.className = 'notif-title';
                title.innerHTML = '<strong>' + n.actor_name + '</strong> ' + typeLabel + ' <strong>#' + n.number + '</strong>';

                var preview = document.createElement('div');
                preview.className = 'notif-preview';
                preview.textContent = n.preview;

                body.appendChild(title);
                body.appendChild(preview);

                var time = document.createElement('div');
                time.className = 'notif-time';
                time.textContent = n.time;

                var dismiss = document.createElement('span');
                dismiss.className = 'notif-dismiss';
                dismiss.innerHTML = '&times;';
                dismiss.title = labelMarkAll;
                dismiss.addEventListener('click', function (e) {
                    e.stopPropagation();
                    markRead(n.conversation_id, item);
                });

                item.appendChild(avatar);
                item.appendChild(body);
                item.appendChild(time);
                item.appendChild(dismiss);

                item.addEventListener('click', function () {
                    markRead(n.conversation_id, null);
                    window.location.href = n.url;
                });

                list.appendChild(item);
            });
        }

        function markRead(convId, itemEl) {
            fetch(readBaseUrl + '/' + convId + '/read', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                credentials: 'same-origin',
            }).then(function () { poll(); }).catch(function () {});
            if (itemEl) itemEl.remove();
        }

        function poll() {
            fetch(notifUrl, {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                credentials: 'same-origin',
            })
            .then(function (r) { return r.ok ? r.json() : null; })
            .then(function (data) {
                if (!data) return;
                if (data.count > 0) {
                    badge.textContent = data.count > 99 ? '99+' : data.count;
                    badge.style.display = 'block';
                } else {
                    badge.style.display = 'none';
                }
                renderList(data);
            })
            .catch(function () {});
        }

        poll();
        setInterval(poll, POLL_INTERVAL);
    });
})();
</script>
