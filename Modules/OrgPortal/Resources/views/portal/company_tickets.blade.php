@extends('enduserportal::layouts.portal')

@section('title', __('orgportal::messages.company_tickets'))

@section('content')
<div id="eup-container">
    <div class="eup-container-padded">

        <div class="heading margin-bottom text-center">
            {{ $organization->name }} — {{ __('orgportal::messages.company_tickets') }}
        </div>

        @if(session('flash_success'))
            <div class="alert alert-success">{{ session('flash_success') }}</div>
        @endif

        @if($conversations->count())
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ __('orgportal::messages.subject') }}</th>
                        <th>{{ __('orgportal::messages.from') }}</th>
                        <th>{{ __('orgportal::messages.updated') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($conversations as $conv)
                    @php
                        $statusMap = [
                            \App\Conversation::STATUS_ACTIVE  => 'label-success',
                            \App\Conversation::STATUS_PENDING => 'label-warning',
                            \App\Conversation::STATUS_CLOSED  => 'label-default',
                        ];
                        $statusCls = $statusMap[$conv->status] ?? 'label-default';
                        $statusLabel = \EndUserPortal::getStatusName($conv);
                    @endphp
                    <tr>
                        <td>
                            <span class="label {{ $statusCls }}">{{ $statusLabel }}</span>
                            @if(\EndUserPortal::hasNewReplies($conv))
                                <span class="label label-warning">{{ __('New') }}</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('orgportal.portal.ticket', ['mailbox_id' => $mailbox_id, 'conversation_id' => $conv->id]) }}">
                                {{ $conv->subject ?: __('orgportal::messages.no_subject') }}
                            </a>
                        </td>
                        <td>{{ optional($conv->customer)->getFullName(__('orgportal::messages.unknown')) }}</td>
                        <td>{{ \EndUserPortal::dateFormat($conv->updated_at) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $conversations->links() }}
        @else
            <div class="alert alert-info">{{ __('orgportal::messages.no_org_tickets') }}</div>
        @endif

    </div>
</div>
@endsection
