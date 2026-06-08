@extends('enduserportal::layouts.portal')

@section('title', '#' . $conversation->number . ' ' . $conversation->subject)

@section('content')
<div id="eup-container">
    <div class="eup-container-padded">

        <p>
            <a href="{{ route('orgportal.portal.company-tickets', ['mailbox_id' => $mailbox_id]) }}"
               class="btn btn-default btn-sm">
                &larr; {{ __('orgportal::messages.company_tickets') }}
            </a>
        </p>

        @if(session('flash_success'))
            <div class="alert alert-success">{{ session('flash_success') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger">{{ $errors->first() }}</div>
        @endif

        <h3>{{ $conversation->subject ?: __('orgportal::messages.no_subject') }}</h3>
        <div style="display:flex; align-items:center; flex-wrap:wrap; gap:12px; margin-bottom:4px;">
            <p class="text-muted" style="margin:0;">
                {{ __('orgportal::messages.from') }}:
                <strong>{{ optional($conversation->customer)->getFullName(__('orgportal::messages.unknown')) }}</strong>
                &nbsp;&middot;&nbsp; #{{ $conversation->number }}
                &nbsp;&middot;&nbsp; {{ \EndUserPortal::dateFormat($conversation->created_at) }}
            </p>
            <form method="POST"
                  action="{{ route('orgportal.portal.ticket.change-author', ['mailbox_id' => $mailbox_id, 'conversation_id' => $conversation->id]) }}"
                  style="display:flex; align-items:center; gap:6px; margin:0;">
                {{ csrf_field() }}
                <select name="new_customer_id" class="form-control input-sm" style="width:auto; min-width:160px;">
                    @foreach($orgMembers as $m)
                    <option value="{{ $m->id }}" @if($m->id === $conversation->customer_id) selected @endif>
                        {{ $m->getFullName() }}
                    </option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-default btn-sm" title="{{ __('orgportal::messages.change_author') }}">
                    {{ __('orgportal::messages.change_author') }}
                </button>
            </form>
        </div>

        <hr>

        @foreach($threads as $thread)
        @php $isCustomer = $thread->type === \App\Thread::TYPE_CUSTOMER; @endphp
        <div class="eup-thread {{ $isCustomer ? 'eup-thread-customer' : 'eup-thread-agent' }}"
             style="margin-bottom:16px; padding:12px 16px; border-radius:4px;
                    background: {{ $isCustomer ? '#f5f5f5' : '#eaf4fb' }};
                    border-left: 3px solid {{ $isCustomer ? '#ccc' : '#5b9bd5' }};">
            <div style="font-size:12px; color:#777; margin-bottom:6px;">
                @if($isCustomer)
                    <strong>{{ optional($thread->customer)->getFullName(__('orgportal::messages.customer')) }}</strong>
                @else
                    <strong>{{ __('orgportal::messages.support_team') }}</strong>
                @endif
                &nbsp;&middot;&nbsp; {{ \EndUserPortal::dateFormat($thread->created_at) }}
            </div>
            <div>{!! $thread->body !!}</div>
        </div>
        @endforeach

        <div style="margin-top:24px;">
            <h4>{{ __('orgportal::messages.reply') }}</h4>
            <form method="POST"
                  action="{{ route('orgportal.portal.ticket.reply', ['mailbox_id' => $mailbox_id, 'conversation_id' => $conversation->id]) }}">
                {{ csrf_field() }}
                <div class="form-group">
                    <textarea name="body"
                              class="form-control"
                              rows="5"
                              required
                              placeholder="{{ __('orgportal::messages.write_reply') }}">{{ old('body') }}</textarea>
                </div>
                <button type="submit" class="btn btn-primary">
                    {{ __('orgportal::messages.send_reply') }}
                </button>
            </form>
        </div>

    </div>
</div>
@endsection
