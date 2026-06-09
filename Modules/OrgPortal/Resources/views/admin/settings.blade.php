@extends('layouts.app')

@section('title', __('orgportal::messages.module_settings'))

@section('content')
<div class="section-heading">
    {{ __('orgportal::messages.module_settings') }}
</div>

<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">

            @include('partials/flash_messages')

            <form method="POST" action="{{ route('orgportal.admin.settings.save') }}">
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
@endsection
