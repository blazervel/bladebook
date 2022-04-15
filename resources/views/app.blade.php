<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@lang('bladebook::bladebook.bladebook')</title>
    <link rel="shortcut icon" href="{{ asset('images/favicon.png') }}"/>
    <script src="https://unpkg.com/petite-vue" defer init></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography,aspect-ratio,line-clamp"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.27.0/prism.min.js"></script>
    <style>[v-cloak] { display: none }</style>
    @include('bladebook::tailwind-config')
  </head>
  <body class="font-sans antialiased h-full">
    @yield('content') 
  </body>
</html>