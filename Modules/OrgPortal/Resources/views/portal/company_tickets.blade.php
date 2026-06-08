@extends('enduserportal::layouts.portal')

@section('title', __('orgportal::messages.company_tickets'))

@section('content')
<div id="eup-container">

    <div class="eup-container-padded">
        <div class="heading margin-bottom text-center">
            {{ $organization->name }} — {{ __('orgportal::messages.company_tickets') }}
        </div>

        @if($authorId && $authorName)
        <div style="margin-bottom:8px">
            <span>{{ __('Автор') }}: <strong>{{ $authorName }}</strong></span>
            <a href="{{ route('orgportal.portal.company-tickets', ['mailbox_id' => $mailbox_id]) }}"
               class="btn btn-xs btn-default" style="margin-left:8px">×</a>
        </div>
        @endif

        {{--
            Use EUP's own tickets_filters partial if the installation provides one.
            Falls back to our default (orgportal::partials.tickets_filters).
            Pass showSubmitButton=false — this page is read-only for managers.
        --}}
        @includeFirst(
            ['enduserportal::partials.tickets_filters', 'orgportal::partials.tickets_filters'],
            [
                'mailbox'           => $mailbox,
                'sortField'         => $sortField,
                'direction'         => $direction,
                'searchField'       => $searchField,
                'status'            => $status,
                'showSubmitButton'  => false,
                'formAction'        => route('orgportal.portal.company-tickets', ['mailbox_id' => $mailbox_id]),
                'resetUrl'          => route('orgportal.portal.company-tickets', ['mailbox_id' => $mailbox_id]),
            ]
        )
    </div>

    @include('orgportal::partials.company_tickets_table', [
        'conversations' => $tickets,
        'mailbox_id'    => $mailbox_id,
        'sortField'     => $sortField,
        'direction'     => $direction,
        'searchField'   => $searchField,
        'status'        => $status,
        'authorId'      => $authorId,
        'authorName'    => $authorName,
    ])

</div>
@endsection
