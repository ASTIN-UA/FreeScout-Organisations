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

                    <div class="margin-bottom">
                        <a href="{{ route('orgportal.admin.create') }}" class="btn btn-primary">
                            <i class="glyphicon glyphicon-plus"></i> {{ __('orgportal::messages.new_organization') }}
                        </a>
                    </div>

                    @if($organizations->count())
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>{{ __('orgportal::messages.name') }}</th>
                                    <th>{{ __('orgportal::messages.mailbox') }}</th>
                                    <th>{{ __('orgportal::messages.members') }}</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($organizations as $org)
                                <tr>
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
                                    <td class="text-right">
                                        <a href="{{ route('orgportal.admin.edit', $org->id) }}"
                                           class="btn btn-xs btn-default">
                                            {{ __('orgportal::messages.edit') }}
                                        </a>
                                        @if(auth()->user()->isAdmin())
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
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
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

                    <div style="margin-bottom:16px;">
                        <span style="cursor:pointer;user-select:none;"
                              data-toggle="collapse"
                              data-target="#system-attribution-desc">
                            <h4 style="display:inline-block;margin:0 6px 0 0;">{{ __('orgportal::messages.system_attribution_heading') }}</h4>
                            <span class="text-muted" style="font-size:13px;">({{ __('orgportal::messages.system_attribution_more') }})</span>
                            <span class="glyphicon glyphicon-chevron-right orgportal-sys-chevron"
                                  style="font-size:11px;transition:transform .15s ease;vertical-align:middle;color:#999;margin-left:4px;"></span>
                        </span>
                        <div id="system-attribution-desc" class="collapse" style="margin-top:8px;">
                            <p class="text-muted">{{ __('orgportal::messages.system_attribution_desc') }}</p>
                        </div>
                    </div>

                    {{-- Progress --}}
                    @php
                        $pct = $systemStats['total'] > 0
                            ? round($systemStats['attributed'] / $systemStats['total'] * 100)
                            : 100;
                    @endphp
                    <div class="margin-bottom">
                        <div style="display:flex;justify-content:space-between;margin-bottom:4px;">
                            <span>
                                <strong>{{ number_format($systemStats['attributed']) }}</strong>
                                /
                                {{ number_format($systemStats['total']) }}
                                {{ __('orgportal::messages.system_tickets_attributed') }}
                            </span>
                            <span class="text-muted">{{ $pct }}%</span>
                        </div>
                        <div class="progress" style="margin-bottom:4px;">
                            <div class="progress-bar {{ $pct >= 100 ? 'progress-bar-success' : 'progress-bar-info' }}"
                                 role="progressbar"
                                 style="width:{{ $pct }}%;min-width:2em;">
                            </div>
                        </div>
                        @if($systemStats['pending'] > 0)
                        <p class="text-muted" style="font-size:12px;">
                            {{ __('orgportal::messages.system_tickets_pending', ['count' => number_format($systemStats['pending'])]) }}
                        </p>
                        @else
                        <p class="text-success" style="font-size:12px;">
                            <i class="glyphicon glyphicon-ok"></i>
                            {{ __('orgportal::messages.system_backfill_complete') }}
                        </p>
                        @endif
                    </div>

                    {{-- Manual backfill trigger --}}
                    <form method="POST" action="{{ route('orgportal.admin.system.backfill') }}" style="margin-bottom:20px;">
                        {{ csrf_field() }}
                        <button type="submit" class="btn btn-default btn-sm">
                            <i class="glyphicon glyphicon-refresh"></i>
                            {{ __('orgportal::messages.system_run_backfill') }}
                        </button>
                        <span class="text-muted" style="font-size:12px;margin-left:8px;">
                            {{ __('orgportal::messages.system_cron_hint') }}
                        </span>
                    </form>

                    <hr>

                    {{-- Attribution source --}}
                    <h4>{{ __('orgportal::messages.system_attr_source_heading') }}</h4>
                    <p class="text-muted" style="font-size:13px;">{{ __('orgportal::messages.system_attr_source_desc') }}</p>

                    <form method="POST" action="{{ route('orgportal.admin.system.save') }}" style="margin-bottom:28px;">
                        {{ csrf_field() }}
                        {{-- preserve other settings --}}
                        <input type="hidden" name="snapshot_visibility" value="{{ $snapshotEnabled ? '1' : '0' }}">
                        @if($langSwitcherEnabled)
                            <input type="hidden" name="lang_switcher_enabled" value="1">
                        @endif
                        @foreach($langSwitcherLocales as $lc)
                            <input type="hidden" name="lang_switcher_locales[]" value="{{ $lc }}">
                        @endforeach

                        <div class="form-group">
                            @foreach(['member' => 'system_attr_member', 'tag' => 'system_attr_tag', 'tag_only' => 'system_attr_tag_only'] as $val => $key)
                            <div class="radio" style="{{ ($val !== 'member' && !$tagsModuleActive) ? 'opacity:.45;pointer-events:none;' : '' }}">
                                <label>
                                    <input type="radio" name="attribution_source" value="{{ $val }}"
                                        {{ $attributionSource === $val ? 'checked' : '' }}>
                                    <strong>{{ __('orgportal::messages.' . $key) }}</strong>
                                    <p class="text-muted" style="margin-left:20px;font-size:12px;margin-bottom:0;">{{ __('orgportal::messages.' . $key . '_hint') }}</p>
                                </label>
                            </div>
                            @endforeach
                            @if(!$tagsModuleActive)
                            <p class="text-muted" style="font-size:12px;margin-top:6px;">
                                <i class="glyphicon glyphicon-info-sign"></i>
                                {{ __('orgportal::messages.system_attr_tags_inactive') }}
                            </p>
                            @endif
                        </div>

                        <button type="submit" class="btn btn-primary btn-sm">{{ __('orgportal::messages.save') }}</button>
                    </form>

                    <hr>

                    {{-- Language switcher --}}
                    <h4>{{ __('orgportal::messages.system_lang_heading') }}</h4>
                    <p class="text-muted" style="font-size:13px;">{{ __('orgportal::messages.system_lang_desc') }}</p>

                    <form method="POST" action="{{ route('orgportal.admin.system.save') }}" style="margin-bottom:28px;">
                        {{ csrf_field() }}
                        <input type="hidden" name="snapshot_visibility" value="{{ $snapshotEnabled ? '1' : '0' }}">

                        <div class="checkbox" style="margin-bottom:12px;">
                            <label>
                                <input type="checkbox"
                                       name="lang_switcher_enabled"
                                       value="1"
                                       id="lang_switcher_enabled"
                                       {{ $langSwitcherEnabled ? 'checked' : '' }}>
                                <strong>{{ __('orgportal::messages.system_lang_enable') }}</strong>
                            </label>
                            <p class="text-muted" style="margin-left:20px;font-size:12px;">
                                {{ __('orgportal::messages.system_lang_enable_hint') }}
                            </p>
                        </div>

                        <div id="lang-locales-block" style="{{ $langSwitcherEnabled ? '' : 'display:none;' }}">
                            <label>{{ __('orgportal::messages.system_lang_locales') }}</label>
                            <div class="orgportal-table-wrap" style="margin-bottom:12px;">
                            <div style="display:flex;flex-wrap:wrap;gap:8px;min-width:max-content;">
                                @foreach($availableLocales as $code => $name)
                                <label style="font-weight:normal;background:#f5f5f5;border:1px solid #ddd;border-radius:4px;padding:4px 10px;cursor:pointer;margin:0;">
                                    <input type="checkbox"
                                           name="lang_switcher_locales[]"
                                           value="{{ $code }}"
                                           {{ in_array($code, $langSwitcherLocales) || empty($langSwitcherLocales) ? 'checked' : '' }}>
                                    {{ $name }}
                                </label>
                                @endforeach
                            </div>
                            </div>
                            <p class="text-muted" style="font-size:12px;">{{ __('orgportal::messages.system_lang_locales_hint') }}</p>
                        </div>

                        <button type="submit" class="btn btn-primary btn-sm">
                            {{ __('orgportal::messages.save') }}
                        </button>
                    </form>

                    <hr>

                    {{-- Snapshot visibility toggle --}}
                    @if($systemStats['pending'] > 0)
                    <div class="alert alert-warning">
                        <i class="glyphicon glyphicon-warning-sign"></i>
                        {{ __('orgportal::messages.system_snapshot_warning') }}
                    </div>
                    @endif

                    <form method="POST" action="{{ route('orgportal.admin.system.save') }}">
                        {{ csrf_field() }}
                        @if($langSwitcherEnabled)
                            <input type="hidden" name="lang_switcher_enabled" value="1">
                        @endif
                        @foreach($langSwitcherLocales as $lc)
                            <input type="hidden" name="lang_switcher_locales[]" value="{{ $lc }}">
                        @endforeach
                        <div class="checkbox">
                            <label>
                                <input type="checkbox"
                                       name="snapshot_visibility"
                                       value="1"
                                       {{ $snapshotEnabled ? 'checked' : '' }}>
                                <strong>{{ __('orgportal::messages.system_snapshot_label') }}</strong>
                            </label>
                            <p class="text-muted" style="margin-left:20px;font-size:12px;">
                                {{ __('orgportal::messages.system_snapshot_hint') }}
                            </p>
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm">
                            {{ __('orgportal::messages.save') }}
                        </button>
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

        // System tab — show/hide locale checkboxes based on lang switcher toggle
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
        $('form').on('submit', function () {
            $('.orgportal-editor').each(function () {
                if ($(this).data('summernote-inited')) {
                    $(this).val($(this).summernote('code'));
                }
            });
        });
    });
})();
</script>

@include('partials/editor')
@endsection
