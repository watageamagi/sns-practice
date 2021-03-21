<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <title>{{ config('app.name') }}</title>
    <link rel="stylesheet" href="{{ mix('/admin.css', '/dist/css') }}">
    <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
</head>
<body>

    <div id="admin"></div>
    @include('admin.scripts')
</body>
</html>
