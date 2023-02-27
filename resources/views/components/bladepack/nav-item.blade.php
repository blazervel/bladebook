@props(['active', 'text', 'child'])

<button
  type="button"
  wire:click.prevent="{{ $attributes['wire:click'] ?: $attributes['wire:click.prevent'] }}"
  @class([
    'flex',
    'justify-between',
    'gap-2',
    'py-1',
    'pr-3',
    'text-sm',
    'transition',
    'text-zinc-900',
    !! ($child ?? false) ? 'pl-7' : 'pl-4',
    ($active ?? false) ? 'dark:text-white' : 'dark:hover:text-white hover:text-zinc-900 dark:text-zinc-400'
  ])
>
  <span class="truncate">{{ $text }}{{ $slot }}</span>
</button>