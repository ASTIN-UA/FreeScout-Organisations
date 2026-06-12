@extends('layouts.app')

@section('title', __('orgportal::messages.organizations'))

@section('content')
<div class="section-heading">
    {{ __('orgportal::messages.organizations') }}
</div>

@php
    $activeTab = request()->input('tab', 'organizations');
    if (!in_array($activeTab, ['organizations', 'templates'])) {
        $activeTab = 'organizations';
    }
@endphp

<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">

            @include('partials/flash_messages')

            <ul class="nav nav-tabs" role="tablist" style="margin-bottom:0;">
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
                        ];
                    @endphp

                    <form method="POST" action="{{ route('orgportal.admin.settings.save') }}">
                        {{ csrf_field() }}

                        @foreach($tplEvents as $eKey => $eLabel)
                        <div class="panel panel-default">
                            <div class="panel-heading"
                                 style="cursor:pointer;user-select:none;"
                                 data-toggle="collapse"
                                 data-target="#tpl-panel-{{ $eKey }}">
                                <strong>{{ __('orgportal::messages.tpl_heading') }}: {{ $eLabel }}</strong>
                                <span class="text-muted" style="font-size:12px;margin-left:8px;">
                                    {{ __('orgportal::messages.tpl_fallback_hint') }}
                                </span>
                                <span class="pull-right glyphicon glyphicon-chevron-down" style="margin-top:2px;"></span>
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

            </div>{{-- /.tab-content --}}

        </div>
    </div>
</div>

<script {!! \Helper::cspNonceAttr() !!}>
(function () {
    document.addEventListener('DOMContentLoaded', function () {
        if (typeof $ === 'undefined' || typeof $.fn.summernote === 'undefined') return;

        // Rotate chevron on collapse
        $('[data-toggle="collapse"]').each(function () {
            var $heading = $(this);
            var $icon    = $heading.find('.glyphicon-chevron-down,.glyphicon-chevron-up');
            var target   = $heading.data('target');
            $(target).on('show.bs.collapse', function () {
                $icon.removeClass('glyphicon-chevron-down').addClass('glyphicon-chevron-up');
            }).on('hide.bs.collapse', function () {
                $icon.removeClass('glyphicon-chevron-up').addClass('glyphicon-chevron-down');
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
