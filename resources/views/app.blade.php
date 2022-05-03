<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@lang('bladepack::bladepack.bladepack')</title>
    <link rel="shortcut icon" href="{{ asset('images/favicon.png') }}"/>
    <script src="https://unpkg.com/petite-vue" defer init></script>
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography,aspect-ratio,line-clamp"></script>
    <style>[v-cloak] { display: none }</style>
    @include('bladepack::tailwind-config')
  </head>
  <body class="font-sans antialiased h-full">
    @yield('content') 
  </body>
</html>