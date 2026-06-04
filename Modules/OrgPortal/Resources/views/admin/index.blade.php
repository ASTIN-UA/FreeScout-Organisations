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
                            <td>{{ $org->members_count }}</td>
                            <td class="text-right">
                                <a href="{{ route('orgportal.admin.edit', $org->id) }}"
                                   class="btn btn-xs btn-default">
                                    {{ __('orgportal::messages.edit') }}
                                </a>
                                <form method="POST"
                                      action="{{ route('orgportal.admin.destroy', $org->id) }}"
                                      style="display:inline;"
                                      onsubmit="return confirm('{{ __('orgportal::messages.confirm_delete_org') }}')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-xs btn-danger">
                                        {{ __('orgportal::messages.delete') }}
                                    </button>
                                </form>
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
</div>
@endsection
