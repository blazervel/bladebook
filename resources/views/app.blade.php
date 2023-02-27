<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark js-focus-visible">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@lang('bladepack::bladepack.bladepack')</title>
    <link rel="shortcut icon" href="/bladepack/logo.png"/>
    <style>[v-cloak] { display: none }</style>
    <link rel="stylesheet" href="/bladepack/css/app.css">
    @livewireStyles
    <script defer src="/bladepack/js/app.js"></script>
  </head>
  <body class="bg-white antialiased dark:bg-zinc-900 font-sans antialiased h-full">
    {{ $slot }}
    @livewireScripts
  </body>
</html>