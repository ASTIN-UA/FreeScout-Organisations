{{--
  Default filter form for company tickets.
  Used via @includeFirst — EUP's enduserportal::partials.tickets_filters takes priority if it exists.
  Required: $mailbox, $sortField, $direction, $searchField, $status
  Optional: $formAction, $resetUrl, $showSubmitButton (default true)
--}}
@php
    $eupBase       = '/help/' . \EndUserPortal::encodeMailboxId($mailbox->id);
    $formAction    = $formAction   ?? ($eupBase . '/tickets');
    $resetUrl      = $resetUrl     ?? ($eupBase . '/tickets?sort=last_reply_at&order=' . $direction);
    $showSubmitBtn = $showSubmitButton ?? true;
@endphp

<form method="GET" action="{{ $formAction }}">
    <div style="display: flex; justify-content: space-between; align-items: center">
        @if($showSubmitBtn)
            <a href="{{ route('enduserportal.submit', ['id' => \EndUserPortal::encodeMailboxId($mailbox->id)]) }}"
               class="btn btn-primary margin-botto eup-btn-create">
                {{ \EndUserPortal::getMailboxParam($mailbox, 'text_submit') }}
            </a>
        @else
            <div></div>
        @endif

        <input type="hidden" name="sort"  value="{{ $sortField }}" />
        <input type="hidden" name="order" value="{{ $direction }}" />

        <div class="search" style="display: flex; justify-content:flex-start; align-items:center">
            <div style="position: relative">
                <input type="text" value="{{ $searchField }}" class="form-control input"
                       name="searchField" placeholder="{{ __('Шукати заявку') }}" />
                <a href="{{ $resetUrl }}" type="button" class="btn btn-light btn-sm"
                   style="position: absolute;right: 0; top:0">
                    <small class="glyphicon glyphicon-remove text-danger"></small>
                </a>
            </div>
            <button type="submit" class="btn btn-default btn-sm" style="margin-left: 5px;">
                <small class="glyphicon glyphicon-search"></small>
            </button>
        </div>
    </div>

    <input type="hidden" name="sort"  value="{{ $sortField }}" />
    <input type="hidden" name="order" value="{{ $direction }}" />

    <div style="display: flex; flex-wrap: wrap; margin-top: 5px;">
        <div><label class="checkbox" for="ct-new">
            <input @if(isset($status[1])) checked @endif type="checkbox" value="1" id="ct-new" name="status[1]" /> Нові
        </label></div>
        <div style="margin-left: 5px;"><label class="checkbox" for="ct-wait">
            <input @if(isset($status[2])) checked @endif type="checkbox" value="2" id="ct-wait" name="status[2]" /> Очікуємо відповідь від клієнта
        </label></div>
        <div style="margin-left: 5px;"><label class="checkbox" for="ct-work">
            <input @if(isset($status[3])) checked @endif type="checkbox" value="3" id="ct-work" name="status[3]" /> В роботі
        </label></div>
        <div style="margin-left: 5px;"><label class="checkbox" for="ct-dev">
            <input @if(isset($status[6])) checked @endif type="checkbox" value="6" id="ct-dev" name="status[6]" /> Передані програмісту
        </label></div>
        <div style="margin-left: 5px;"><label class="checkbox" for="ct-check">
            <input @if(isset($status[4])) checked @endif type="checkbox" value="4" id="ct-check" name="status[4]" /> На перевірці
        </label></div>
        <div style="margin-left: 5px;"><label class="checkbox" for="ct-post">
            <input @if(isset($status[5])) checked @endif type="checkbox" value="5" id="ct-post" name="status[5]" /> Відкладені
        </label></div>
        <div style="margin-left: 5px;"><label class="checkbox" for="ct-closed">
            <input @if(isset($status[10])) checked @endif type="checkbox" value="10" id="ct-closed" name="status[10]" /> Закриті
        </label></div>
        <button type="submit" class="btn" style="margin-left: 5px; background-color: transparent">
            <small class="glyphicon glyphicon-refresh"></small>
        </button>
    </div>
</form>
