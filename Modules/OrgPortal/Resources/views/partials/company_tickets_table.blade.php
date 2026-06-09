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
    }
@endphp

@if($conversations->count())
<table class="table-conversations table eup-table-tickets">
    <colgroup>
        <col class="conv-current">
        <col class="conv-id">
        <col class="conv-attachment">
        <col class="conv-subject">
        <col class="conv-responsible">
        <col class="conv-thread-count">
        <col class="conv-number">
        <col class="conv-customer">
        <col class="conv-customer">
        @if($kanbanActive)<col class="conv-customer">@endif
        <col class="conv-date">
    </colgroup>
    <thead>
    <tr>
        <th class="conv-current">&nbsp;</th>
        <th class="conv-id" style="width:60px;padding:0;vertical-align:middle">
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
        <th class="conv-customer" style="vertical-align:middle;text-align:center">
            <span>{{ __('orgportal::messages.author') }}</span>
        </th>
        <th class="conv-customer" style="vertical-align:middle;text-align:center">
            <span>{{ __('orgportal::messages.conv_status') }}</span>
        </th>
        @if($kanbanActive)
        <th class="conv-customer" style="vertical-align:middle;text-align:center">
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
        <tr class="conv-row @if(\EndUserPortal::hasNewReplies($conversation)) conv-active @endif"
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
            <td class="conv-subject">
                <a href="{{ $ticketUrl }}">
                    <span class="conv-fader"></span>
                    <p>{{ $conversation->getSubject() }}</p>
                </a>
            </td>
            <td class="responsible-person" style="text-align:right;padding-right:5px;padding-top:5px">
                @if($conversation->user)
                    <span>{{ $conversation->user->first_name }} {{ $conversation->user->last_name }}</span>
                @endif
            </td>
            <td class="conv-thread-count">
                <i class="conv-star glyphicon"></i>
                <a href="{{ $ticketUrl }}"><span>{{ $conversation->threads_count }}</span></a>
            </td>
            <td class="conv-number">
                <a href="{{ $ticketUrl }}">@if(\EndUserPortal::hasNewReplies($conversation))<span class="glyphicon glyphicon-envelope text-help"></span>@endif</a>
            </td>
            <td class="conv-customer" style="text-align:center">
                @if($conversation->customer && $authorUrl)
                    <a href="{{ $authorUrl }}" title="{{ __('orgportal::messages.filter_by_author') }}">
                        <small>{{ $conversation->customer->getFullName() }}</small>
                    </a>
                @elseif($conversation->customer)
                    <small>{{ $conversation->customer->getFullName() }}</small>
                @endif
            </td>
            <td class="conv-customer" style="text-align:center">
                <small class="{{ $convStatusClass }}">{{ __('orgportal::messages.' . $convStatusKey) }}</small>
            </td>
            @if($kanbanActive)
            <td class="conv-customer" style="text-align:center">
                @if($knLabel)
                    <small class="text-help">{{ $knLabel }}</small>
                @else
                    <small class="text-muted">—</small>
                @endif
            </td>
            @endif
            <td class="conv-date">
                <a href="{{ $ticketUrl }}">{{ \EndUserPortal::dateFormat($conversation->last_reply_at, 'M j, Y') }}</a>
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
