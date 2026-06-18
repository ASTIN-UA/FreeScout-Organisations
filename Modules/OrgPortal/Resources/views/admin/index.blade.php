@extends('layouts.app')

@section('title', __('orgportal::messages.organizations'))

@section('content')
<div class="section-heading">
    {{ __('orgportal::messages.organizations') }}
</div>

@php
    $validTabs = ['organizations', 'templates'];
    if ($isAdmin) $validTabs[] = 'system';
    $activeTab = request()->input('tab', 'organizations');
    if (!in_array($activeTab, $validTabs)) $activeTab = 'organizations';
@endphp

<div class="container">
    <div class="row">
        <div class="col-md-12">

            @include('partials/flash_messages')

            <ul class="nav nav-tabs" role="tablist" style="margin-bottom:0;display:flex;flex-wrap:nowrap;overflow-x:auto;-webkit-overflow-scrolling:touch;white-space:nowrap;">
                <li role="presentation" class="{{ $activeTab === 'organizations' ? 'active' : '' }}">
                    <a href="#tab-organizations" role="tab" data-toggle="tab">
                        {{ __('orgportal::messages.organizations') }}
                    </a>
                </li>
                @if($canManageTemplates)
                <li role="presentation" class="{{ $activeTab === 'templates' ? 'active' : '' }}">
                    <a href="#tab-templates" role="tab" data-toggle="tab">
                        {{ __('orgportal::messages.tpl_tab_title') }}
                    </a>
                </li>
                @endif
                @if($isAdmin)
                <li role="presentation" class="{{ $activeTab === 'system' ? 'active' : '' }}">
                    <a href="#tab-system" role="tab" data-toggle="tab">
                        {{ __('orgportal::messages.system_tab_title') }}
                    </a>
                </li>
                @endif
            </ul>

            <div class="tab-content" style="padding-top:12px;">

                {{-- ── Organizations ─────────────────────────────────────── --}}
                <div role="tabpanel" class="tab-pane {{ $activeTab === 'organizations' ? 'active' : '' }}" id="tab-organizations">

                    <div style="display:flex;align-items:center;gap:8px;margin-bottom:10px;flex-wrap:wrap;">
                        <a href="{{ route('orgportal.admin.create') }}" class="btn btn-primary btn-sm">
                            <i class="glyphicon glyphicon-plus"></i> {{ __('orgportal::messages.new_organization') }}
                        </a>
                        <div class="btn-group" data-toggle="buttons" id="orgportal-status-filter">
                            <label class="btn btn-sm btn-default active">
                                <input type="radio" name="org-status" value="active" checked> {{ __('orgportal::messages.filter_active') }}
                            </label>
                            <label class="btn btn-sm btn-default">
                                <input type="radio" name="org-status" value="inactive"> {{ __('orgportal::messages.filter_inactive') }}
                            </label>
                            <label class="btn btn-sm btn-default">
                                <input type="radio" name="org-status" value="all"> {{ __('orgportal::messages.filter_all') }}
                            </label>
                        </div>
                        <div style="flex:1;min-width:180px;max-width:320px;">
                            <input type="text"
                                   id="orgportal-org-search"
                                   class="form-control input-sm"
                                   placeholder="{{ __('orgportal::messages.search_organizations') }}"
                                   autocomplete="off">
                        </div>
                    </div>

                    @if($organizations->count())
                        <div style="overflow-x:auto;-webkit-overflow-scrolling:touch;">
                        <table class="table table-striped" id="orgportal-org-table" style="min-width:760px;white-space:nowrap;">
                            <thead>
                                <tr>
                                    <th>{{ __('orgportal::messages.name') }}</th>
                                    <th>{{ __('orgportal::messages.mailbox') }}</th>
                                    <th>{{ __('orgportal::messages.members') }}</th>
                                    <th>{{ __('orgportal::messages.col_tickets') }}</th>
                                    @if($tagsModuleActive)
                                    <th>{{ __('orgportal::messages.col_tags') }}</th>
                                    @endif
                                    <th>{{ __('orgportal::messages.col_status') }}</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($organizations as $org)
                                <tr data-org-name="{{ mb_strtolower($org->name) }}" data-is-active="{{ $org->is_active ? '1' : '0' }}">
                                    <td>
                                        <a href="{{ route('orgportal.admin.edit', $org->id) }}">
                                            {{ $org->name }}
                                        </a>
                                    </td>
                                    <td>
                                        @if($org->mailbox)
                                            <span class="label label-default">{{ $org->mailbox->name }}</span>
                                        @else
                                            <span class="text-muted">{{ __('orgportal::messages.global_scope') }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $org->members_count }}</td>
                                    <td>
                                        @if($org->conversations_count > 0)
                                            <a href="{{ url(\Helper::getSubdirectory() . 'search') . '?' . http_build_query(['f' => ['organization' => $org->id]]) }}" target="_blank">
                                                {{ $org->conversations_count }}
                                            </a>
                                        @else
                                            <span class="text-muted">0</span>
                                        @endif
                                    </td>
                                    @if($tagsModuleActive)
                                    <td>
                                        @if(!empty($org->has_tags))
                                            <span class="text-success" title="{{ __('orgportal::messages.col_tags') }}">
                                                <i class="glyphicon glyphicon-ok"></i>
                                            </span>
                                        @else
                                            <span class="text-danger">
                                                <i class="glyphicon glyphicon-remove"></i>
                                            </span>
                                        @endif
                                    </td>
                                    @endif
                                    <td>
                                        @if($org->is_active)
                                            <span class="label label-success">{{ __('orgportal::messages.org_status_active') }}</span>
                                        @else
                                            <span class="label label-default">{{ __('orgportal::messages.org_status_inactive') }}</span>
                                        @endif
                                    </td>
                                    <td class="text-right" style="white-space:nowrap;">
                                        {{-- Tickets button --}}
                                        <a href="{{ url(\Helper::getSubdirectory() . 'search') . '?' . http_build_query(['f' => ['organization' => $org->id]]) }}"
                                           target="_blank"
                                           class="btn btn-xs btn-default"
                                           title="{{ __('orgportal::messages.btn_tickets') }}">
                                            <i class="glyphicon glyphicon-list-alt"></i>
                                            {{ __('orgportal::messages.btn_tickets') }}
                                        </a>

                                        {{-- Edit button --}}
                                        <a href="{{ route('orgportal.admin.edit', $org->id) }}"
                                           class="btn btn-xs btn-default">
                                            {{ __('orgportal::messages.edit') }}
                                        </a>

                                        @if(auth()->user()->isAdmin())
                                        {{-- Deactivate / Activate button --}}
                                        @if($snapshotEnabled)
                                            <form method="POST"
                                                  action="{{ route('orgportal.admin.deactivate', $org->id) }}"
                                                  style="display:inline;"
                                                  onsubmit="return confirm('{{ $org->is_active ? __('orgportal::messages.confirm_deactivate_org') : __('orgportal::messages.confirm_activate_org') }}')">
                                                {{ csrf_field() }}
                                                <button type="submit"
                                                        class="btn btn-xs {{ $org->is_active ? 'btn-warning' : 'btn-success' }}">
                                                    {{ $org->is_active ? __('orgportal::messages.btn_deactivate') : __('orgportal::messages.btn_activate') }}
                                                </button>
                                            </form>
                                        @else
                                            <button type="button"
                                                    class="btn btn-xs btn-default"
                                                    disabled
                                                    title="{{ __('orgportal::messages.deactivate_no_snapshot') }}"
                                                    data-toggle="tooltip" data-placement="top">
                                                {{ __('orgportal::messages.btn_deactivate') }}
                                            </button>
                                        @endif

                                        {{-- Delete button: only if no members AND no tickets --}}
                                        @if($org->members_count == 0 && $org->conversations_count == 0)
                                        <form method="POST"
                                              action="{{ route('orgportal.admin.destroy', $org->id) }}"
                                              style="display:inline;"
                                              onsubmit="return confirm('{{ __('orgportal::messages.confirm_delete_org') }}')">
                                            {{ csrf_field() }}
                                            {{ method_field('DELETE') }}
                                            <button type="submit" class="btn btn-xs btn-danger">
                                                {{ __('orgportal::messages.delete') }}
                                            </button>
                                        </form>
                                        @endif
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        </div>
                        <div id="orgportal-org-no-results" style="display:none;" class="alert alert-info">
                            {{ __('orgportal::messages.no_organizations') }}
                        </div>
                        {{ $organizations->links() }}
                    @else
                        <div class="alert alert-info">
                            {{ __('orgportal::messages.no_organizations') }}
                            <a href="{{ route('orgportal.admin.create') }}">{{ __('orgportal::messages.create_one') }}</a>.
                        </div>
                    @endif

                </div>

                {{-- ── Notification templates ─────────────────────────────── --}}
                @if($canManageTemplates)
                <div role="tabpanel" class="tab-pane {{ $activeTab === 'templates' ? 'active' : '' }}" id="tab-templates">

                    @php
                        $macros = [
                            '{manager_name}'     => __('orgportal::messages.macro_manager_name'),
                            '{author_name}'      => __('orgportal::messages.macro_author_name'),
                            '{org_name}'         => __('orgportal::messages.macro_org_name'),
                            '{unit_name}'        => __('orgportal::messages.macro_unit_name'),
                            '{subject}'          => __('orgportal::messages.macro_subject'),
                            '{ticket_number}'    => __('orgportal::messages.macro_ticket_number'),
                            '{ticket_url}'       => __('orgportal::messages.macro_ticket_url'),
                            '{created_date}'     => __('orgportal::messages.macro_created_date'),
                            '{created_time}'     => __('orgportal::messages.macro_created_time'),
                            '{created_datetime}' => __('orgportal::messages.macro_created_datetime'),
                            '{reply_date}'       => __('orgportal::messages.macro_reply_date'),
                            '{reply_time}'       => __('orgportal::messages.macro_reply_time'),
                            '{reply_datetime}'   => __('orgportal::messages.macro_reply_datetime'),
                            '{reply_text}'       => __('orgportal::messages.macro_reply_text'),
                            '{ticket_text}'      => __('orgportal::messages.macro_ticket_text'),
                        ];
                    @endphp

                    <form method="POST" action="{{ route('orgportal.admin.settings.save') }}">
                        {{ csrf_field() }}

                        @foreach($tplEvents as $eKey => $eLabel)
                        <div class="panel panel-default">
                            <div class="panel-heading"
                                 style="cursor:pointer;user-select:none;display:flex;align-items:center;gap:8px;"
                                 data-toggle="collapse"
                                 data-target="#tpl-panel-{{ $eKey }}">
                                <span class="glyphicon glyphicon-chevron-right" style="flex:0 0 auto;font-size:12px;transition:transform .15s ease;"></span>
                                <span>
                                    <strong>{{ __('orgportal::messages.tpl_heading') }}: {{ $eLabel }}</strong>
                                    <span class="text-muted" style="font-size:12px;margin-left:8px;">
                                        {{ __('orgportal::messages.tpl_fallback_hint') }}
                                    </span>
                                </span>
                            </div>
                            <div id="tpl-panel-{{ $eKey }}" class="panel-collapse collapse">
                                <div class="panel-body">

                                    {{-- Subject --}}
                                    <div class="form-group">
                                        <label>{{ __('orgportal::messages.tpl_subject') }}</label>
                                        <div style="display:flex;gap:6px;align-items:center;">
                                            <input type="text"
                                                   id="tpl_{{ $eKey }}_subject"
                                                   name="tpl_{{ $eKey }}_subject"
                                                   class="form-control"
                                                   value="{{ $tplTemplates[$eKey]['subject'] }}"
                                                   placeholder="{{ __('orgportal::messages.tpl_subject_placeholder') }}">
                                            <select class="form-control orgportal-macro-subject"
                                                    data-target="tpl_{{ $eKey }}_subject"
                                                    style="width:180px;flex-shrink:0;">
                                                <option value="">{{ __('orgportal::messages.tpl_insert_macro') }}</option>
                                                @foreach($macros as $macro => $macroLabel)
                                                    <option value="{{ $macro }}">{{ $macroLabel }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    {{-- Load default --}}
                                    <div class="form-group">
                                        <button type="button"
                                                class="btn btn-default btn-xs orgportal-load-default"
                                                data-event="{{ $eKey }}">
                                            <i class="glyphicon glyphicon-refresh"></i>
                                            {{ __('orgportal::messages.tpl_load_default') }}
                                        </button>
                                    </div>

                                    {{-- Body --}}
                                    <div class="form-group">
                                        <label>{{ __('orgportal::messages.tpl_body') }}</label>
                                        <div style="margin-bottom:6px;">
                                            <select class="form-control orgportal-macro-body"
                                                    data-target="tpl_{{ $eKey }}_body"
                                                    style="width:200px;text-align:left;">
                                                <option value="">{{ __('orgportal::messages.tpl_insert_macro') }}</option>
                                                @foreach($macros as $macro => $macroLabel)
                                                    <option value="{{ $macro }}">{{ $macroLabel }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <textarea id="tpl_{{ $eKey }}_body"
                                                  name="tpl_{{ $eKey }}_body"
                                                  class="form-control orgportal-editor"
                                                  rows="6">{{ $tplTemplates[$eKey]['body'] }}</textarea>
                                    </div>

                                </div>
                            </div>
                        </div>
                        @endforeach

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                {{ __('orgportal::messages.save') }}
                            </button>
                        </div>

                    </form>

                </div>
                @endif

                {{-- ── System ─────────────────────────────────────────────── --}}
                @if($isAdmin)
                <div role="tabpanel" class="tab-pane {{ $activeTab === 'system' ? 'active' : '' }}" id="tab-system">

                    {{-- Single form for all system settings --}}
                    <form method="POST" action="{{ route('orgportal.admin.system.save') }}" id="system-settings-form">
                        {{ csrf_field() }}

                        {{-- ═══════════════════════════════════════════════════
                             PANEL 1: Ticket Attribution
                        ════════════════════════════════════════════════════ --}}
                        <div class="panel panel-default">
                            <div class="panel-heading" style="cursor:pointer;" data-toggle="collapse" data-target="#sys-panel-attribution">
                                <strong>
                                    <span class="glyphicon glyphicon-tag" style="margin-right:6px;"></span>
                                    {{ __('orgportal::messages.system_attribution_heading') }}
                                </strong>
                                <span class="pull-right glyphicon glyphicon-chevron-down orgportal-panel-chevron" style="margin-top:2px;transition:transform .15s;"></span>
                            </div>
                            <div id="sys-panel-attribution" class="collapse in">
                                <div class="panel-body">

                                    {{-- Description spoiler --}}
                                    <p class="text-muted" style="font-size:12px;margin-top:0;">
                                        <span style="cursor:pointer;" data-toggle="collapse" data-target="#system-attribution-desc">
                                            {{ __('orgportal::messages.system_attribution_more') }}
                                            <span class="glyphicon glyphicon-chevron-right orgportal-sys-chevron" style="font-size:10px;"></span>
                                        </span>
                                    </p>
                                    <div id="system-attribution-desc" class="collapse" style="margin-bottom:12px;">
                                        <p class="text-muted" style="font-size:13px;">{{ __('orgportal::messages.system_attribution_desc') }}</p>
                                    </div>

                                    {{-- Attribution source --}}
                                    <label style="font-size:13px;font-weight:600;margin-bottom:6px;display:block;">{{ __('orgportal::messages.system_attr_source_heading') }}</label>
                                    <p class="text-muted" style="font-size:12px;margin-top:0;">{{ __('orgportal::messages.system_attr_source_desc') }}</p>
                                    <div style="margin-bottom:12px;">
                                        @foreach(['member' => 'system_attr_member', 'tag' => 'system_attr_tag', 'tag_only' => 'system_attr_tag_only'] as $val => $key)
                                        <div style="margin-bottom:2px;{{ ($val !== 'member' && !$tagsModuleActive) ? 'opacity:.45;pointer-events:none;' : '' }}">
                                            <label style="font-weight:normal;margin:0;cursor:pointer;"
                                                   title="{{ __('orgportal::messages.' . $key . '_hint') }}"
                                                   data-toggle="tooltip" data-placement="right">
                                                <input type="radio" name="attribution_source" value="{{ $val }}"
                                                    {{ $attributionSource === $val ? 'checked' : '' }}>
                                                {{ __('orgportal::messages.' . $key) }}
                                                <i class="glyphicon glyphicon-question-sign text-muted" style="font-size:11px;margin-left:3px;"></i>
                                            </label>
                                        </div>
                                        @endforeach
                                        @if(!$tagsModuleActive)
                                        <p class="text-muted" style="font-size:11px;margin:4px 0 0 18px;">
                                            <i class="glyphicon glyphicon-info-sign"></i>
                                            {{ __('orgportal::messages.system_attr_tags_inactive') }}
                                        </p>
                                        @endif
                                    </div>

                                    <hr style="margin:12px 0;">

                                    {{-- Progress --}}
                                    @php
                                        $pct = $systemStats['total'] > 0
                                            ? round($systemStats['attributed'] / $systemStats['total'] * 100)
                                            : 100;
                                    @endphp
                                    <div style="margin-bottom:10px;">
                                        <div style="display:flex;justify-content:space-between;margin-bottom:4px;font-size:13px;">
                                            <span>
                                                <strong>{{ number_format($systemStats['attributed']) }}</strong>
                                                / {{ number_format($systemStats['total']) }}
                                                {{ __('orgportal::messages.system_tickets_attributed') }}
                                            </span>
                                            <span class="text-muted">{{ $pct }}%</span>
                                        </div>
                                        <div class="progress" style="margin-bottom:4px;height:10px;">
                                            <div class="progress-bar {{ $pct >= 100 ? 'progress-bar-success' : 'progress-bar-info' }}"
                                                 role="progressbar" style="width:{{ $pct }}%;min-width:2em;"></div>
                                        </div>
                                        @if($systemStats['pending'] > 0)
                                        <p class="text-muted" style="font-size:12px;margin-bottom:0;">
                                            {{ __('orgportal::messages.system_tickets_pending', ['count' => number_format($systemStats['pending'])]) }}
                                        </p>
                                        @else
                                        <p class="text-success" style="font-size:12px;margin-bottom:0;">
                                            <i class="glyphicon glyphicon-ok"></i>
                                            {{ __('orgportal::messages.system_backfill_complete') }}
                                        </p>
                                        @endif
                                    </div>

                                    {{-- Preflight --}}
                                    @if($preflightStats && $preflightStats['pending_total'] > 0)
                                    <div style="background:#f9f9f9;border:1px solid #e3e3e3;border-radius:4px;padding:10px 14px;margin:10px 0;font-size:12px;">
                                        <strong>{{ __('orgportal::messages.system_preflight_heading') }}</strong>
                                        <table style="width:100%;margin-top:6px;border-collapse:collapse;">
                                            <tr>
                                                <td style="padding:2px 0;color:#555;width:65%;">{{ __('orgportal::messages.system_preflight_pending') }}</td>
                                                <td style="padding:2px 0;font-weight:bold;">{{ number_format($preflightStats['pending_total']) }}</td>
                                            </tr>
                                            @if($preflightStats['tags_active'])
                                            <tr>
                                                <td style="padding:2px 0;color:#555;">{{ __('orgportal::messages.system_preflight_orgs_with_tags', ['n' => $preflightStats['orgs_with_tags'], 'total' => $preflightStats['orgs_total']]) }}</td>
                                                <td style="padding:2px 0;">
                                                    <span class="label label-success">{{ number_format($preflightStats['pending_by_tag']) }}</span>
                                                    {{ __('orgportal::messages.system_preflight_will_tag') }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding:2px 0;color:#555;">{{ __('orgportal::messages.system_preflight_orgs_no_tags', ['n' => $preflightStats['orgs_without_tags']]) }}</td>
                                                <td style="padding:2px 0;">
                                                    <span class="label label-default">{{ number_format($preflightStats['pending_no_tag_match']) }}</span>
                                                    {{ __('orgportal::messages.system_preflight_will_member') }}
                                                </td>
                                            </tr>
                                            @endif
                                        </table>
                                    </div>
                                    @endif

                                    {{-- Backfill result --}}
                                    @if(session('backfill_result'))
                                    @php $br = session('backfill_result'); @endphp
                                    <div class="alert alert-info" style="font-size:12px;padding:8px 12px;margin-top:8px;">
                                        <strong>{{ __('orgportal::messages.system_backfill_summary_heading') }}</strong>
                                        {{ __('orgportal::messages.system_backfill_summary_processed', ['n' => number_format($br['processed'])]) }}
                                        @if($br['by_tag'] > 0)
                                        · <span class="label label-success">{{ number_format($br['by_tag']) }}</span> {{ __('orgportal::messages.system_backfill_summary_by_tag') }}
                                        @endif
                                        @if($br['by_member'] > 0)
                                        · <span class="label label-default">{{ number_format($br['by_member']) }}</span> {{ __('orgportal::messages.system_backfill_summary_by_member') }}
                                        @endif
                                        @if($br['unmatched'] > 0)
                                        · <span class="label label-warning">{{ number_format($br['unmatched']) }}</span> {{ __('orgportal::messages.system_backfill_summary_unmatched') }}
                                        @endif
                                    </div>
                                    @endif

                                    {{-- Backfill buttons --}}
                                    <div style="margin-top:10px;">
                                        <button type="submit"
                                                formaction="{{ route('orgportal.admin.system.backfill') }}"
                                                class="btn btn-default btn-sm"
                                                style="margin-right:6px;">
                                            <i class="glyphicon glyphicon-refresh"></i>
                                            {{ __('orgportal::messages.system_run_backfill') }}
                                        </button>
                                        <button type="submit"
                                                formaction="{{ route('orgportal.admin.system.reset-attribution') }}"
                                                class="btn btn-danger btn-sm"
                                                onclick="return confirm('{{ __('orgportal::messages.system_reset_confirm') }}')">
                                            <i class="glyphicon glyphicon-trash"></i>
                                            {{ __('orgportal::messages.system_reset_attribution') }}
                                        </button>
                                        <span class="text-muted" style="font-size:11px;margin-left:8px;">{{ __('orgportal::messages.system_cron_hint') }}</span>
                                    </div>

                                    <hr style="margin:10px 0;">

                                    {{-- Snapshot visibility --}}
                                    @if($systemStats['pending'] > 0)
                                    <div class="alert alert-warning" style="font-size:12px;padding:8px 12px;margin-bottom:10px;">
                                        <i class="glyphicon glyphicon-warning-sign"></i>
                                        {{ __('orgportal::messages.system_snapshot_warning') }}
                                    </div>
                                    @endif
                                    <div class="checkbox" style="margin-top:0;">
                                        <label>
                                            <input type="checkbox" name="attribution_cron_enabled" value="1"
                                                   {{ \Option::get('orgportal.attribution_cron_enabled') ? 'checked' : '' }}>
                                            <strong>{{ __('orgportal::messages.system_attr_cron_enabled') }}</strong>
                                        </label>
                                        <p class="text-muted" style="margin-left:20px;font-size:12px;margin-bottom:0;">
                                            {{ __('orgportal::messages.system_attr_cron_enabled_hint') }}
                                        </p>
                                    </div>

                                    <div class="checkbox" style="margin-top:0;">
                                        <label>
                                            <input type="checkbox" name="snapshot_visibility" value="1"
                                                   {{ $snapshotEnabled ? 'checked' : '' }}>
                                            <strong>{{ __('orgportal::messages.system_snapshot_label') }}</strong>
                                        </label>
                                        <p class="text-muted" style="margin-left:20px;font-size:12px;margin-bottom:0;">
                                            {{ __('orgportal::messages.system_snapshot_hint') }}
                                        </p>
                                    </div>

                                </div>
                            </div>
                        </div>

                        {{-- ═══════════════════════════════════════════════════
                             PANEL 2: Portal Language Switcher
                        ════════════════════════════════════════════════════ --}}
                        <div class="panel panel-default">
                            <div class="panel-heading" style="cursor:pointer;" data-toggle="collapse" data-target="#sys-panel-lang">
                                <strong>
                                    <span class="glyphicon glyphicon-globe" style="margin-right:6px;"></span>
                                    {{ __('orgportal::messages.system_lang_heading') }}
                                </strong>
                                <span class="pull-right glyphicon glyphicon-chevron-right orgportal-panel-chevron" style="margin-top:2px;transition:transform .15s;"></span>
                            </div>
                            <div id="sys-panel-lang" class="collapse">
                                <div class="panel-body">
                                    <p class="text-muted" style="font-size:12px;margin-top:0;">{{ __('orgportal::messages.system_lang_desc') }}</p>

                                    <div class="checkbox" style="margin-bottom:10px;">
                                        <label>
                                            <input type="checkbox" name="lang_switcher_enabled" value="1"
                                                   id="lang_switcher_enabled"
                                                   {{ $langSwitcherEnabled ? 'checked' : '' }}>
                                            <strong>{{ __('orgportal::messages.system_lang_enable') }}</strong>
                                        </label>
                                        <p class="text-muted" style="margin-left:20px;font-size:12px;">{{ __('orgportal::messages.system_lang_enable_hint') }}</p>
                                    </div>

                                    <div id="lang-locales-block" style="{{ $langSwitcherEnabled ? '' : 'display:none;' }}">
                                        <label style="font-size:13px;">{{ __('orgportal::messages.system_lang_locales') }}</label>
                                        <div class="orgportal-table-wrap" style="margin-bottom:8px;">
                                            <div style="display:flex;flex-wrap:wrap;gap:6px;min-width:max-content;">
                                                @foreach($availableLocales as $code => $name)
                                                <label style="font-weight:normal;background:#f5f5f5;border:1px solid #ddd;border-radius:4px;padding:3px 10px;cursor:pointer;margin:0;font-size:13px;">
                                                    <input type="checkbox" name="lang_switcher_locales[]" value="{{ $code }}"
                                                           {{ in_array($code, $langSwitcherLocales) || empty($langSwitcherLocales) ? 'checked' : '' }}>
                                                    {{ $name }}
                                                </label>
                                                @endforeach
                                            </div>
                                        </div>
                                        <p class="text-muted" style="font-size:12px;">{{ __('orgportal::messages.system_lang_locales_hint') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Single Save button --}}
                        <div style="margin-top:4px;">
                            <button type="submit" class="btn btn-primary">
                                <i class="glyphicon glyphicon-floppy-disk" style="margin-right:4px;"></i>
                                {{ __('orgportal::messages.system_save_settings') }}
                            </button>
                        </div>

                    </form>

                </div>
                @endif

            </div>{{-- /.tab-content --}}

        </div>
    </div>
</div>

<div id="orgportal-defaults-data" data-defaults='{!! json_encode($tplDefaults ?? [], JSON_HEX_QUOT | JSON_HEX_TAG | JSON_UNESCAPED_SLASHES) !!}'></div>

<script {!! \Helper::cspNonceAttr() !!}>
window.orgportalDefaults = JSON.parse(document.getElementById('orgportal-defaults-data').getAttribute('data-defaults') || '{}');
(function () {
    document.addEventListener('DOMContentLoaded', function () {
        if (typeof $ === 'undefined' || typeof $.fn.summernote === 'undefined') return;

        // Tooltip init (system tab + disabled deactivate btn)
        $('[data-toggle="tooltip"]').tooltip();

        // Organizations list — live search + status filter
        var $orgSearch = $('#orgportal-org-search');
        var $orgTable  = $('#orgportal-org-table');
        var $noResults = $('#orgportal-org-no-results');
        var orgStatus  = 'active'; // default

        function applyOrgFilters() {
            var q = $orgSearch.length ? $orgSearch.val().trim().toLowerCase() : '';
            var visibleCount = 0;
            $orgTable.find('tbody tr').each(function () {
                var name     = $(this).data('org-name') || '';
                var isActive = $(this).data('is-active') === 1 || $(this).data('is-active') === '1';
                var matchSearch = q.length < 2 || name.indexOf(q) !== -1;
                var matchStatus = orgStatus === 'all' ||
                                  (orgStatus === 'active' && isActive) ||
                                  (orgStatus === 'inactive' && !isActive);
                var show = matchSearch && matchStatus;
                $(this).toggle(show);
                if (show) visibleCount++;
            });
            $noResults.toggle(visibleCount === 0);
            $orgTable.toggle(visibleCount > 0);
        }

        if ($orgSearch.length && $orgTable.length) {
            $orgSearch.on('input', function () {
                var q = $(this).val().trim();
                if (q.length > 0 && q.length < 2) return;
                applyOrgFilters();
            });
        }

        $('#orgportal-status-filter input[type=radio]').on('change', function () {
            orgStatus = $(this).val();
            applyOrgFilters();
        });

        // Apply default filter on load
        applyOrgFilters();

        $('#lang_switcher_enabled').on('change', function () {
            $('#lang-locales-block').toggle(this.checked);
        });

        // System tab — attribution description spoiler chevron
        $('#system-attribution-desc')
            .on('show.bs.collapse', function () {
                $('.orgportal-sys-chevron').css('transform', 'rotate(90deg)');
            })
            .on('hide.bs.collapse', function () {
                $('.orgportal-sys-chevron').css('transform', 'rotate(0deg)');
            });

        // Panel chevrons (down when open, right when closed)
        function syncPanelChevron($panel) {
            var $icon = $panel.prev('.panel-heading').find('.orgportal-panel-chevron');
            if ($panel.hasClass('in')) {
                $icon.css('transform', 'rotate(0deg)');
            } else {
                $icon.css('transform', 'rotate(-90deg)');
            }
        }
        $('.panel .collapse').each(function () { syncPanelChevron($(this)); });
        $('.panel .collapse')
            .on('show.bs.collapse', function () {
                $(this).prev('.panel-heading').find('.orgportal-panel-chevron').css('transform', 'rotate(0deg)');
            })
            .on('hide.bs.collapse', function () {
                $(this).prev('.panel-heading').find('.orgportal-panel-chevron').css('transform', 'rotate(-90deg)');
            });

        // Rotate chevron on collapse
        $('[data-toggle="collapse"]').each(function () {
            var $heading = $(this);
            var $icon    = $heading.find('.glyphicon-chevron-right');
            var target   = $heading.data('target');
            $(target).on('show.bs.collapse', function () {
                $icon.css('transform', 'rotate(90deg)');
            }).on('hide.bs.collapse', function () {
                $icon.css('transform', 'rotate(0deg)');
            });
        });

        // Init Summernote when panel is opened (lazy init to avoid hidden-editor issues)
        $('.panel-collapse').on('shown.bs.collapse', function () {
            $(this).find('.orgportal-editor').each(function () {
                var $ta = $(this);
                if ($ta.data('summernote-inited')) return;
                $ta.data('summernote-inited', true);
                $ta.summernote({
                    height: 200,
                    toolbar: [
                        ['style', ['bold', 'italic', 'underline', 'clear']],
                        ['color', ['color']],
                        ['para', ['ul', 'ol', 'paragraph']],
                        ['insert', ['link']],
                        ['view', ['codeview']],
                    ],
                    callbacks: {
                        onChange: function (contents) { $ta.val(contents); }
                    }
                });
            });
        });

        // Macro → body (Summernote)
        $(document).on('change', '.orgportal-macro-body', function () {
            var macro  = $(this).val();
            var target = $(this).data('target');
            if (!macro || !target) { $(this).val(''); return; }
            var $ta = $('#' + target);
            if ($ta.data('summernote-inited')) {
                $ta.summernote('insertText', macro);
            } else {
                var pos = $ta[0].selectionStart || $ta[0].value.length;
                $ta[0].value = $ta[0].value.slice(0, pos) + macro + $ta[0].value.slice(pos);
            }
            $(this).val('');
        });

        // Macro → subject (plain input)
        $(document).on('change', '.orgportal-macro-subject', function () {
            var macro  = $(this).val();
            var target = $(this).data('target');
            if (!macro || !target) { $(this).val(''); return; }
            var input = document.getElementById(target);
            if (!input) { $(this).val(''); return; }
            var pos = input.selectionStart || input.value.length;
            input.value = input.value.slice(0, pos) + macro + input.value.slice(pos);
            input.selectionStart = input.selectionEnd = pos + macro.length;
            input.focus();
            $(this).val('');
        });

        // Load default template
        $(document).on('click', '.orgportal-load-default', function () {
            var eKey    = $(this).data('event');
            var defs    = window.orgportalDefaults || {};
            var tpl     = defs[eKey];
            if (!tpl) return;
            var $subjectInput = $('#tpl_' + eKey + '_subject');
            var $bodyTa       = $('#tpl_' + eKey + '_body');
            $subjectInput.val(tpl.subject || '');
            if ($bodyTa.data('summernote-inited')) {
                $bodyTa.summernote('code', tpl.body || '');
            } else {
                $bodyTa.val(tpl.body || '');
            }
        });

        // Sync Summernote → textarea on form submit
        $(document).on('submit', 'form', function () {
            var $form = $(this);
            var hasEditors = false;
            $form.find('.orgportal-editor').each(function () {
                if ($(this).data('summernote-inited')) {
                    $(this).val($(this).summernote('code'));
                    hasEditors = true;
                }
            });
            // Allow form to submit normally (return true or don't prevent)
        });
    });
})();
</script>

@include('partials/editor')
@endsection
