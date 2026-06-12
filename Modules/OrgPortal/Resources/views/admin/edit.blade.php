@extends('layouts.app')

@section('title', __('orgportal::messages.organizations'))

@section('content')
<div class="section-heading">
    {{ __('orgportal::messages.organizations') }}: {{ $organization->name }}
</div>

<div class="container">
    <div class="row">

        @include('partials/flash_messages')

        {{-- Organization name --}}
        <div class="col-md-5">
            <div class="panel panel-default">
                <div class="panel-heading"><strong>{{ __('orgportal::messages.org_details') }}</strong></div>
                <div class="panel-body">
                    <form method="POST" action="{{ route('orgportal.admin.update', $organization->id) }}">
                        {{ csrf_field() }}
                        {{ method_field('PUT') }}

                        <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                            <label for="name">{{ __('orgportal::messages.name') }}</label>
                            <input type="text"
                                   id="name"
                                   name="name"
                                   class="form-control"
                                   value="{{ old('name', $organization->name) }}"
                                   required
                                   maxlength="255">
                            @if($errors->has('name'))
                                <span class="help-block">{{ $errors->first('name') }}</span>
                            @endif
                        </div>

                        @php
                            $defaultColor = \Modules\OrgPortal\Models\Organization::DEFAULT_COLOR;
                            $currentColor = old('color', $organization->color ?: '');
                            $previewColor = $currentColor ?: $defaultColor;
                            $palette = [
                                ['hex' => $defaultColor, 'name' => __('orgportal::messages.color_default')],
                                ['hex' => '#5b9bd5', 'name' => 'Blue'],
                                ['hex' => '#28a745', 'name' => 'Green'],
                                ['hex' => '#dc3545', 'name' => 'Red'],
                                ['hex' => '#fd7e14', 'name' => 'Orange'],
                                ['hex' => '#6f42c1', 'name' => 'Purple'],
                                ['hex' => '#20c997', 'name' => 'Teal'],
                                ['hex' => '#e83e8c', 'name' => 'Pink'],
                                ['hex' => '#17a2b8', 'name' => 'Cyan'],
                                ['hex' => '#ffc107', 'name' => 'Yellow'],
                                ['hex' => '#343a40', 'name' => 'Dark'],
                                ['hex' => '#795548', 'name' => 'Brown'],
                            ];
                        @endphp

                        <div class="form-group {{ $errors->has('color') ? 'has-error' : '' }}">
                            <label>{{ __('orgportal::messages.badge_color') }}</label>

                            {{-- Hidden input: empty value means "use default gray" --}}
                            <input type="hidden" name="color" id="org_color_input" value="{{ $currentColor }}">

                            <div id="org_color_swatches" style="display:flex;flex-wrap:wrap;gap:6px;margin-bottom:8px;">
                                @foreach($palette as $i => $swatch)
                                    @php
                                        $isDefault = ($i === 0);
                                        $swatchValue = $isDefault ? '' : $swatch['hex'];
                                        $isSelected = ($swatchValue === $currentColor);
                                    @endphp
                                    <span class="orgportal-color-swatch{{ $isSelected ? ' is-selected' : '' }}"
                                          data-color="{{ $swatchValue }}"
                                          data-hex="{{ $swatch['hex'] }}"
                                          title="{{ $swatch['name'] }}"
                                          style="display:inline-block;width:26px;height:26px;border-radius:4px;cursor:pointer;background-color:{{ $swatch['hex'] }};border:2px solid {{ $isSelected ? '#333' : 'transparent' }};box-shadow:0 0 0 1px rgba(0,0,0,.15);"></span>
                                @endforeach
                            </div>

                            <div>
                                <span class="text-muted" style="font-size:12px;">{{ __('orgportal::messages.preview') }}:</span>
                                <span class="orgportal-org-badge" id="org_color_preview"
                                      style="background-color:{{ $previewColor }};border-color:{{ \Modules\OrgPortal\Models\Organization::darkenColor($previewColor, 0.85) }};">
                                    <span class="glyphicon glyphicon-briefcase" style="margin-right:3px;"></span><span id="org_color_preview_name">{{ $organization->name }}</span>
                                </span>
                            </div>

                            @if($errors->has('color'))
                                <span class="help-block">{{ $errors->first('color') }}</span>
                            @endif
                        </div>

                        <div class="form-group {{ $errors->has('mailbox_id') ? 'has-error' : '' }}">
                            <label for="mailbox_id">{{ __('orgportal::messages.mailbox') }}</label>
                            <select id="mailbox_id" name="mailbox_id" class="form-control">
                                <option value="">{{ __('orgportal::messages.global_scope') }}</option>
                                @foreach($mailboxes as $mb)
                                    <option value="{{ $mb->id }}"
                                        {{ old('mailbox_id', $organization->mailbox_id) == $mb->id ? 'selected' : '' }}>
                                        {{ $mb->name }}
                                    </option>
                                @endforeach
                            </select>
                            <span class="help-block text-muted" style="font-size:12px;">{{ __('orgportal::messages.mailbox_scope_hint') }}</span>
                            @if($errors->has('mailbox_id'))
                                <span class="help-block">{{ $errors->first('mailbox_id') }}</span>
                            @endif
                        </div>

                        <button type="submit" class="btn btn-primary btn-sm">
                            {{ __('orgportal::messages.save') }}
                        </button>
                        <a href="{{ route('orgportal.admin.index') }}" class="btn btn-default btn-sm">
                            {{ __('orgportal::messages.back') }}
                        </a>
                    </form>
                </div>
            </div>

            {{-- Structural units --}}
            <div class="panel panel-default">
                <div class="panel-heading"><strong>{{ __('orgportal::messages.tab_units') }}</strong></div>
                <div class="panel-body">
                    @if($units->count())
                        <table class="table table-condensed table-striped">
                            <tbody>
                                @foreach($units as $unit)
                                <tr>
                                    <td>
                                        <form method="POST"
                                              action="{{ route('orgportal.admin.units.rename', [$organization->id, $unit->id]) }}"
                                              style="display:inline-flex;gap:4px;align-items:center">
                                            {{ csrf_field() }}
                                            {{ method_field('PUT') }}
                                            <input type="text" name="name" class="form-control input-sm"
                                                   value="{{ $unit->name }}" maxlength="255" required>
                                            <button type="submit" class="btn btn-xs btn-primary" title="{{ __('orgportal::messages.save') }}">✓</button>
                                        </form>
                                    </td>
                                    <td class="text-right">
                                        <form method="POST"
                                              action="{{ route('orgportal.admin.units.delete', [$organization->id, $unit->id]) }}"
                                              onsubmit="return confirm('{{ __('orgportal::messages.confirm_delete_unit') }}')">
                                            {{ csrf_field() }}
                                            {{ method_field('DELETE') }}
                                            <button type="submit" class="btn btn-xs btn-danger">
                                                {{ __('orgportal::messages.delete') }}
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p class="text-muted">{{ __('orgportal::messages.no_units') }}</p>
                    @endif

                    <form method="POST" action="{{ route('orgportal.admin.units.add', $organization->id) }}"
                          style="display:flex;gap:4px;align-items:center;margin-top:8px;">
                        {{ csrf_field() }}
                        <input type="text" name="name" class="form-control input-sm"
                               placeholder="{{ __('orgportal::messages.unit_name_placeholder') }}"
                               maxlength="255" required>
                        <button type="submit" class="btn btn-success btn-sm">
                            {{ __('orgportal::messages.add_unit') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Members --}}
        <div class="col-md-7">
            <div class="panel panel-default">
                <div class="panel-heading"><strong>{{ __('orgportal::messages.members') }}</strong></div>
                <div class="panel-body">

                    @if($members->count())
                        <table class="table table-condensed table-striped">
                            <thead>
                                <tr>
                                    <th>{{ __('orgportal::messages.name') }}</th>
                                    <th style="width:160px">{{ __('orgportal::messages.member_unit') }}</th>
                                    <th style="width:120px">{{ __('orgportal::messages.role') }}</th>
                                    <th style="width:1px;white-space:nowrap">{{ __('orgportal::messages.can_manage_org') }}</th>
                                    <th style="width:80px">{{ __('orgportal::messages.member_status') }}</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($members as $member)
                                {{-- hidden form for role/unit update; inputs below use form="mf-{id}" --}}
                                <form id="mf-{{ $member->id }}"
                                      method="POST"
                                      action="{{ route('orgportal.admin.members.role', [$organization->id, $member->id]) }}"
                                      style="display:none">
                                    {{ csrf_field() }}
                                </form>
                                <tr class="{{ $member->is_active ? '' : 'text-muted' }}">
                                    <td>
                                        @if($member->customer)
                                            {{ $member->customer->getFullName() }}
                                            <small class="text-muted">#{{ $member->customer_id }}</small>
                                            @if($member->customer->getMainEmail())
                                                <br><small class="text-muted">{{ $member->customer->getMainEmail() }}</small>
                                            @endif
                                        @else
                                            <em class="text-muted">{{ __('orgportal::messages.deleted_customer') }}</em>
                                        @endif
                                    </td>
                                    <td>
                                        <select name="unit_id" form="mf-{{ $member->id }}" class="form-control input-sm" style="width:100%">
                                            <option value="">{{ __('orgportal::messages.no_unit') }}</option>
                                            @foreach($units as $unit)
                                                <option value="{{ $unit->id }}" {{ $member->unit_id === $unit->id ? 'selected' : '' }}>{{ $unit->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <select name="role" form="mf-{{ $member->id }}" class="form-control input-sm" style="width:100%">
                                            <option value="member"  {{ $member->role === 'member'  ? 'selected' : '' }}>{{ __('orgportal::messages.member') }}</option>
                                            <option value="manager" {{ $member->role === 'manager' ? 'selected' : '' }}>{{ __('orgportal::messages.manager') }}</option>
                                        </select>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" name="can_manage_org" value="1"
                                               form="mf-{{ $member->id }}"
                                               {{ $member->can_manage_org ? 'checked' : '' }}
                                               title="{{ __('orgportal::messages.can_manage_org_hint') }}">
                                    </td>
                                    <td>
                                        @if($member->is_active)
                                            <span class="label label-success">{{ __('orgportal::messages.status_member_active') }}</span>
                                        @else
                                            <span class="label label-default">{{ __('orgportal::messages.status_member_inactive') }}</span>
                                        @endif
                                    </td>
                                    <td class="text-right" style="white-space:nowrap">
                                        <button type="submit" form="mf-{{ $member->id }}" class="btn btn-xs btn-primary" title="{{ __('orgportal::messages.save') }}">✓</button>
                                        <form method="POST"
                                              action="{{ route('orgportal.admin.members.toggle', [$organization->id, $member->id]) }}"
                                              style="display:inline">
                                            {{ csrf_field() }}
                                            <button type="submit" class="btn btn-xs btn-default">
                                                {{ $member->is_active ? __('orgportal::messages.deactivate') : __('orgportal::messages.activate') }}
                                            </button>
                                        </form>
                                        <form method="POST"
                                              action="{{ route('orgportal.admin.members.remove', [$organization->id, $member->id]) }}"
                                              onsubmit="return confirm('{{ __('orgportal::messages.confirm_remove_member') }}')"
                                              style="display:inline">
                                            {{ csrf_field() }}
                                            {{ method_field('DELETE') }}
                                            <button type="submit" class="btn btn-xs btn-danger">
                                                {{ __('orgportal::messages.remove') }}
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p class="text-muted">{{ __('orgportal::messages.no_members') }}</p>
                    @endif

                    <hr>
                    <h5>{{ __('orgportal::messages.add_member') }}</h5>
                    <form method="POST" action="{{ route('orgportal.admin.members.add', $organization->id) }}">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label>{{ __('orgportal::messages.search_customer') }}</label>
                            <input type="hidden" id="customer_id" name="customer_id" required>
                            <div style="position:relative">
                                <input type="text"
                                       id="customer_search"
                                       class="form-control"
                                       placeholder="{{ __('orgportal::messages.type_name_or_email') }}"
                                       autocomplete="off">
                                <ul id="customer_suggestions"
                                    class="list-group"
                                    style="position:absolute;z-index:1000;width:100%;display:none;max-height:200px;overflow-y:auto;top:100%;left:0;margin-top:2px;box-shadow:0 4px 8px rgba(0,0,0,.15);"></ul>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>{{ __('orgportal::messages.member_unit') }}</label>
                            <select name="unit_id" class="form-control">
                                <option value="">{{ __('orgportal::messages.no_unit') }}</option>
                                @foreach($units as $unit)
                                    <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>{{ __('orgportal::messages.role') }}</label>
                            <select name="role" class="form-control">
                                <option value="member">{{ __('orgportal::messages.member') }}</option>
                                <option value="manager">{{ __('orgportal::messages.manager') }}</option>
                            </select>
                        </div>
                        <div class="checkbox">
                            <label title="{{ __('orgportal::messages.can_manage_org_hint') }}">
                                <input type="checkbox" name="can_manage_org" value="1">
                                {{ __('orgportal::messages.can_manage_org') }}
                            </label>
                        </div>
                        <button type="submit" class="btn btn-success btn-sm" id="add_member_btn" disabled>
                            {{ __('orgportal::messages.add_member') }}
                        </button>
                    </form>

                </div>
            </div>
        </div>

    </div>
</div>

<script {!! \Helper::cspNonceAttr() !!}>
(function () {
    var searchInput = document.getElementById('customer_search');
    var hiddenInput = document.getElementById('customer_id');
    var suggestions = document.getElementById('customer_suggestions');
    var addBtn      = document.getElementById('add_member_btn');
    var searchUrl   = '{{ route('orgportal.admin.customers.search') }}';
    var timer;

    searchInput.addEventListener('input', function () {
        clearTimeout(timer);
        var q = this.value.trim();

        hiddenInput.value = '';
        addBtn.disabled = true;

        if (q.length < 2) {
            suggestions.style.display = 'none';
            return;
        }

        timer = setTimeout(function () {
            fetch(searchUrl + '?q=' + encodeURIComponent(q), {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(function (r) { return r.json(); })
            .then(function (data) {
                suggestions.innerHTML = '';
                if (!data.length) {
                    suggestions.style.display = 'none';
                    return;
                }
                data.forEach(function (item) {
                    var li = document.createElement('li');
                    li.className = 'list-group-item';
                    li.style.cursor = 'pointer';

                    var nameEl = document.createElement('div');
                    nameEl.textContent = item.name;

                    if (item.email) {
                        var emailEl = document.createElement('small');
                        emailEl.style.color = '#999';
                        emailEl.textContent = item.email;
                        li.appendChild(nameEl);
                        li.appendChild(emailEl);
                    } else {
                        li.appendChild(nameEl);
                    }

                    li.addEventListener('mouseenter', function () { this.style.backgroundColor = '#f5f5f5'; });
                    li.addEventListener('mouseleave', function () { this.style.backgroundColor = ''; });
                    li.addEventListener('click', function () {
                        searchInput.value = item.name;
                        hiddenInput.value = item.id;
                        addBtn.disabled   = false;
                        suggestions.style.display = 'none';
                    });
                    suggestions.appendChild(li);
                });
                suggestions.style.display = 'block';
            })
            .catch(function (err) {
                console.error('[OrgPortal] customer search error:', err);
                suggestions.style.display = 'none';
            });
        }, 300);
    });

    document.addEventListener('click', function (e) {
        if (!suggestions.contains(e.target) && e.target !== searchInput) {
            suggestions.style.display = 'none';
        }
    });
})();

// Badge color swatch picker
(function () {
    var input    = document.getElementById('org_color_input');
    var wrap     = document.getElementById('org_color_swatches');
    var preview  = document.getElementById('org_color_preview');
    if (!input || !wrap || !preview) {
        return;
    }
    var swatches = wrap.querySelectorAll('.orgportal-color-swatch');

    // Darken a hex color by a factor (mirrors Organization::darkenColor in PHP).
    function darken(hex, factor) {
        hex = hex.replace('#', '');
        if (hex.length === 3) {
            hex = hex[0] + hex[0] + hex[1] + hex[1] + hex[2] + hex[2];
        }
        if (hex.length !== 6) {
            return '#' + hex;
        }
        var r = Math.min(255, Math.round(parseInt(hex.substr(0, 2), 16) * factor));
        var g = Math.min(255, Math.round(parseInt(hex.substr(2, 2), 16) * factor));
        var b = Math.min(255, Math.round(parseInt(hex.substr(4, 2), 16) * factor));
        function pad(n) { return ('0' + n.toString(16)).slice(-2); }
        return '#' + pad(r) + pad(g) + pad(b);
    }

    function select(swatch) {
        var value = swatch.getAttribute('data-color'); // '' = default
        var hex   = swatch.getAttribute('data-hex');

        input.value = value;

        for (var i = 0; i < swatches.length; i++) {
            swatches[i].style.border = '2px solid transparent';
            swatches[i].classList.remove('is-selected');
        }
        swatch.style.border = '2px solid #333';
        swatch.classList.add('is-selected');

        preview.style.backgroundColor = hex;
        preview.style.borderColor     = darken(hex, 0.85);
    }

    for (var i = 0; i < swatches.length; i++) {
        (function (swatch) {
            swatch.addEventListener('click', function () { select(swatch); });
        })(swatches[i]);
    }
})();
</script>
@endsection
