@extends('layouts.cabinet')

@push ('custom_css')
<link href="{{ asset('vendor/customTools/docs/docs.css') }}" rel="stylesheet">
<link href="{{ asset('css/cabinet/example/app.css') }}" rel="stylesheet">
@endpush

@section('content')
    <div class="application">

        <div class="row justify-content-center">
            <div class="col-md-10">

                <div class="starter-template">
                    <h1>Example module</h1>
                    <p class="lead">Placeholder for new features.<br> Now create something great!</p>
                </div>

            </div>
        </div>

    </div>
@endsection

@push ('scripts')
    <script src="{{ asset("vendor/customTools/docs/docs.min.js") }}"></script>
    <script src="{{ asset("js/cabinet/example/app.min.js") }}" defer></script>
@endpush
