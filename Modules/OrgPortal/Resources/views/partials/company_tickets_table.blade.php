@php
    $baseUrl = route('orgportal.portal.company-tickets', ['mailbox_id' => $mailbox_id]);

    // Build persistent params (preserve all active filters across sort/author links)
    $persistParams = array_filter([
        'searchField' => $searchField ?: null,
        'status'      => $status      ?: null,
        'author_id'   => $authorId    ?: null,
    ]);

    $sortByIdUrl   = $baseUrl . '?' . http_build_query(array_merge($persistParams, ['sort' => 'id',            'order' => $direction]));
    $sortByDateUrl = $baseUrl . '?' . http_build_query(array_merge($persistParams, ['sort' => 'last_reply_at', 'order' => $direction]));

    // Base URL for author filter links (no author_id — replaces it)
    $authorBase = $baseUrl . '?' . http_build_query(array_filter([
        'searchField' => $searchField ?: null,
        'status'      => $status      ?: null,
        'sort'        => $sortField   ?: null,
        'order'       => $direction   ?: null,
    ]));

    // Conversation status → lang key map
    $statusLangKey = [
        \App\Conversation::STATUS_ACTIVE  => 'status_active',
        \App\Conversation::STATUS_PENDING => 'status_pending',
        \App\Conversation::STATUS_CLOSED  => 'status_closed',
        \App\Conversation::STATUS_SPAM    => 'status_spam',
    ];
    $statusClass = [
        \App\Conversation::STATUS_ACTIVE  => 'text-success',
        \App\Conversation::STATUS_PENDING => 'text-warning',
        \App\Conversation::STATUS_CLOSED  => 'text-muted',
        \App\Conversation::STATUS_SPAM    => 'text-danger',
    ];
    $statusIcon = [
        \App\Conversation::STATUS_ACTIVE  => 'glyphicon-ok-circle',
        \App\Conversation::STATUS_PENDING => 'glyphicon-time',
        \App\Conversation::STATUS_CLOSED  => 'glyphicon-lock',
        \App\Conversation::STATUS_SPAM    => 'glyphicon-ban-circle',
    ];

    // Kanban: load card column IDs + column name map (one query each, no N+1)
    $kanbanActive   = \Module::isActive('kanban');
    $knStatusMap    = [];
    $knColumnNames  = [];
    if ($kanbanActive) {
        $knStatusMap = \Modules\Kanban\Entities\KnCard::whereIn('conversation_id', $conversations->pluck('id'))
            ->pluck('kn_column_id', 'conversation_id')
            ->all();

        if (!empty($knStatusMap)) {
            $boards = \Modules\Kanban\Entities\KnBoard::where(function ($q) use ($mailbox) {
                $q->where('mailbox_id', $mailbox->id)->orWhereNull('mailbox_id');
            })->get();
            foreach ($boards as $board) {
                foreach ((array) $board->columns as $col) {
                    $colId = (int) ($col['id'] ?? 0);
                    if ($colId && !isset($knColumnNames[$colId])) {
                        $knColumnNames[$colId] = $col['name'] ?? '';
                    }
                }
            }
        }

        // Override with custom labels configured in per-mailbox OrgPortal settings.
        $cfRaw = \Option::get('orgportal.company_filters_' . $mailbox->id);
        if ($cfRaw === null) {
            $cfRaw = \Option::get('orgportal.company_filters', '[]');
        }
        $companyFilters = is_array($cfRaw) ? $cfRaw : (json_decode($cfRaw, true) ?: []);
        foreach ($companyFilters as $cf) {
            $cfId = (int) ($cf['id'] ?? 0);
            if ($cfId && !empty($cf['label'])) {
                $knColumnNames[$cfId] = $cf['label'];
            }
        }
    }
@endphp

@if($conversations->count())
<style>
/* Mobile/tablet cards vs desktop table toggle (inline — EUP layout does not load module.css) */
@media (max-width: 991px) {
    .otm-desktop-table { display: none !important; }
}
@media (min-width: 992px) {
    .otm-mobile-list { display: none !important; }
}

.otm-mobile-list { margin-bottom: 16px; }
.otm-card {
    position: relative;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    padding: 11px 13px;
    margin-bottom: 10px;
    background: #fff;
}
.otm-card:hover { background: #f9fafb; }
.otm-card.otm-unread { border-left: 4px solid #337ab7; padding-left: 10px; }

/* Stretched link covers the whole card → click anywhere opens the ticket */
.otm-card-link {
    position: absolute;
    top: 0; right: 0; bottom: 0; left: 0;
    z-index: 1;
}

/* Interactive elements (author filter) sit above the stretched link */
.otm-card-row1,
.otm-subject,
.otm-card-row3 { position: relative; }
.otm-author-link { position: relative; z-index: 2; }

.otm-card-row1 { display: flex; align-items: center; gap: 8px; margin-bottom: 5px; }
.otm-num { font-weight: 700; color: #6b7280; font-size: 12px; white-space: nowrap; }
.otm-author {
    color: #6b7280; font-size: 12px; flex: 1;
    overflow: hidden; white-space: nowrap; text-overflow: ellipsis;
}
.otm-author-link { color: #337ab7; }
.otm-status { font-size: 12px; white-space: nowrap; margin-left: auto; }
.otm-status .glyphicon { font-size: 11px; margin-right: 2px; }

.otm-subject {
    font-size: 15px; font-weight: 500; color: #111827;
    line-height: 1.35; margin-bottom: 8px; word-break: break-word;
}

.otm-card-row3 { display: flex; justify-content: space-between; align-items: flex-end; }
.otm-meta-left { display: flex; flex-direction: column; gap: 3px; font-size: 12px; color: #6b7280; }
.otm-meta-left .glyphicon { font-size: 12px; margin-right: 3px; }
.otm-meta-right { text-align: right; font-size: 12px; color: #9ca3af; line-height: 1.4; }
.otm-meta-right .otm-time { display: block; }
</style>

{{-- ── Mobile card list ───────────────────────────────────────────── --}}
<div class="otm-mobile-list">
    @foreach($conversations as $conversation)
    @php
        $ticketUrl  = route('orgportal.portal.ticket', ['mailbox_id' => $mailbox_id, 'conversation_id' => $conversation->id]);
        $subjectText = $conversation->getSubject() ?: __('orgportal::messages.no_subject');
        $authorName  = $conversation->customer ? $conversation->customer->getFullName() : '';
        $authorUrl   = $conversation->customer_id ? $authorBase . '&author_id=' . $conversation->customer_id : null;
        $convStatusClass = $statusClass[$conversation->status] ?? 'text-success';
        $convStatusKey   = $statusLangKey[$conversation->status] ?? 'status_active';
        $convStatusIcon  = $statusIcon[$conversation->status] ?? 'glyphicon-ok-circle';
    @endphp
    <div class="otm-card @if($conversation->manager_has_unread ?? false) otm-unread @endif">
        <a class="otm-card-link" href="{{ $ticketUrl }}" aria-label="#{{ $conversation->number }}"></a>
        <div class="otm-card-row1">
            <span class="otm-num">#{{ $conversation->number }}</span>
            @if($authorName)
                @if($authorUrl)
                    <a class="otm-author otm-author-link" href="{{ $authorUrl }}"
                       title="{{ __('orgportal::messages.filter_by_author') }}: {{ $authorName }}">{{ $authorName }}</a>
                @else
                    <span class="otm-author">{{ $authorName }}</span>
                @endif
            @endif
            <span class="otm-status {{ $convStatusClass }}">
                <i class="glyphicon {{ $convStatusIcon }}"></i>{{ __('orgportal::messages.' . $convStatusKey) }}
            </span>
        </div>
        <div class="otm-subject">{{ $subjectText }}</div>
        <div class="otm-card-row3">
            <div class="otm-meta-left">
                <span><i class="glyphicon glyphicon-comment"></i>{{ $conversation->threads_count }}</span>
                @if($conversation->author_has_unread ?? false)
                    <span title="{{ __('orgportal::messages.author_not_read') }}"><i class="glyphicon glyphicon-eye-close"></i></span>
                @endif
            </div>
            <div class="otm-meta-right">
                <span class="otm-date">{{ \EndUserPortal::dateFormat($conversation->last_reply_at, 'M j, Y') }}</span>
                <span class="otm-time">{{ \EndUserPortal::dateFormat($conversation->last_reply_at, 'H:i') }}</span>
            </div>
        </div>
    </div>
    @endforeach
</div>

{{-- ── Desktop table ──────────────────────────────────────────────── --}}
<table class="table-conversations table eup-table-tickets otm-desktop-table" style="table-layout:fixed;width:100%">
    <colgroup>
        <col style="width:6px">
        <col style="width:58px">
        <col style="width:24px">
        <col>{{-- subject: takes all remaining space --}}
        <col style="width:120px">
        <col style="width:36px">
        <col style="width:26px">
        <col style="width:110px">
        <col style="width:76px">
        @if($kanbanActive)<col style="width:110px">@endif
        <col style="width:120px">
    </colgroup>
    <thead>
    <tr>
        <th class="conv-current">&nbsp;</th>
        <th class="conv-id" style="padding:0;vertical-align:middle">
            <a href="{{ $sortByIdUrl }}" style="display:block;text-align:center">
                <div style="display:flex;flex-direction:row;justify-content:center;align-items:center">
                    <span>#</span>
                    <span style="margin-left:5px;display:flex;flex-direction:column;justify-content:center;align-items:center">
                        <span class="caret-up" style="margin-bottom:2px"></span>
                        <span class="caret"></span>
                    </span>
                </div>
            </a>
        </th>
        <th class="conv-attachment" style="vertical-align:middle">&nbsp;</th>
        <th class="conv-subject" style="vertical-align:middle"><span>{{ __('orgportal::messages.subject') }}</span></th>
        <th colspan="2" class="conv-responsible" style="vertical-align:middle;text-align:right">
            <span>{{ __('orgportal::messages.responsible') }}</span>
        </th>
        <th class="conv-number" style="vertical-align:middle">&nbsp;</th>
        <th class="conv-author" style="vertical-align:middle;text-align:center">
            <span>{{ __('orgportal::messages.author') }}</span>
        </th>
        <th style="vertical-align:middle;text-align:center">
            <span>{{ __('orgportal::messages.conv_status') }}</span>
        </th>
        @if($kanbanActive)
        <th style="vertical-align:middle;text-align:center">
            <span>{{ __('orgportal::messages.kanban_state') }}</span>
        </th>
        @endif
        <th class="conv-date sortable" style="vertical-align:middle">
            <a href="{{ $sortByDateUrl }}" style="display:block;text-align:center">
                <div style="display:flex;flex-direction:row;justify-content:center;align-items:center">
                    <span>{{ __('orgportal::messages.updated') }}</span>
                    <span style="margin-left:5px;display:flex;flex-direction:column;justify-content:center;align-items:center">
                        <span class="caret-up" style="margin-bottom:2px"></span>
                        <span class="caret"></span>
                    </span>
                </div>
            </a>
        </th>
    </tr>
    </thead>
    <tbody>
        @foreach($conversations as $conversation)
        @php
            $ticketUrl  = route('orgportal.portal.ticket', ['mailbox_id' => $mailbox_id, 'conversation_id' => $conversation->id]);
            $knColumnId = $knStatusMap[$conversation->id] ?? 0;
            $knLabel    = $knColumnId ? ($knColumnNames[$knColumnId] ?? '') : '';
            $authorUrl  = $conversation->customer_id
                ? $authorBase . '&author_id=' . $conversation->customer_id
                : null;
            $convStatusKey   = $statusLangKey[$conversation->status]   ?? 'status_active';
            $convStatusClass = $statusClass[$conversation->status] ?? 'text-success';
        @endphp
        <tr class="conv-row @if($conversation->manager_has_unread ?? false) conv-active @endif"
            data-conversation_id="{{ $conversation->id }}">
            <td class="conv-current"></td>
            <td class="conver-number">#{{ $conversation->number }}</td>
            <td class="conv-attachment">
                @if($conversation->has_attachments)
                    <i class="glyphicon glyphicon-paperclip"></i>
                @else
                    &nbsp;
                @endif
            </td>
            @php
                $subjectText  = $conversation->getSubject() ?: __('orgportal::messages.no_subject');
                $responsibleName = $conversation->user
                    ? trim($conversation->user->first_name . ' ' . $conversation->user->last_name)
                    : '';
                $authorName = $conversation->customer ? $conversation->customer->getFullName() : '';
            @endphp
            <td class="conv-subject" style="overflow:hidden;max-width:0;">
                <a href="{{ $ticketUrl }}"
                   title="{{ $subjectText }}"
                   style="display:block;overflow:hidden;white-space:nowrap;text-overflow:ellipsis;">
                    <span class="conv-fader"></span>
                    {{ $subjectText }}
                </a>
            </td>
            <td class="responsible-person"
                style="text-align:right;padding-right:5px;padding-top:5px;overflow:hidden;white-space:nowrap;text-overflow:ellipsis;max-width:0;"
                title="{{ $responsibleName }}">
                @if($responsibleName)
                    <span>{{ $responsibleName }}</span>
                @endif
            </td>
            <td class="conv-thread-count">
                <i class="conv-star glyphicon"></i>
                <a href="{{ $ticketUrl }}"><span>{{ $conversation->threads_count }}</span></a>
            </td>
            <td class="conv-number">
                <a href="{{ $ticketUrl }}">
                    @if($conversation->author_has_unread ?? false)
                        <span class="glyphicon glyphicon-eye-close text-muted" title="{{ __('orgportal::messages.author_not_read') }}"></span>
                    @endif
                </a>
            </td>
            <td style="text-align:center;overflow:hidden;white-space:nowrap;text-overflow:ellipsis;max-width:0;"
                title="{{ $authorName }}">
                @if($conversation->customer && $authorUrl)
                    <a href="{{ $authorUrl }}" title="{{ __('orgportal::messages.filter_by_author') }}: {{ $authorName }}">
                        <small>{{ $authorName }}</small>
                    </a>
                @elseif($conversation->customer)
                    <small>{{ $authorName }}</small>
                @endif
            </td>
            <td style="text-align:center;">
                @if($conversation->status === \App\Conversation::STATUS_CLOSED)
                    <small class="text-muted"><i class="glyphicon glyphicon-lock"></i> {{ __('orgportal::messages.status_closed') }}</small>
                @elseif($conversation->status === \App\Conversation::STATUS_PENDING)
                    <small class="{{ $convStatusClass }}"><i class="glyphicon glyphicon-time"></i> {{ __('orgportal::messages.status_pending') }}</small>
                @elseif($conversation->status === \App\Conversation::STATUS_SPAM)
                    <small class="{{ $convStatusClass }}"><i class="glyphicon glyphicon-ban-circle"></i> {{ __('orgportal::messages.status_spam') }}</small>
                @else
                    <small class="{{ $convStatusClass }}">{{ __('orgportal::messages.status_active') }}</small>
                @endif
            </td>
            @if($kanbanActive)
            <td style="text-align:center;overflow:hidden;white-space:nowrap;text-overflow:ellipsis;max-width:0;"
                title="{{ $knLabel }}">
                @if($knLabel)
                    <small class="text-help">{{ $knLabel }}</small>
                @else
                    <small class="text-muted">—</small>
                @endif
            </td>
            @endif
            <td class="conv-date">
                <a href="{{ $ticketUrl }}">{{ \EndUserPortal::dateFormat($conversation->last_reply_at, 'M j, Y') }}<br><small class="text-muted">{{ \EndUserPortal::dateFormat($conversation->last_reply_at, 'H:i') }}</small></a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
{{ $conversations->links() }}

@else
<div class="empty-content">
    <i class="glyphicon glyphicon-ok text-larger"></i>
    <p>{{ __('orgportal::messages.no_org_tickets') }}</p>
</div>
@endif
