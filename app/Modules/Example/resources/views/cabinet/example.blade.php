@extends('layouts.cabinet')

@push ('custom_css')
<link href="{{ asset('vendor/customTools/docs/docs.css') }}" rel="stylesheet">
<link href="{{ asset('css/cabinet/example/app.css') }}" rel="stylesheet">
@endpush

@section('content')
    <div class="application">

        <div class="row justify-content-center">
            <div class="col-md-10">

                <p>Тип &nbsp; &nbsp;<select name="type_val"><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option></select></p>

                <p>Поле 1&nbsp; &nbsp;<input name="input_1" type="text" /></p>

                <p>&nbsp;</p>

                <p>Поле 2&nbsp; &nbsp;<input name="input_2" type="text" /></p>

                <p>&nbsp;</p>

                <p>Поле 3&nbsp; &nbsp;<input name="input_3" type="text" /></p>

                <p>Поле 4&nbsp; &nbsp;<input name="input_4" type="text" /></p>

                <p>Поле 5&nbsp; &nbsp;<input name="input_5" type="text" /></p>

                <p>Поле 6&nbsp; &nbsp;<input name="input_6" type="text" /></p>

                <p>Поле 7&nbsp; &nbsp;<input name="input_7" type="text" /></p>

                <p><input name="button_12" type="button" value="Кнопка 1" /></p>

                <p><input name="button_28" type="button" value="Кнопка 2" /></p>

                <p><input name="button_88" type="button" value="Кнопка 4" /></p>

                <p><input name="button_33" type="button" value="Кнопка 3" /></p>

                <p><input name="button_1" type="button" value="Кнопка 8" /></p>

            </div>
        </div>

    </div>
@endsection

@push ('scripts')
    <script src="{{ asset("vendor/customTools/docs/docs.min.js") }}"></script>
    <script src="{{ asset("js/cabinet/example/app.min.js") }}" defer></script>
@endpush
