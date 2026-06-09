@extends('layouts.app')

@section('title_full', __('orgportal::messages.module_settings').' - '.$mailbox->name)

@section('body_attrs')@parent data-mailbox_id="{{ $mailbox->id }}"@endsection

@section('sidebar')
    @include('partials/sidebar_menu_toggle')
    @include('mailboxes/sidebar_menu')
@endsection

@section('content')
<div class="section-heading">
    {{ __('orgportal::messages.module_settings') }}
</div>

<div class="col-xs-12">
    @include('partials/flash_messages')

    <form method="POST" action="{{ route('orgportal.admin.mailbox-settings.save', $mailbox->id) }}">
        {{ csrf_field() }}

        <div class="panel panel-default">
            <div class="panel-heading">{{ __('orgportal::messages.display_settings') }}</div>
            <div class="panel-body">
                <div class="form-group">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="show_badge_conversation" value="1"
                                {{ $show_badge_conversation ? 'checked' : '' }}>
                            {{ __('orgportal::messages.show_badge_conversation') }}
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="show_badge_kanban" value="1"
                                {{ $show_badge_kanban ? 'checked' : '' }}>
                            {{ __('orgportal::messages.show_badge_kanban') }}
                        </label>
                    </div>
                </div>
            </div>
        </div>

        @if(\Module::isActive('kanban'))
        <div class="panel panel-default">
            <div class="panel-heading">{{ __('orgportal::messages.company_filters_heading') }}</div>
            <div class="panel-body">
                <p class="text-muted" style="font-size:13px;">{{ __('orgportal::messages.company_filters_hint') }}</p>

                @php $savedById = collect($companyFilters)->keyBy('id'); @endphp

                @if(!empty($kanbanColumns))
                <table class="table table-condensed">
                    <thead>
                        <tr>
                            <th style="width:36px;"></th>
                            <th>{{ __('orgportal::messages.filter_label') }}</th>
                            <th style="width:160px; color:#999;">{{ __('orgportal::messages.filter_board') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($kanbanColumns as $col)
                        @php
                            $checked    = isset($savedById[$col['id']]);
                            $savedLabel = $checked ? $savedById[$col['id']]['label'] : $col['name'];
                        @endphp
                        <tr>
                            <td>
                                <input type="checkbox"
                                       name="company_filter_ids[]"
                                       value="{{ $col['id'] }}"
                                       {{ $checked ? 'checked' : '' }}>
                            </td>
                            <td>
                                <input type="text"
                                       name="company_filter_labels[{{ $col['id'] }}]"
                                       class="form-control input-sm"
                                       value="{{ $savedLabel }}">
                            </td>
                            <td style="color:#999; font-size:12px; vertical-align:middle;">
                                {{ $col['board_name'] }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <p class="text-muted">{{ __('orgportal::messages.company_filters_no_boards') }}</p>
                @endif
            </div>
        </div>
        @endif

        <div class="form-group">
            <button type="submit" class="btn btn-primary">
                {{ __('orgportal::messages.save') }}
            </button>
        </div>
    </form>
</div>
@endsection
