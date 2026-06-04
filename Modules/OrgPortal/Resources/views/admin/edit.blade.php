@extends('layouts.app')

@section('title', __('Edit Organization'))

@section('content')
<div class="section-heading">
    {{ __('Edit Organization') }}: {{ $organization->name }}
</div>

<div class="container">
    <div class="row">

        @include('partials/flash_messages')

        {{-- Organization name form --}}
        <div class="col-md-5">
            <div class="panel panel-default">
                <div class="panel-heading"><strong>{{ __('Organization Details') }}</strong></div>
                <div class="panel-body">
                    <form method="POST" action="{{ route('orgportal.admin.update', $organization->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                            <label for="name">{{ __('Name') }}</label>
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
                            {{ __('Save') }}
                        </button>
                        <a href="{{ route('orgportal.admin.index') }}" class="btn btn-default btn-sm">
                            {{ __('Back') }}
                        </a>
                    </form>
                </div>
            </div>
        </div>

        {{-- Members list --}}
        <div class="col-md-7">
            <div class="panel panel-default">
                <div class="panel-heading"><strong>{{ __('Members') }}</strong></div>
                <div class="panel-body">

                    @if($members->count())
                        <table class="table table-condensed table-striped">
                            <thead>
                                <tr>
                                    <th>{{ __('Customer') }}</th>
                                    <th>{{ __('Role') }}</th>
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
                                            <em class="text-muted">{{ __('Deleted customer') }}</em>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="label {{ $member->role === 'manager' ? 'label-primary' : 'label-default' }}">
                                            {{ ucfirst($member->role) }}
                                        </span>
                                    </td>
                                    <td class="text-right">
                                        <form method="POST"
                                              action="{{ route('orgportal.admin.members.remove', [$organization->id, $member->id]) }}"
                                              onsubmit="return confirm('{{ __('Remove this member?') }}')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-xs btn-danger">
                                                {{ __('Remove') }}
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p class="text-muted">{{ __('No members yet.') }}</p>
                    @endif

                    {{-- Add member form --}}
                    <hr>
                    <h5>{{ __('Add Member') }}</h5>
                    <form method="POST" action="{{ route('orgportal.admin.members.add', $organization->id) }}">
                        @csrf
                        <div class="form-group">
                            <label>{{ __('Search customer') }}</label>
                            <input type="hidden" id="customer_id" name="customer_id" required>
                            <input type="text"
                                   id="customer_search"
                                   class="form-control"
                                   placeholder="{{ __('Type name or email…') }}"
                                   autocomplete="off">
                            <ul id="customer_suggestions"
                                class="list-group"
                                style="position:absolute;z-index:1000;width:100%;display:none;max-height:200px;overflow-y:auto;"></ul>
                        </div>
                        <div class="form-group">
                            <label>{{ __('Role') }}</label>
                            <select name="role" class="form-control">
                                <option value="member">{{ __('Member') }}</option>
                                <option value="manager">{{ __('Manager') }}</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-success btn-sm" id="add_member_btn" disabled>
                            {{ __('Add Member') }}
                        </button>
                    </form>

                </div>
            </div>
        </div>{{-- /col-md-7 --}}

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
