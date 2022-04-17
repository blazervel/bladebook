@props(['size', 'color', 'vIf'])

<x-bladebox::icon 
  :size="$size ?? null" 
  :color="$color ?? null"
  :v-if="$vIf ?? true"
>
  <path stroke-linecap="round" stroke-linejoin="round" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14" />
</x-bladebox::icon>