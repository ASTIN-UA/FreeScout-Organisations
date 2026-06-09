@extends('enduserportal::layouts.portal')

@section('title', '#' . $conversation->number . ' ' . $conversation->subject)

@section('stylesheets')
<style>.eup-thread-body img { max-width: 100%; height: auto; }</style>
@endsection

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

        <div style="display:flex; align-items:flex-start; justify-content:space-between; flex-wrap:wrap; gap:12px; margin-bottom:8px;">
            <h3 style="margin:0;">{{ $conversation->subject ?: __('orgportal::messages.no_subject') }}</h3>

            {{-- Close ticket button --}}
            @if($conversation->status !== \App\Conversation::STATUS_CLOSED)
            <form method="POST"
                  action="{{ route('orgportal.portal.ticket.close', ['mailbox_id' => $mailbox_id, 'conversation_id' => $conversation->id]) }}"
                  onsubmit="return confirm('{{ __('orgportal::messages.close_ticket_confirm') }}')">
                {{ csrf_field() }}
                <button type="submit" class="btn btn-default btn-sm">
                    <i class="glyphicon glyphicon-lock"></i>
                    {{ __('orgportal::messages.close_ticket') }}
                </button>
            </form>
            @else
            <span class="label label-default" style="font-size:13px; padding:5px 10px;">
                <i class="glyphicon glyphicon-lock"></i>
                {{ __('orgportal::messages.ticket_closed_label') }}
            </span>
            @endif
        </div>

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
            <div class="eup-thread-body">{!! $thread->body !!}</div>

            {{-- Attachments --}}
            @if($thread->has_attachments && $thread->attachments->isNotEmpty())
            <div style="margin-top:10px; padding-top:8px; border-top:1px solid rgba(0,0,0,.08);">
                @foreach($thread->attachments as $attachment)
                <a href="{{ $attachment->url() }}"
                   target="_blank"
                   rel="noopener"
                   style="display:inline-flex; align-items:center; gap:4px; margin-right:12px; margin-bottom:4px; font-size:13px;">
                    <i class="glyphicon glyphicon-paperclip"></i>
                    {{ $attachment->file_name }}
                    <span style="color:#999;">({{ $attachment->getSizeName() }})</span>
                </a>
                @endforeach
            </div>
            @endif
        </div>
        @endforeach

        <div style="margin-top:24px;">
            <h4>{{ __('orgportal::messages.reply') }}</h4>
            @if($conversation->status === \App\Conversation::STATUS_CLOSED)
            <div class="alert alert-info" style="margin-bottom:12px;">
                {{ __('orgportal::messages.ticket_closed_reply_reopens') }}
            </div>
            @endif
            <form method="POST"
                  enctype="multipart/form-data"
                  action="{{ route('orgportal.portal.ticket.reply', ['mailbox_id' => $mailbox_id, 'conversation_id' => $conversation->id]) }}">
                {{ csrf_field() }}
                <div class="form-group">
                    <textarea name="body"
                              class="form-control"
                              rows="5"
                              required
                              placeholder="{{ __('orgportal::messages.write_reply') }}">{{ old('body') }}</textarea>
                </div>
                <div class="form-group">
                    <label style="font-weight:normal; color:#666; font-size:13px;">
                        <i class="glyphicon glyphicon-paperclip"></i>
                        {{ __('orgportal::messages.attach_files') }}
                        <span style="color:#999;">({{ __('orgportal::messages.attach_files_hint', ['max' => (int)ini_get('upload_max_filesize'), 'count' => 5]) }})</span>
                    </label>
                    <input type="file" name="attachments[]" multiple
                           style="display:block; margin-top:4px;">
                </div>
                <button type="submit" class="btn btn-primary">
                    {{ __('orgportal::messages.send_reply') }}
                </button>
            </form>
        </div>

    </div>
</div>
@endsection
