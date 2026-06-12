@extends('layouts.app')

@section('title', __('orgportal::messages.module_settings'))

@section('content')
<div class="section-heading">
    {{ __('orgportal::messages.module_settings') }}
</div>

<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">

            @include('partials/flash_messages')

            <form method="POST" action="{{ route('orgportal.admin.settings.save') }}">
                {{ csrf_field() }}

                {{-- ── Display settings ─────────────────────────────────────── --}}
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

                {{-- ── Reply notification triggers ──────────────────────────── --}}
                <div class="panel panel-default">
                    <div class="panel-heading">{{ __('orgportal::messages.notif_reply_triggers') }}</div>
                    <div class="panel-body">
                        <div class="form-group">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="notify_agent_reply" value="1"
                                        {{ $notify_agent_reply ? 'checked' : '' }}>
                                    {{ __('orgportal::messages.notif_trigger_agent') }}
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="notify_customer_reply" value="1"
                                        {{ $notify_customer_reply ? 'checked' : '' }}>
                                    {{ __('orgportal::messages.notif_trigger_customer') }}
                                </label>
                            </div>
                        </div>
                        <p class="text-muted" style="font-size:12px;margin-top:4px;">
                            {{ __('orgportal::messages.notif_trigger_hint') }}
                        </p>
                    </div>
                </div>

                {{-- ── Email templates ──────────────────────────────────────── --}}
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

                @foreach($events as $eKey => $eLabel)
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <strong>{{ __('orgportal::messages.tpl_heading') }}: {{ $eLabel }}</strong>
                        <span class="text-muted" style="font-size:12px;margin-left:8px;">
                            {{ __('orgportal::messages.tpl_fallback_hint') }}
                        </span>
                    </div>
                    <div class="panel-body">

                        {{-- Subject --}}
                        <div class="form-group">
                            <label>{{ __('orgportal::messages.tpl_subject') }}</label>
                            <div class="input-group">
                                <input type="text"
                                       id="tpl_{{ $eKey }}_subject"
                                       name="tpl_{{ $eKey }}_subject"
                                       class="form-control"
                                       value="{{ $templates[$eKey]['subject'] }}"
                                       placeholder="{{ __('orgportal::messages.tpl_subject_placeholder') }}">
                                <span class="input-group-btn">
                                    <select class="btn btn-default orgportal-macro-subject"
                                            data-target="tpl_{{ $eKey }}_subject"
                                            style="height:34px;border:1px solid #ccc;border-radius:0 4px 4px 0;background:#f5f5f5;cursor:pointer;">
                                        <option value="">{{ __('orgportal::messages.tpl_insert_macro') }}</option>
                                        @foreach($macros as $macro => $macroLabel)
                                            <option value="{{ $macro }}">{{ $macroLabel }}</option>
                                        @endforeach
                                    </select>
                                </span>
                            </div>
                        </div>

                        {{-- Body --}}
                        <div class="form-group">
                            <label>{{ __('orgportal::messages.tpl_body') }}</label>
                            <div style="margin-bottom:6px;">
                                <select class="btn btn-default btn-sm orgportal-macro-body"
                                        data-target="tpl_{{ $eKey }}_body"
                                        style="border:1px solid #ccc;background:#f5f5f5;cursor:pointer;">
                                    <option value="">{{ __('orgportal::messages.tpl_insert_macro') }}</option>
                                    @foreach($macros as $macro => $macroLabel)
                                        <option value="{{ $macro }}">{{ $macroLabel }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <textarea id="tpl_{{ $eKey }}_body"
                                      name="tpl_{{ $eKey }}_body"
                                      class="form-control orgportal-editor"
                                      rows="6">{{ $templates[$eKey]['body'] }}</textarea>
                        </div>

                    </div>
                </div>
                @endforeach

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">
                        {{ __('orgportal::messages.save') }}
                    </button>
                    <a href="{{ route('orgportal.admin.index') }}" class="btn btn-default">
                        {{ __('orgportal::messages.back') }}
                    </a>
                </div>

            </form>
        </div>
    </div>
</div>

<script {!! \Helper::cspNonceAttr() !!}>
(function () {
    // Init Summernote on each template body textarea
    document.addEventListener('DOMContentLoaded', function () {
        if (typeof $ === 'undefined' || typeof $.fn.summernote === 'undefined') return;

        $('.orgportal-editor').each(function () {
            var $ta = $(this);
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
                    onChange: function (contents) {
                        $ta.val(contents);
                    }
                }
            });
        });

        // Macro insert for body (Summernote)
        $(document).on('change', '.orgportal-macro-body', function () {
            var macro  = $(this).val();
            var target = $(this).data('target');
            if (!macro || !target) { $(this).val(''); return; }
            $('#' + target).summernote('insertText', macro);
            $(this).val('');
        });

        // Macro insert for subject (plain input)
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

        // Before form submit — sync Summernote content to textarea values
        $('form').on('submit', function () {
            $('.orgportal-editor').each(function () {
                $(this).val($(this).summernote('code'));
            });
        });
    });
})();
</script>

@include('partials/editor')
@endsection
