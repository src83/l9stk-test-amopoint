@extends('layouts.cabinet')

@push ('custom_css')
<link href="{{ asset('vendor/customTools/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">
<link href="{{ asset('vendor/customTools/material-symbols/index.css') }}" rel="stylesheet">
<link href="{{ asset('css/cabinet/index.css') }}" rel="stylesheet">
@endpush

@section('content')
    <div class="application">

        <div class="starter-template">
            <h1>Bootstrap starter template</h1>
            <p class="lead">Use this document as a way to quickly start any new project.<br> All you get is this text and a mostly barebones HTML document.</p>
        </div>

    </div>
@endsection

@push ('scripts')
    <script src="{{ asset("js/cabinet/index/index.min.js") }}" defer></script>
@endpush
