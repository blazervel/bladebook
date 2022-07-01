@forelse($components as $component)

  <div v-if="component && component.key === '{{ $component['key'] }}'" class="relative py-12 lg:px-12">

    <div class="max-w-[37.5rem] mx-auto space-y-6">

      <section class="prose dark:prose-invert prose-sm">

        <h1 class="mb-0" v-text="component.name"></h1>

        <div class="mt-2 text-xs space-y-1 pb-2 border-b-2 border-gray-200 dark:border-gray-700 text-gray-500">
          <div v-if="component.className" class="text-gray-500">
            <span class="w-10 inline-block font-bold">@lang('bladepack::bladepack.class')</span>
            <span class="font-mono select-all" v-text="component.className"></span>
          </div>
          <div v-if="component.path" class="text-gray-500">
            <span class="w-10 inline-block font-bold">@lang('bladepack::bladepack.view')</span>
            <span class="font-mono select-all" v-text="component.path"></span>
          </div>
          <div class="text-gray-500">
            <span class="w-10 inline-block font-bold">@lang('bladepack::bladepack.tag')</span>
            <span class="font-mono select-all"><span v-text="`<x-${component.key}>`"></span></span>
          </div>
        </div>

      </section>
      
      <section class="prose dark:prose-invert prose-sm">
        <h3 id="implementation" class="relative group">
          <!-- <span class="absolute -left-5 top-1.5 opacity-70 group-hover:opacity-100">
            <x-bladepack::icon.hashtag size="4" color="text-pink-500" />
          </span> -->
          @lang('bladepack::bladepack.implementation')
        </h3>

        {{--  ${component.props.map(param => param.name + '="' + param.type + '" ')} --}}
        <pre class="dark:bg-gray-800 select-all cursor-text rounded p-4"><code v-text="`<x-${component.key}>
      Stuff goes here...
  </x-${component.key}>`"></code></pre>

        <h3 id="implementation" class="relative group">
          <!-- <span class="absolute -left-5 top-1.5 opacity-70 group-hover:opacity-100">
            <x-bladepack::icon.hashtag size="4" color="text-pink-500" />
          </span> -->
          @lang('bladepack::bladepack.props')
        </h3>

        <ul class="text-sm pl-4" v-if="component.props.length > 0">
          <li v-for="parameter in component.props">
            <span class="font-mono opacity-60" v-text="parameter.type"></span>
            <span class="font-mono" v-text="'$' + parameter.name"></span>
          </li>
        </ul>

        <div v-if="component.props.length <= 0" class="text-gray-500 dark:text-gray-500">None</div>

      </section>

      @foreach($component['packs'] as $i => $pack)
        <section>

          <div class="prose dark:prose-invert prose-sm">
            <h3 id="example-{{ $i }}" class="relative group">
              <!-- <span class="absolute -left-5 top-1.5 opacity-70 group-hover:opacity-100">
                <x-bladepack::icon.hashtag size="4" color="text-pink-500" />
              </span> -->
              @php $i++ @endphp
              {{ "Example #{$i}" }}{{ !empty($pack['name']) ? ": {$pack['name']}" : '' }}
            </h3>
          </div>
            
          <div class="mt-2 p-4 bg-white rounded">
            @if($component['className'])
              @if($pack['props'])
                {{ (new $component['className'](...$pack['props']))->render() }}
              @else
                {{ (new $component['className'])->render() }}
              @endif
            @else
              @include(str_replace('.blade.php', '', str_replace('resources/views/', '', $component['path'])), $pack['props'] ?? [])
            @endif
          </div>
        
        </section>
      @endforeach

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
