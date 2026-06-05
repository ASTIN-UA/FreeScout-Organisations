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

                        <button type="submit" class="btn btn-primary btn-sm">
                            {{ __('orgportal::messages.save') }}
                        </button>
                        <a href="{{ route('orgportal.admin.index') }}" class="btn btn-default btn-sm">
                            {{ __('orgportal::messages.back') }}
                        </a>
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
                                    <th>{{ __('orgportal::messages.role') }}</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($members as $member)
                                <tr>
                                    <td>
                                        @if($member->customer)
                                            {{ $member->customer->getFullName() }}
                                            <small class="text-muted">#{{ $member->customer_id }}</small>
                                        @else
                                            <em class="text-muted">{{ __('orgportal::messages.deleted_customer') }}</em>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="label {{ $member->role === 'manager' ? 'label-primary' : 'label-default' }}">
                                            {{ __('orgportal::messages.' . $member->role) }}
                                        </span>
                                    </td>
                                    <td class="text-right">
                                        <form method="POST"
                                              action="{{ route('orgportal.admin.members.remove', [$organization->id, $member->id]) }}"
                                              onsubmit="return confirm('{{ __('orgportal::messages.confirm_remove_member') }}')">
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
                            <input type="text"
                                   id="customer_search"
                                   class="form-control"
                                   placeholder="{{ __('orgportal::messages.type_name_or_email') }}"
                                   autocomplete="off">
                            <ul id="customer_suggestions"
                                class="list-group"
                                style="position:absolute;z-index:1000;width:100%;display:none;max-height:200px;overflow-y:auto;"></ul>
                        </div>
                        <div class="form-group">
                            <label>{{ __('orgportal::messages.role') }}</label>
                            <select name="role" class="form-control">
                                <option value="member">{{ __('orgportal::messages.member') }}</option>
                                <option value="manager">{{ __('orgportal::messages.manager') }}</option>
                            </select>
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

<script>
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
                    li.className = 'list-group-item list-group-item-action';
                    li.style.cursor = 'pointer';
                    li.textContent = item.text;
                    li.addEventListener('click', function () {
                        searchInput.value  = item.text;
                        hiddenInput.value  = item.id;
                        addBtn.disabled    = false;
                        suggestions.style.display = 'none';
                    });
                    suggestions.appendChild(li);
                });
                suggestions.style.display = 'block';
            });
        }, 300);
    });

    document.addEventListener('click', function (e) {
        if (!suggestions.contains(e.target) && e.target !== searchInput) {
            suggestions.style.display = 'none';
        }
    });
})();
</script>
@endsection
