<button {{ $attributes->merge([
  'type' => 'button', 
  'class' => join(' ', [
    $secondary 
      ? 'text-indigo-700 bg-indigo-100 hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500' 
      : 'shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500',
    'inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md'
  ])
]) }}>
  {{ $text ?: $slot }}
</button>