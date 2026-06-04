{{--
    Layout: extends EndUserPortal's main layout.
    The alias 'endusrportal' and section names must be verified against the
    actual EUP module installed. Update the @extends line if needed.
--}}
@extends('endusrportal::portal')

@section('title', __('Company Tickets'))

@section('content')
<div class="eup-content">

    <h2>{{ $organization->name }} — {{ __('Company Tickets') }}</h2>

    <p>
        <a href="{{ url()->previous() }}" class="btn btn-default btn-sm">
            &larr; {{ __('My Tickets') }}
        </a>
    </p>

    @if(session('flash_success'))
        <div class="alert alert-success">{{ session('flash_success') }}</div>
    @endif

    @if($conversations->count())
        <table class="table table-striped eup-tickets-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{ __('Subject') }}</th>
                    <th>{{ __('From') }}</th>
                    <th>{{ __('Status') }}</th>
                    <th>{{ __('Updated') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($conversations as $conv)
                <tr>
                    <td>{{ $conv->number }}</td>
                    <td>
                        <a href="{{ route('orgportal.portal.ticket', $conv->id) }}">
                            {{ $conv->subject ?: __('(no subject)') }}
                        </a>
                    </td>
                    <td>
                        {{ optional($conv->customer)->getFullName(__('Unknown')) }}
                    </td>
                    <td>
                        @php
                            $statusMap = [
                                \App\Conversation::STATUS_ACTIVE  => ['label-success',  __('Active')],
                                \App\Conversation::STATUS_PENDING => ['label-warning',  __('Pending')],
                                \App\Conversation::STATUS_CLOSED  => ['label-default',  __('Closed')],
                            ];
                            [$cls, $label] = $statusMap[$conv->status] ?? ['label-default', $conv->status];
                        @endphp
                        <span class="label {{ $cls }}">{{ $label }}</span>
                    </td>
                    <td>{{ $conv->updated_at->diffForHumans() }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        {{ $conversations->links() }}
    @else
        <div class="alert alert-info">{{ __('No tickets found for your organization.') }}</div>
    @endif

</div>
@endsection
