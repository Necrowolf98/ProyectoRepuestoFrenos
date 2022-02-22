<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <link rel="shortcut icon" href="/img/Logoimpor.ico" />
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Laravel</title>
        <link href="{{ mix('/css/app.css') }}" rel="stylesheet">
        <link href="{{ mix('/css/plantilla.css') }}" rel="stylesheet">

        <script src="{{ mix('/js/app.js') }}" defer></script>
        <script src="{{ mix('/js/plantilla.js') }}" defer></script>

    </head>
    <body class="hold-transition sidebar-mini layout-fixed">
        <div class="wrapper" id="app">
            @if (Auth::user())
                <App ruta="{{ route('basepath') }}" :usuario="{{ \App\Models\User::with('roles', 'people')->find(Auth::id()) }}"></App>
            @else
                <Auth ruta="{{ route('basepath') }}"></Auth>
            @endif
        </div>
    </body>
</html>
