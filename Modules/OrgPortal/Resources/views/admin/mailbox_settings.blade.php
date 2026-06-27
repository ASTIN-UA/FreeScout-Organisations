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

                    // Build JS memory store: { colId: { locale: label } }
                    $cfMemory = [];
                    foreach ($orderedRows as $row) {
                        $cid = $row['col']['id'];
                        $cfMemory[$cid] = $row['saved']['labels'] ?? [];
                    }
                @endphp

                {{-- Data carrier for JS (picked up by @section('javascript') below) --}}
                <div id="cf-data"
                     data-saved="{{ e(json_encode($cfMemory)) }}"
                     data-locale="{{ e($activeLocale) }}"
                     style="display:none;"></div>

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

                {{-- Hidden inputs that carry all locale labels on submit --}}
                <div id="cf-hidden-labels"></div>

                <table class="table table-condensed" id="cf-sortable-table">
                    <thead>
                        <tr>
                            <th style="width:24px;"></th>
                            <th style="width:32px;"></th>
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
                                {{-- Single visible input — JS loads/saves value for active locale --}}
                                <input type="text"
                                       class="form-control input-sm cf-label-input"
                                       data-col-id="{{ $colId }}"
                                       placeholder="{{ $col['name'] }}"
                                       value="{{ $labelsMap[$activeLocale] ?? '' }}">
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

        @endif

        @if(\Module::isActive('customfields') && !empty($cfFields))
        <div class="panel panel-default">
            <div class="panel-heading">{{ __('orgportal::messages.cf_fields_heading') }}</div>
            <div class="panel-body">
                <p class="text-muted" style="font-size:13px;">{{ __('orgportal::messages.cf_fields_hint') }}</p>

                @php
                    $cfSavedById = collect($cfFieldSettings)->keyBy('id');
                    $cfOrderedRows = [];
                    foreach ($cfFieldSettings as $s) {
                        $field = collect($cfFields)->firstWhere('id', $s['id']);
                        if ($field) {
                            $cfOrderedRows[] = ['field' => $field, 'saved' => $s, 'checked' => true];
                        }
                    }
                    foreach ($cfFields as $field) {
                        if (!$cfSavedById->has($field->id)) {
                            $cfOrderedRows[] = ['field' => $field, 'saved' => null, 'checked' => false];
                        }
                    }
                    $cfActiveLocale = $filterLocales[0] ?? 'en';
                    $cfMemoryData = [];
                    foreach ($cfOrderedRows as $row) {
                        $cfMemoryData[$row['field']->id] = $row['saved']['labels'] ?? [];
                    }
                @endphp

                <div id="cff-data"
                     data-saved="{{ e(json_encode($cfMemoryData)) }}"
                     data-locale="{{ e($cfActiveLocale) }}"
                     style="display:none;"></div>

                @if(count($filterLocales) > 1)
                <div style="display:flex;align-items:center;gap:10px;margin-bottom:14px;">
                    <label for="cff-locale-select" style="margin:0;font-weight:normal;">
                        {{ __('orgportal::messages.filter_label_language') }}:
                    </label>
                    <select id="cff-locale-select" class="form-control" style="width:auto;min-width:160px;">
                        @foreach($filterLocales as $loc)
                        <option value="{{ $loc }}" {{ $loc === $cfActiveLocale ? 'selected' : '' }}>
                            {{ $localeNames[$loc] ?? $loc }}
                        </option>
                        @endforeach
                    </select>
                </div>
                @endif

                <div id="cff-hidden-labels"></div>

                <table class="table table-condensed" id="cff-sortable-table">
                    <thead>
                        <tr>
                            <th style="width:24px;"></th>
                            <th style="width:32px;"></th>
                            <th style="color:#999;">{{ __('orgportal::messages.filter_original_name') }}</th>
                            <th>{{ __('orgportal::messages.filter_label') }}</th>
                        </tr>
                    </thead>
                    <tbody id="cff-tbody">
                        @foreach($cfOrderedRows as $row)
                        @php
                            $field     = $row['field'];
                            $checked   = $row['checked'];
                            $saved     = $row['saved'];
                            $labelsMap = $saved['labels'] ?? [];
                        @endphp
                        <tr class="cff-row" data-field-id="{{ $field->id }}">
                            <td style="cursor:move;color:#ccc;vertical-align:middle;">
                                <span class="cff-drag-handle glyphicon glyphicon-menu-hamburger"></span>
                                <input type="hidden" name="cf_field_sort[{{ $field->id }}]"
                                       class="cff-sort-input" value="0">
                            </td>
                            <td style="vertical-align:middle;">
                                <input type="checkbox"
                                       name="cf_field_ids[]"
                                       value="{{ $field->id }}"
                                       {{ $checked ? 'checked' : '' }}>
                            </td>
                            <td style="color:#555;vertical-align:middle;font-size:13px;">
                                {{ $field->name }}
                            </td>
                            <td>
                                <input type="text"
                                       class="form-control input-sm cff-label-input"
                                       data-field-id="{{ $field->id }}"
                                       placeholder="{{ $field->name }}"
                                       value="{{ $labelsMap[$cfActiveLocale] ?? '' }}">
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
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

@section('javascript')
if ($('#cf-data').length) {
    var cfSaved  = JSON.parse($('#cf-data').attr('data-saved') || '{}');
    var cfLocale = $('#cf-data').attr('data-locale') || 'en';
    // Normalize: PHP [] serializes as JSON array, but we need plain objects.
    var cfMemory = {};
    $.each(cfSaved, function(colId, labels) {
        cfMemory[String(colId)] = (labels && !Array.isArray(labels)) ? labels : {};
    });

    console.log('[CF] cfSaved:', cfSaved, '| cfLocale:', cfLocale);

    function cfSaveCurrentToMemory() {
        $('#cf-tbody .cf-row').each(function() {
            var colId = String($(this).attr('data-col-id'));
            var val   = $(this).find('.cf-label-input').val();
            if (!cfMemory[colId]) cfMemory[colId] = {};
            cfMemory[colId][cfLocale] = val;
        });
    }

    function cfLoadFromMemory(locale) {
        $('#cf-tbody .cf-row').each(function() {
            var colId = String($(this).attr('data-col-id'));
            var saved = cfMemory[colId];
            $(this).find('.cf-label-input').val(saved && saved[locale] ? saved[locale] : '');
        });
    }

    function cfBuildHiddenInputs() {
        var $c = $('#cf-hidden-labels').empty();
        $.each(cfMemory, function(colId, labels) {
            $.each(labels, function(loc, lbl) {
                if (lbl && lbl.trim() !== '') {
                    $('<input>').attr({ type: 'hidden', name: 'company_filter_labels[' + colId + '][' + loc + ']', value: lbl.trim() }).appendTo($c);
                }
            });
        });
    }

    $('#cf-locale-select').on('change', function() {
        var newLocale = $(this).val();
        cfSaveCurrentToMemory();
        console.log('[CF] switch', cfLocale, '->', newLocale, cfMemory);
        cfLocale = newLocale;
        cfLoadFromMemory(newLocale);
    });

    $('form').on('submit', function() {
        cfSaveCurrentToMemory();
        cfBuildHiddenInputs();
    });

    if ($.fn.sortable) {
        $('#cf-tbody').sortable({
            handle: '.cf-drag-handle',
            axis: 'y',
            update: function() {
                $('#cf-tbody .cf-row').each(function(i) { $(this).find('.cf-sort-input').val(i); });
            }
        });
        $('#cf-tbody .cf-row').each(function(i) { $(this).find('.cf-sort-input').val(i); });
    }
}

if ($('#cff-data').length) {
    var cffSaved  = JSON.parse($('#cff-data').attr('data-saved') || '{}');
    var cffLocale = $('#cff-data').attr('data-locale') || 'en';
    var cffMemory = {};
    $.each(cffSaved, function(fid, labels) {
        cffMemory[String(fid)] = (labels && !Array.isArray(labels)) ? labels : {};
    });

    function cffSaveCurrentToMemory() {
        $('#cff-tbody .cff-row').each(function() {
            var fid = String($(this).attr('data-field-id'));
            var val = $(this).find('.cff-label-input').val();
            if (!cffMemory[fid]) cffMemory[fid] = {};
            cffMemory[fid][cffLocale] = val;
        });
    }

    function cffLoadFromMemory(locale) {
        $('#cff-tbody .cff-row').each(function() {
            var fid   = String($(this).attr('data-field-id'));
            var saved = cffMemory[fid];
            $(this).find('.cff-label-input').val(saved && saved[locale] ? saved[locale] : '');
        });
    }

    function cffBuildHiddenInputs() {
        var $c = $('#cff-hidden-labels').empty();
        $.each(cffMemory, function(fid, labels) {
            $.each(labels, function(loc, lbl) {
                if (lbl && lbl.trim() !== '') {
                    $('<input>').attr({ type: 'hidden', name: 'cf_field_labels[' + fid + '][' + loc + ']', value: lbl.trim() }).appendTo($c);
                }
            });
        });
    }

    $('#cff-locale-select').on('change', function() {
        var newLocale = $(this).val();
        cffSaveCurrentToMemory();
        cffLocale = newLocale;
        cffLoadFromMemory(newLocale);
    });

    $('form').on('submit', function() {
        cffSaveCurrentToMemory();
        cffBuildHiddenInputs();
    });

    if ($.fn.sortable) {
        $('#cff-tbody').sortable({
            handle: '.cff-drag-handle',
            axis: 'y',
            update: function() {
                $('#cff-tbody .cff-row').each(function(i) { $(this).find('.cff-sort-input').val(i); });
            }
        });
        $('#cff-tbody .cff-row').each(function(i) { $(this).find('.cff-sort-input').val(i); });
    }
}
@endsection
