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
    $authorBase    = $baseUrl . '?' . http_build_query(array_filter([
        'searchField' => $searchField ?: null,
        'status'      => $status      ?: null,
        'sort'        => $sortField   ?: null,
        'order'       => $direction   ?: null,
    ]));

    // Pre-load Kanban statuses in one query to avoid N+1
    $knStatusMap = [];
    $knLabels    = ['', 'Нова', 'Очікуємо відповідь від клієнта', 'В роботі', 'На перевірці', 'Відкладено', 'Передано програмісту', '', '', '', 'Завершено'];
    if (\Module::isActive('kanban')) {
        $knStatusMap = \Modules\Kanban\Entities\KnCard::whereIn('conversation_id', $conversations->pluck('id'))
            ->pluck('kn_column_id', 'conversation_id')
            ->all();
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
        <col class="conv-customer">
        @if(\Module::isActive('kanban'))<col class="conv-customer">@endif
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
        <th class="conv-subject" style="vertical-align:middle"><span>{{ __('Ticket') }}</span></th>
        <th colspan="2" class="conv-responsible" style="vertical-align:middle;text-align:right">
            <span>{{ __('Відповідальний') }}</span>
        </th>
        <th class="conv-customer" style="vertical-align:middle;text-align:center">
            <span>{{ __('Автор') }}</span>
        </th>
        @if(\Module::isActive('kanban'))
        <th class="conv-customer" style="vertical-align:middle;text-align:center">
            <span>{{ __('Статус') }}</span>
        </th>
        @endif
        <th class="conv-date sortable" style="vertical-align:middle">
            <a href="{{ $sortByDateUrl }}" style="display:block;text-align:center">
                <div style="display:flex;flex-direction:row;justify-content:center;align-items:center">
                    <span>{{ __('Last Activity') }}</span>
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
            $knLabel    = $knLabels[$knColumnId] ?? '';
            $authorUrl  = $conversation->customer_id
                ? $authorBase . '&author_id=' . $conversation->customer_id
                : null;
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
            <td class="conv-customer" style="text-align:center">
                @if($conversation->customer && $authorUrl)
                    <a href="{{ $authorUrl }}" title="{{ __('Показати заявки цього автора') }}">
                        <small>{{ $conversation->customer->getFullName() }}</small>
                    </a>
                @elseif($conversation->customer)
                    <small>{{ $conversation->customer->getFullName() }}</small>
                @endif
            </td>
            @if(\Module::isActive('kanban'))
            <td class="conv-customer" style="text-align:center">
                <a href="{{ $ticketUrl }}">
                    <small class="text-help">{{ $knLabel ?: 'Відкрита' }}</small>
                </a>
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
