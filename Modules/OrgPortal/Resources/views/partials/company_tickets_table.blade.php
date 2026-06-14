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
<table class="table-conversations table eup-table-tickets" style="table-layout:fixed;width:100%">
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
        <th style="vertical-align:middle;text-align:center">
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
