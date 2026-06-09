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

                @if(\Module::isActive('kanban'))
                <div class="panel panel-default">
                    <div class="panel-heading">{{ __('orgportal::messages.company_filters_heading') }}</div>
                    <div class="panel-body">
                        <p class="text-muted" style="font-size:13px;">{{ __('orgportal::messages.company_filters_hint') }}</p>
                        <table class="table table-condensed" id="filters-table">
                            <thead>
                                <tr>
                                    <th style="width:120px;">{{ __('orgportal::messages.filter_column_id') }}</th>
                                    <th>{{ __('orgportal::messages.filter_label') }}</th>
                                    <th style="width:40px;"></th>
                                </tr>
                            </thead>
                            <tbody id="filters-rows">
                                @foreach($companyFilters as $filter)
                                <tr class="filter-row">
                                    <td><input type="number" name="company_filters[][id]" class="form-control input-sm" value="{{ $filter['id'] }}" min="1" required></td>
                                    <td><input type="text" name="company_filters[][label]" class="form-control input-sm" value="{{ $filter['label'] }}" required></td>
                                    <td><a href="#" class="btn btn-xs btn-default remove-filter-row"><i class="glyphicon glyphicon-remove"></i></a></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <a href="#" id="add-filter-row" class="btn btn-xs btn-default">
                            <i class="glyphicon glyphicon-plus"></i> {{ __('orgportal::messages.filter_add') }}
                        </a>
                    </div>
                </div>
                <script>
                document.getElementById('add-filter-row').addEventListener('click', function(e) {
                    e.preventDefault();
                    var row = document.createElement('tr');
                    row.className = 'filter-row';
                    row.innerHTML = '<td><input type="number" name="company_filters[][id]" class="form-control input-sm" min="1" required></td>'
                        + '<td><input type="text" name="company_filters[][label]" class="form-control input-sm" required></td>'
                        + '<td><a href="#" class="btn btn-xs btn-default remove-filter-row"><i class="glyphicon glyphicon-remove"></i></a></td>';
                    document.getElementById('filters-rows').appendChild(row);
                    bindRemove(row.querySelector('.remove-filter-row'));
                });
                function bindRemove(btn) {
                    btn.addEventListener('click', function(e) {
                        e.preventDefault();
                        this.closest('tr').remove();
                    });
                }
                document.querySelectorAll('.remove-filter-row').forEach(bindRemove);
                </script>
                @endif

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
