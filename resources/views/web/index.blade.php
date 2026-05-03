@extends('layouts.web')

@push ('custom_css')
<link href="{{ asset('css/web/index.css') }}" rel="stylesheet">
@endpush

@section('content')

    <!-- Jumbotron -->
    <div class="jumbotron">
        <h1>Just sign in for start</h1>
        <p class="lead">........</p>
        <br>
        <br>
        <p><a class="btn btn-lg btn-success" href="{{ route('showLoginForm') }}" role="button">Get started today</a></p>
    </div>

@endsection

@push ('scripts')
@endpush
