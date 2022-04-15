<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@lang('bladebook::bladebook.bladebook')</title>
    <link rel="shortcut icon" href="{{ asset('images/favicon.png') }}"/>
    <script src="https://unpkg.com/petite-vue" defer init></script>
    <script src="https://cdn.tailwindcss.com"></script>
    @include('bladebook::tailwind-config')
  </head>
  <body class="font-sans antialiased">
    @yield('content') 
  </body>
</html>