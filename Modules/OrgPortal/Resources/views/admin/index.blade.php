@extends('layouts.app')

@section('title', __('orgportal::messages.organizations'))

@section('content')
<div class="section-heading">
    {{ __('orgportal::messages.organizations') }}
</div>

<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">

            @include('partials/flash_messages')

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
    </div>

    @if(auth()->user()->isAdmin())
    <div class="row" style="margin-top:30px;">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading"><strong>{{ __('orgportal::messages.display_settings') }}</strong></div>
                <div class="panel-body">
                    <form method="POST" action="{{ route('orgportal.admin.settings.save') }}">
                        {{ csrf_field() }}

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

                        <button type="submit" class="btn btn-primary btn-sm">
                            {{ __('orgportal::messages.save') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
