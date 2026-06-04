@extends('layouts.app')

@section('title', __('Organizations'))

@section('content')
<div class="section-heading">
    {{ __('Organizations') }}
</div>

<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">

            @include('partials/flash_messages')

            <div class="margin-bottom">
                <a href="{{ route('orgportal.admin.create') }}" class="btn btn-primary">
                    <i class="glyphicon glyphicon-plus"></i> {{ __('New Organization') }}
                </a>
            </div>

            @if($organizations->count())
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('Members') }}</th>
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
                            <td>{{ $org->members()->count() }}</td>
                            <td class="text-right">
                                <a href="{{ route('orgportal.admin.edit', $org->id) }}"
                                   class="btn btn-xs btn-default">
                                    {{ __('Edit') }}
                                </a>
                                <form method="POST"
                                      action="{{ route('orgportal.admin.destroy', $org->id) }}"
                                      style="display:inline;"
                                      onsubmit="return confirm('{{ __('Delete this organization?') }}')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-xs btn-danger">
                                        {{ __('Delete') }}
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
                    {{ __('No organizations yet.') }}
                    <a href="{{ route('orgportal.admin.create') }}">{{ __('Create one') }}</a>.
                </div>
            @endif

        </div>
    </div>
</div>
@endsection
