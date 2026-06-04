@extends('endusrportal::portal')

@section('title', '#' . $conversation->number . ' ' . $conversation->subject)

@section('content')
<div class="eup-content">

    <p>
        <a href="{{ route('orgportal.portal.company-tickets') }}" class="btn btn-default btn-sm">
            &larr; {{ __('Company Tickets') }}
        </a>
    </p>

    @if(session('flash_success'))
        <div class="alert alert-success">{{ session('flash_success') }}</div>
    @endif
    @if(session('flash_error'))
        <div class="alert alert-danger">{{ session('flash_error') }}</div>
    @endif

    <h3>{{ $conversation->subject ?: __('(no subject)') }}</h3>
    <p class="text-muted">
        {{ __('From') }}: <strong>{{ optional($conversation->customer)->getFullName(__('Unknown')) }}</strong>
        &middot; #{{ $conversation->number }}
        &middot; {{ $conversation->created_at->format('Y-m-d H:i') }}
    </p>

    <hr>

    {{-- Thread messages --}}
    @foreach($threads as $thread)
    <div class="panel {{ $thread->isFromCustomer() ? 'panel-default' : 'panel-primary' }} margin-bottom">
        <div class="panel-heading">
            <small>
                @if($thread->isFromCustomer())
                    {{ optional($thread->customer)->getFullName(__('Customer')) }}
                @else
                    {{ __('Support Team') }}
                @endif
                &middot; {{ $thread->created_at->format('Y-m-d H:i') }}
            </small>
        </div>
        <div class="panel-body">
            {!! clean($thread->body) !!}
        </div>
    </div>
    @endforeach

    {{-- Reply form (manager only — already checked in controller) --}}
    <div class="panel panel-default">
        <div class="panel-heading"><strong>{{ __('Reply') }}</strong></div>
        <div class="panel-body">
            <form method="POST" action="{{ route('orgportal.portal.ticket.reply', $conversation->id) }}">
                @csrf
                <div class="form-group">
                    <textarea name="body"
                              class="form-control"
                              rows="5"
                              required
                              placeholder="{{ __('Write your reply…') }}">{{ old('body') }}</textarea>
                    @error('body')
                        <span class="help-block text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary">
                    {{ __('Send Reply') }}
                </button>
            </form>
        </div>
    </div>

</div>
@endsection
