@forelse($components as $component)

  <div v-if="component.key === '{{ $component['key'] }}'" class="relative py-12 lg:px-12">

    <div v-if="tab === 'canvas'" class="relative">
      <iframe src="{{ route('bladepack.canvas', ['component' => $component['key']]) }}" class="border-none absolute inset-0"></iframe>
    </div>

    <div class="prose dark:prose-invert prose-sm max-w-[37.5rem] mx-auto">

      <h2 class="mb-0">
        @{{ component.name }}
      </h2>

      <div
        class="font-mono opacity-50 pb-2 border-b-2 border-gray-200 dark:border-gray-700"
        v-text="component.className || component.path"></div>
      
      <h3 id="implementation" class="relative group">
        <!-- <span class="absolute -left-5 top-1.5 opacity-70 group-hover:opacity-100">
          <x-bladepack::icon.hashtag size="4" color="text-pink-500" />
        </span> -->
        @lang('bladepack::bladepack.implementation')
      </h3>

      <pre class="dark:bg-gray-800 select-all cursor-text"><code v-text="`<x-${component.key}>
    Stuff goes here...
</x-${component.key}>`"></code></pre>

      <h3 id="implementation" class="relative group">
        <!-- <span class="absolute -left-5 top-1.5 opacity-70 group-hover:opacity-100">
          <x-bladepack::icon.hashtag size="4" color="text-pink-500" />
        </span> -->
        @lang('bladepack::bladepack.parameters')
      </h3>

      <ul class="text-sm pl-4" v-if="component.parameters.length > 0">
        <li v-for="parameter in component.parameters">
          <span class="font-mono opacity-60" v-text="parameter.type"></span>
          <span class="font-mono" v-text="'$' + parameter.name"></span>
        </li>
      </ul>

      <div v-if="component.parameters.length <= 0" class="text-gray-500 dark:text-gray-500">None</div>

    </div>

  </div>

@empty

  <p class="flex flex-col items-center">
    <x-bladepack::icon.bladepack size="12" color="text-pink-400 opacity-20" />
    <div class="text-gray-500 dark:text-gray-500 text-xl text-center mt-5">
      @lang('bladepack::bladepack.you_dont_have_any_blade_components_yet')
    </div>
    <pre class="dark:bg-gray-800"><code v-text="`<x-${component.key}>
    // Create one using the artisan make command in your terminal
    php artisan make:component MyAwesomeComponent
</x-${component.key}>`"></code></pre>
  </p>

@endforelse
