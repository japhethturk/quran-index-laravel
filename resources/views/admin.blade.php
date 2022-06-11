<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8" />

        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Adminpanel</title>

        <link rel="icon" href="{{ asset('/favicon.ico') }}" />

        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap"/>
        <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons"/>
        
        <!-- Styles -->
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">

        <script type="text/javascript">
            var api = "{!! $data->api !!}";
        </script>

    </head>
    <body>

        <div id="app"></div>

        <script src="{{ asset('js/app.js') }}"></script>

    </body>



</html>
