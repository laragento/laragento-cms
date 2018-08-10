<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @yield('meta')
        <title>Module Cms</title>

       {{-- Laravel Mix - CSS File --}}
       <link rel="stylesheet" href="{{ mix('css/app.css') }}">
        @yield('css')
        @yield('topscripts')
    </head>
    <body>
        @yield('content')
        @yield('bottomscripts')

        {{-- Laravel Mix - JS File --}}
        {{-- <script src="{{ mix('js/cms.js') }}"></script> --}}
    </body>
</html>
