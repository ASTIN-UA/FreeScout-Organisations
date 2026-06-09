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

    @php
        $cfRaw = \Option::get('orgportal.company_filters_' . $mailbox->id, '[]');
        $companyFilters = is_array($cfRaw) ? $cfRaw : (json_decode($cfRaw, true) ?: []);
    @endphp
    @if(!empty($companyFilters))
    <div style="display: flex; flex-wrap: wrap; margin-top: 5px;">
        @foreach($companyFilters as $filter)
        @php $fid = (int)$filter['id']; @endphp
        <div @if(!$loop->first) style="margin-left: 5px;" @endif>
            <label class="checkbox" for="ct-f{{ $fid }}">
                <input @if(isset($status[$fid])) checked @endif
                       type="checkbox" value="{{ $fid }}"
                       id="ct-f{{ $fid }}" name="status[{{ $fid }}]" />
                {{ $filter['label'] }}
            </label>
        </div>
        @endforeach
        <button type="submit" class="btn" style="margin-left: 5px; background-color: transparent">
            <small class="glyphicon glyphicon-refresh"></small>
        </button>
    </div>
    @endif
</form>
