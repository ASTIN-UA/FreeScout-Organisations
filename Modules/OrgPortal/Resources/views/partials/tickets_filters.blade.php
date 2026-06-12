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
    $units         = $units  ?? collect();
    $unitId        = $unitId ?? null;
@endphp

<form method="GET" action="{{ $formAction }}" id="ct-filter-form">
    <div style="display: flex; justify-content: space-between; align-items: center; gap:8px; flex-wrap:wrap;">
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

        <div style="display:flex; align-items:center; gap:6px; flex-wrap:wrap;">

            @if($units->isNotEmpty())
            <select name="unit_id" class="form-control input-sm" style="width:auto;"
                    onchange="document.getElementById('ct-filter-form').submit()">
                <option value="">{{ __('orgportal::messages.notif_scope_org') }}</option>
                @foreach($units as $u)
                    <option value="{{ $u->id }}" {{ $unitId == $u->id ? 'selected' : '' }}>
                        {{ $u->name }}
                    </option>
                @endforeach
            </select>
            @endif

            <div class="search" style="display: flex; align-items:center;">
                <div style="position: relative">
                    <input type="text" value="{{ $searchField }}" class="form-control input"
                           name="searchField" placeholder="{{ __('orgportal::messages.search_ticket') }}" />
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
    </div>

    <input type="hidden" name="sort"  value="{{ $sortField }}" />
    <input type="hidden" name="order" value="{{ $direction }}" />

    @php
        $companyFilters = [];
        if (\Module::isActive('kanban')) {
            $cfRaw = \Option::get('orgportal.company_filters_' . $mailbox->id);
            if ($cfRaw === null) {
                $cfRaw = \Option::get('orgportal.company_filters', '[]');
            }
            $companyFilters = is_array($cfRaw) ? $cfRaw : (json_decode($cfRaw, true) ?: []);
        }
        $closed = $closed ?? false;
    @endphp
    <div style="display:flex; flex-wrap:wrap; justify-content:flex-end; align-items:center; margin-top:5px; gap:4px;">
        @foreach($companyFilters as $filter)
        @php $fid = (int)$filter['id']; @endphp
        <label class="checkbox" style="margin:0;" for="ct-f{{ $fid }}">
            <input @if(isset($status[$fid])) checked @endif
                   type="checkbox" value="{{ $fid }}"
                   id="ct-f{{ $fid }}" name="status[{{ $fid }}]" />
            {{ $filter['label'] }}
        </label>
        @endforeach
        <label class="checkbox" style="margin:0;" for="ct-closed">
            <input type="checkbox" value="1" id="ct-closed" name="closed"
                   @if($closed) checked @endif />
            {{ __('orgportal::messages.status_closed') }}
        </label>
        <button type="submit" class="btn" style="background-color:transparent; padding:2px 6px;">
            <small class="glyphicon glyphicon-refresh"></small>
        </button>
    </div>
</form>
