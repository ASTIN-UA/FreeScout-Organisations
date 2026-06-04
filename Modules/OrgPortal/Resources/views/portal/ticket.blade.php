@extends('endusrportal::portal')

@section('title', '#' . $conversation->number . ' ' . $conversation->subject)

@section('content')
<div class="eup-content">

    <p>
        <a href="{{ route('orgportal.portal.company-tickets') }}" class="btn btn-default btn-sm">
            &larr; {{ __('orgportal::messages.company_tickets') }}
        </a>
    </p>

    @if(session('flash_success'))
        <div class="alert alert-success">{{ session('flash_success') }}</div>
    @endif
    @if(session('flash_error'))
        <div class="alert alert-danger">{{ session('flash_error') }}</div>
    @endif

    <h3>{{ $conversation->subject ?: __('orgportal::messages.no_subject') }}</h3>
    <p class="text-muted">
        {{ __('orgportal::messages.from') }}: <strong>{{ optional($conversation->customer)->getFullName(__('orgportal::messages.unknown')) }}</strong>
        &middot; #{{ $conversation->number }}
        &middot; {{ $conversation->created_at->format('Y-m-d H:i') }}
    </p>

    <hr>

    @foreach($threads as $thread)
    <div class="panel {{ $thread->isFromCustomer() ? 'panel-default' : 'panel-primary' }} margin-bottom">
        <div class="panel-heading">
            <small>
                @if($thread->isFromCustomer())
                    {{ optional($thread->customer)->getFullName(__('orgportal::messages.customer')) }}
                @else
                    {{ __('orgportal::messages.support_team') }}
                @endif
                &middot; {{ $thread->created_at->format('Y-m-d H:i') }}
            </small>
        </div>
        <div class="panel-body">
            {!! clean($thread->body) !!}
        </div>
    </div>
    @endforeach

    <div class="panel panel-default">
        <div class="panel-heading"><strong>{{ __('orgportal::messages.reply') }}</strong></div>
        <div class="panel-body">
            <form method="POST" action="{{ route('orgportal.portal.ticket.reply', $conversation->id) }}">
                @csrf
                <div class="form-group">
                    <textarea name="body"
                              class="form-control"
                              rows="5"
                              required
                              placeholder="{{ __('orgportal::messages.write_reply') }}">{{ old('body') }}</textarea>
                    @error('body')
                        <span class="help-block text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary">
                    {{ __('orgportal::messages.send_reply') }}
                </button>
            </form>
        </div>
    </div>

</div>
@endsection
