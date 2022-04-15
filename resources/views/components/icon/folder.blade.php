@props(['size', 'color', 'vIf'])

<x-bladebook::icon 
  :size="$size ?? null" 
  :color="$color ?? null"
  :v-if="$vIf ?? true"
>
  <path stroke-linecap="round" stroke-linejoin="round" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
</x-bladebook::icon>