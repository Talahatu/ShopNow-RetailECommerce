<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- jQuery -->
    <script src="{{ asset('js/jquery-3.7.0.min.js') }}"></script>

    <!-- LeafletJS -->
    <link rel="stylesheet" href="{{ asset('leafletjs/leaflet.css') }}" />
    <script src="{{ asset('leafletjs/leaflet.js') }}"></script>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <style>
        html,
        body,
        #app {
            min-height: 100vh;
        }
    </style>
    @laravelPWA
</head>

<body>
    <div id="app" class="py-4" style="background-color: #212529;">
        <main>
            @yield('content')
        </main>
    </div>
    @yield('js')
</body>

</html>
