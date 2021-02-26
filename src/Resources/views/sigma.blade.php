<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0"/>
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font@4.x/css/materialdesignicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('vendor/sigma/app.css') }}">
    <script src="{{ asset('vendor/sigma/app.js') }}" defer></script>
    <style>
        #app {
            font-family: Roboto,serif;
            font-size: 13px;
            line-height: 18px;
            color: #6a6a6a;
            font-weight: 400;
            background-color: #fff;
            overflow-x: hidden!important;
            -webkit-font-smoothing: antialiased;
        /*    1354/84 */
        }

        .log-table tr td{
            font-size: 0.7rem!important;
        }


    </style>

    @routes
</head>
<body class="antialiased">
    @inertia
</body>
</html>
