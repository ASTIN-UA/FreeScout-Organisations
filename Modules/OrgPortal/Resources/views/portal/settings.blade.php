@extends('enduserportal::layouts.portal')

@section('title', __('orgportal::messages.org_settings_title'))

@section('content')
@php
    $activeTab = request()->input('tab', 'notifications');
    if (!in_array($activeTab, ['notifications', 'units', 'members'])) {
        $activeTab = 'notifications';
    }
@endphp
<div id="eup-container">
    <div class="eup-container-padded">

        <div class="heading margin-bottom text-center">
            {{ __('orgportal::messages.org_settings_title') }}
        </div>

        @if(session('flash_success'))
            <div class="alert alert-success">{{ session('flash_success') }}</div>
        @endif
        @if(session('flash_error'))
            <div class="alert alert-danger">{{ session('flash_error') }}</div>
        @endif

        <ul class="nav nav-tabs" role="tablist" style="margin-bottom:0;">
            <li role="presentation" class="{{ $activeTab === 'notifications' ? 'active' : '' }}">
                <a href="#tab-notifications" aria-controls="tab-notifications" role="tab" data-toggle="tab">
                    {{ __('orgportal::messages.tab_notifications') }}
                </a>
            </li>
            @if($canManageStructure)
                <li role="presentation" class="{{ $activeTab === 'units' ? 'active' : '' }}">
                    <a href="#tab-units" aria-controls="tab-units" role="tab" data-toggle="tab">
                        {{ __('orgportal::messages.tab_units') }}
                    </a>
                </li>
            @endif
            <li role="presentation" class="{{ $activeTab === 'members' ? 'active' : '' }}">
                <a href="#tab-members" aria-controls="tab-members" role="tab" data-toggle="tab">
                    {{ __('orgportal::messages.members') }}
                </a>
            </li>
        </ul>

        <div class="tab-content" style="padding-top:12px;">

            {{-- ─── Notifications ─────────────────────────────────────── --}}
            <div role="tabpanel" class="tab-pane {{ $activeTab === 'notifications' ? 'active' : '' }}" id="tab-notifications">
                @include('orgportal::portal.settings_inline', [
                    'member'     => $member,
                    'mailbox_id' => $mailbox_id,
                ])
            </div>

            {{-- ─── Units (global manager only) ───────────────────────── --}}
            @if($canManageStructure)
            <div role="tabpanel" class="tab-pane {{ $activeTab === 'units' ? 'active' : '' }}" id="tab-units">

                <form method="POST"
                      action="{{ route('orgportal.portal.units.create', ['mailbox_id' => $mailbox_id]) }}"
                      class="form-inline" style="margin-bottom:18px;">
                    {{ csrf_field() }}
                    <input type="text" name="name" class="form-control"
                           placeholder="{{ __('orgportal::messages.unit_name_placeholder') }}"
                           maxlength="255" required>
                    <button type="submit" class="btn btn-primary">
                        {{ __('orgportal::messages.add_unit') }}
                    </button>
                </form>

                @if($units->isEmpty())
                    <p class="text-muted">{{ __('orgportal::messages.no_units') }}</p>
                @else
                    <table class="table">
                        <thead>
                            <tr>
                                <th>{{ __('orgportal::messages.unit_name') }}</th>
                                <th class="text-right">{{ __('orgportal::messages.members') }}</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($units as $unit)
                            <tr>
                                <td>
                                    <form method="POST"
                                          action="{{ route('orgportal.portal.units.rename', ['mailbox_id' => $mailbox_id, 'unit_id' => $unit->id]) }}"
                                          class="form-inline">
                                        {{ csrf_field() }}
                                        {{ method_field('PUT') }}
                                        <input type="text" name="name" class="form-control input-sm"
                                               value="{{ $unit->name }}" maxlength="255" required>
                                        <button type="submit" class="btn btn-default btn-sm">
                                            {{ __('orgportal::messages.rename') }}
                                        </button>
                                    </form>
                                </td>
                                <td class="text-right">{{ $unit->members->count() }}</td>
                                <td class="text-right">
                                    <form method="POST"
                                          action="{{ route('orgportal.portal.units.delete', ['mailbox_id' => $mailbox_id, 'unit_id' => $unit->id]) }}"
                                          onsubmit="return confirm('{{ __('orgportal::messages.confirm_delete_unit') }}');"
                                          style="display:inline;">
                                        {{ csrf_field() }}
                                        {{ method_field('DELETE') }}
                                        <button type="submit" class="btn btn-link btn-sm text-danger">
                                            {{ __('orgportal::messages.delete') }}
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
            @endif

            {{-- ─── Members ───────────────────────────────────────────── --}}
            <div role="tabpanel" class="tab-pane {{ $activeTab === 'members' ? 'active' : '' }}" id="tab-members">
                @if($members->isEmpty())
                    <p class="text-muted">{{ __('orgportal::messages.no_members') }}</p>
                @else
                    <table class="table">
                        <thead>
                            <tr>
                                <th>{{ __('orgportal::messages.name') }}</th>
                                <th>{{ __('orgportal::messages.member_unit') }}</th>
                                <th>{{ __('orgportal::messages.role') }}</th>
                                <th>{{ __('orgportal::messages.member_status') }}</th>
                                @if($canManageStructure)<th></th>@endif
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($members as $m)
                            @php $isGlobal = ($m->role === 'manager' && $m->unit_id === null); @endphp
                            <tr class="{{ $m->is_active ? '' : 'text-muted' }}">
                                <td>
                                    {{ optional($m->customer)->getFullName() ?: __('orgportal::messages.deleted_customer') }}
                                    @if($isGlobal)
                                        <span class="label label-info">{{ __('orgportal::messages.role_global_manager') }}</span>
                                    @endif
                                </td>

                                @if($canManageStructure)
                                    <td colspan="{{ 2 }}">
                                        <form method="POST"
                                              action="{{ route('orgportal.portal.members.update', ['mailbox_id' => $mailbox_id, 'member_id' => $m->id]) }}"
                                              class="form-inline">
                                            {{ csrf_field() }}
                                            <select name="unit_id" class="form-control input-sm">
                                                <option value="">{{ __('orgportal::messages.no_unit') }}</option>
                                                @foreach($units as $unit)
                                                    <option value="{{ $unit->id }}" {{ $m->unit_id === $unit->id ? 'selected' : '' }}>
                                                        {{ $unit->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <select name="role" class="form-control input-sm">
                                                <option value="member"  {{ $m->role === 'member'  ? 'selected' : '' }}>{{ __('orgportal::messages.role_member') }}</option>
                                                <option value="manager" {{ $m->role === 'manager' ? 'selected' : '' }}>{{ __('orgportal::messages.role_manager_scoped') }}</option>
                                            </select>
                                            <button type="submit" class="btn btn-default btn-sm">
                                                {{ __('orgportal::messages.apply') }}
                                            </button>
                                        </form>
                                        @if(!$canGrantGlobal)
                                            <small class="text-muted">{{ __('orgportal::messages.global_grant_hint') }}</small>
                                        @endif
                                    </td>
                                @else
                                    <td>{{ optional($m->unit)->name ?: __('orgportal::messages.no_unit') }}</td>
                                    <td>
                                        @if($isGlobal)
                                            {{ __('orgportal::messages.role_global_manager') }}
                                        @elseif($m->role === 'manager')
                                            {{ __('orgportal::messages.role_unit_manager') }}
                                        @else
                                            {{ __('orgportal::messages.role_member') }}
                                        @endif
                                    </td>
                                @endif

                                <td>
                                    @if($m->is_active)
                                        <span class="label label-success">{{ __('orgportal::messages.status_member_active') }}</span>
                                    @else
                                        <span class="label label-default">{{ __('orgportal::messages.status_member_inactive') }}</span>
                                    @endif
                                </td>

                                @if($canManageStructure)
                                    <td class="text-right">
                                        <form method="POST"
                                              action="{{ route('orgportal.portal.members.toggle', ['mailbox_id' => $mailbox_id, 'member_id' => $m->id]) }}"
                                              @if($m->is_active) onsubmit="return confirm('{{ __('orgportal::messages.confirm_deactivate') }}');" @endif
                                              style="display:inline;">
                                            {{ csrf_field() }}
                                            <button type="submit" class="btn btn-link btn-sm {{ $m->is_active ? 'text-danger' : '' }}">
                                                {{ $m->is_active ? __('orgportal::messages.deactivate') : __('orgportal::messages.activate') }}
                                            </button>
                                        </form>
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @endif
            </div>

        </div>
    </div>
</div>
@endsection
