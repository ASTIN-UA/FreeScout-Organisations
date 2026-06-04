@extends('layouts.app')

@section('title', __('New Organization'))

@section('content')
<div class="section-heading">
    {{ __('New Organization') }}
</div>

<div class="container">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">

            @include('partials/flash_messages')

            <form method="POST" action="{{ route('orgportal.admin.store') }}">
                @csrf

                <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                    <label for="name">{{ __('Organization Name') }} <span class="text-danger">*</span></label>
                    <input type="text"
                           id="name"
                           name="name"
                           class="form-control"
                           value="{{ old('name') }}"
                           required
                           maxlength="255">
                    @if($errors->has('name'))
                        <span class="help-block">{{ $errors->first('name') }}</span>
                    @endif
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">
                        {{ __('Create Organization') }}
                    </button>
                    <a href="{{ route('orgportal.admin.index') }}" class="btn btn-default">
                        {{ __('Cancel') }}
                    </a>
                </div>
            </form>

        </div>
    </div>
</div>
@endsection
