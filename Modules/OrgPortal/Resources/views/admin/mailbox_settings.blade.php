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
                <div class="form-group">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="show_org_in_profile" value="1"
                                {{ $show_org_in_profile ? 'checked' : '' }}>
                            {{ __('orgportal::messages.show_org_in_profile') }}
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

                @if(!empty($kanbanColumns))
                @php
                    $savedById = collect($companyFilters)->keyBy('id');
                    // Build ordered rows: saved filters first (in saved order), then unsaved columns
                    $orderedRows = [];
                    foreach ($companyFilters as $cf) {
                        $colId = $cf['id'];
                        if (isset($savedById[$colId])) {
                            $col = collect($kanbanColumns)->firstWhere('id', $colId) ?? ['id' => $colId, 'name' => $cf['name'] ?? '', 'board_name' => ''];
                            $orderedRows[] = ['col' => $col, 'saved' => $cf, 'checked' => true];
                        }
                    }
                    foreach ($kanbanColumns as $col) {
                        if (!isset($savedById[$col['id']])) {
                            $orderedRows[] = ['col' => $col, 'saved' => null, 'checked' => false];
                        }
                    }
                    $activeLocale = $filterLocales[0] ?? 'en';
                @endphp

                {{-- Locale selector (only when more than one locale) --}}
                @if(count($filterLocales) > 1)
                <div style="display:flex;align-items:center;gap:10px;margin-bottom:14px;">
                    <label for="cf-locale-select" style="margin:0;font-weight:normal;">
                        {{ __('orgportal::messages.filter_label_language') }}:
                    </label>
                    <select id="cf-locale-select" class="form-control" style="width:auto;min-width:160px;">
                        @foreach($filterLocales as $loc)
                        <option value="{{ $loc }}" {{ $loc === $activeLocale ? 'selected' : '' }}>
                            {{ $localeNames[$loc] ?? $loc }}
                        </option>
                        @endforeach
                    </select>
                </div>
                @endif

                <table class="table table-condensed" id="cf-sortable-table">
                    <thead>
                        <tr>
                            <th style="width:24px;"></th>{{-- drag handle --}}
                            <th style="width:32px;"></th>{{-- checkbox --}}
                            <th style="color:#999;">{{ __('orgportal::messages.filter_original_name') }}</th>
                            <th>{{ __('orgportal::messages.filter_label') }}</th>
                            <th style="width:150px;color:#999;">{{ __('orgportal::messages.filter_board') }}</th>
                        </tr>
                    </thead>
                    <tbody id="cf-tbody">
                        @foreach($orderedRows as $row)
                        @php
                            $col     = $row['col'];
                            $colId   = $col['id'];
                            $checked = $row['checked'];
                            $saved   = $row['saved'];
                            // labels map for all locales
                            $labelsMap = $saved['labels'] ?? [];
                        @endphp
                        <tr class="cf-row" data-col-id="{{ $colId }}">
                            <td style="cursor:move;color:#ccc;vertical-align:middle;">
                                <span class="cf-drag-handle glyphicon glyphicon-menu-hamburger"></span>
                                <input type="hidden" name="company_filter_sort[{{ $colId }}]"
                                       class="cf-sort-input" value="0">
                            </td>
                            <td style="vertical-align:middle;">
                                <input type="checkbox"
                                       name="company_filter_ids[]"
                                       value="{{ $colId }}"
                                       {{ $checked ? 'checked' : '' }}>
                                <input type="hidden"
                                       name="company_filter_names[{{ $colId }}]"
                                       value="{{ e($col['name']) }}">
                            </td>
                            <td style="color:#555;vertical-align:middle;font-size:13px;">
                                {{ $col['name'] }}
                            </td>
                            <td>
                                {{-- One input per locale, show/hide via JS --}}
                                @foreach($filterLocales as $loc)
                                <input type="text"
                                       name="company_filter_labels[{{ $colId }}][{{ $loc }}]"
                                       class="form-control input-sm cf-label-input"
                                       data-locale="{{ $loc }}"
                                       placeholder="{{ $col['name'] }}"
                                       value="{{ $labelsMap[$loc] ?? '' }}"
                                       style="{{ $loc === $activeLocale ? '' : 'display:none;' }}">
                                @endforeach
                            </td>
                            <td style="color:#999;font-size:12px;vertical-align:middle;">
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

        <script>
        $(function() {
            // Locale switcher
            $('#cf-locale-select').on('change', function() {
                var loc = $(this).val();
                $('.cf-label-input').hide();
                $('.cf-label-input[data-locale="' + loc + '"]').show();
            });

            // Drag & drop sorting via jQuery UI sortable
            if ($.fn.sortable) {
                $('#cf-tbody').sortable({
                    handle: '.cf-drag-handle',
                    axis: 'y',
                    update: function() {
                        $('#cf-tbody .cf-row').each(function(i) {
                            $(this).find('.cf-sort-input').val(i);
                        });
                    }
                });
                // Set initial sort values
                $('#cf-tbody .cf-row').each(function(i) {
                    $(this).find('.cf-sort-input').val(i);
                });
            }
        });
        </script>
        @endif

        <div class="form-group">
            <button type="submit" class="btn btn-primary">
                {{ __('orgportal::messages.save') }}
            </button>
        </div>
    </form>
</div>
@endsection
