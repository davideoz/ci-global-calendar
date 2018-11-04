
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <title>App Name - @yield('title')</title>
    {{--<title>{{ config('app.name', 'Laravel') }}</title>--}}

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- CSS-->
        <!--<link rel="stylesheet" type="text/css" href="/css/vendor.css">
        <link rel="stylesheet" type="text/css" href="/css/app.css">
        <link rel="stylesheet" type="text/css" href="/css/custom.css">-->
        <link href="{{ asset('css/vendor.css') }}" rel="stylesheet">
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
</head>

<body class="pb-5"> <!-- Laravel use VUE as default - https://stackoverflow.com/questions/41411344/vue-warn-cannot-find-element-app#41411385-->
    {{-- {!! menu('main', 'nav') !!} --}}
    @include('menus.nav', ['items' => $MyNavBar->roots()])

    <div id="app" class="container pt-5">
            @yield('content')
    </div>

    <!-- JS -->
        <!--<script src="/js/manifest.js"></script>
        <script src="/js/vendor.js"></script>
        <script src="/js/app.js"></script>-->

        <script src="{{ asset('js/manifest.js') }}" defer></script>
        <script src="{{ asset('js/vendor.js') }}" defer></script>
        <script src="{{ asset('js/app.js') }}" defer></script>

        @yield('javascript')
</body>
</html>
