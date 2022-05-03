@props(['size', 'color', 'vIf', 'viewBox'])

<svg 
  class="h-{{ $size ?? 4 }} w-{{ $size ?? 4 }} {{ $color ?? 'text-gray-100 dark:text-gray-900' }}" 
  xmlns="http://www.w3.org/2000/svg" 
  fill="none" 
  viewBox="{{ $viewBox ?? '0 0 24 24' }}" 
  stroke="currentColor" 
  stroke-width="2"
  v-if="{{ $vIf ?? 'true' }}"
>
  {{ $slot }}
</svg>