@extends('endusrportal::portal')

@section('title', __('orgportal::messages.company_tickets'))

@section('content')
<div class="eup-content">

    <h2>{{ $organization->name }} — {{ __('orgportal::messages.company_tickets') }}</h2>

    <p>
        <a href="{{ url()->previous() }}" class="btn btn-default btn-sm">
            &larr; {{ __('orgportal::messages.my_tickets') }}
        </a>
    </p>

    @if(session('flash_success'))
        <div class="alert alert-success">{{ session('flash_success') }}</div>
    @endif

    @if($conversations->count())
        <table class="table table-striped eup-tickets-table">
            <thead>
                <tr>
                    <th>{{ __('orgportal::messages.ticket_hash') }}</th>
                    <th>{{ __('orgportal::messages.subject') }}</th>
                    <th>{{ __('orgportal::messages.from') }}</th>
                    <th>Status</th>
                    <th>{{ __('orgportal::messages.updated') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($conversations as $conv)
                @php
                    $statusMap = [
                        \App\Conversation::STATUS_ACTIVE  => ['label-success', __('orgportal::messages.status_active')],
                        \App\Conversation::STATUS_PENDING => ['label-warning', __('orgportal::messages.status_pending')],
                        \App\Conversation::STATUS_CLOSED  => ['label-default', __('orgportal::messages.status_closed')],
                    ];
                    [$cls, $label] = $statusMap[$conv->status] ?? ['label-default', $conv->status];
                @endphp
                <tr>
                    <td>{{ $conv->number }}</td>
                    <td>
                        <a href="{{ route('orgportal.portal.ticket', $conv->id) }}">
                            {{ $conv->subject ?: __('orgportal::messages.no_subject') }}
                        </a>
                    </td>
                    <td>{{ optional($conv->customer)->getFullName(__('orgportal::messages.unknown')) }}</td>
                    <td><span class="label {{ $cls }}">{{ $label }}</span></td>
                    <td>{{ $conv->updated_at->diffForHumans() }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        {{ $conversations->links() }}
    @else
        <div class="alert alert-info">{{ __('orgportal::messages.no_org_tickets') }}</div>
    @endif

</div>
@endsection
