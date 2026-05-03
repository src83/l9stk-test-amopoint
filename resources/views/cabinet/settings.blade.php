@extends('layouts.cabinet')

@push ('custom_css')
<link href="{{ asset('css/cabinet/settings.css') }}" rel="stylesheet">
@endpush

@section('content')
    <div class="application">

        <div class="row">
            <div class="col-md-12">

                <h4 class="mb-3">Settings – change password</h4>

                <hr class="mb-3">

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="username">Username</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="username" value="{{ auth()->user()->name }}" disabled>
                        </div>
                    </div>

                    <div class="col-md-6 mb-2">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" value="{{ auth()->user()->email }}" disabled>
                    </div>
                </div>

                <hr class="mb-3">

                @if(session('success'))
                    <div class="alert alert-success mb-3">
                        {{ session('success') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('cabinet.user.settings.password.update') }}" class="needs-validation" autocomplete="off">
                    @csrf
                    @method('patch')

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="current_password">Current Password</label>
                            <input type="password" class="form-control" id="current_password" name="current_password" maxlength="100" autocomplete="new-password" required autofocus>
                            @error('current_password')<div class="text-danger">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="password">New Password</label>
                            <input type="password" class="form-control" id="password" name="password" maxlength="100" autocomplete="new-password" required>
                            @error('password')<div class="text-danger">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="password_confirmation">New Password confirmation</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" maxlength="100" autocomplete="new-password" required>
                            @error('password_confirmation')<div class="text-danger">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="logout_other_sessions" name="logout_other_sessions" value="1" checked>
                        <label class="form-check-label" for="logout_other_sessions">Log out sessions from other devices</label>
                    </div>

                    <hr class="mb-3">

                    <button class="btn btn-primary btn-lg btn-block" type="submit">Update Password</button>
                </form>

            </div>
        </div>

    </div>
@endsection

@push ('scripts')
    <script src="{{ asset("js/cabinet/settings/index.min.js") }}" defer></script>
@endpush
