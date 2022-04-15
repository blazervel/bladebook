<div class="relative py-12 lg:px-12">
  <div class="prose dark:prose-invert prose-sm max-w-[37.5rem] mx-auto">

    <h2 class="mb-0">
      <span v-text="component.inDirectory" class="opacity-80"></span>@{{ component.name }}
    </h2>
    <div class="font-mono opacity-50 pb-2 border-b-2 border-gray-200 dark:border-gray-700" v-text="component.className"></div>
    
    <h3 id="implementation" class="relative group">
      <span class="absolute -left-5 top-1.5 opacity-70 group-hover:opacity-100">
        <x-bladebook::icon.hashtag size="4" color="text-pink-500" />
      </span>
      @lang('bladebook::bladebook.implementation')
    </h3>

    <pre class="dark:bg-gray-800"><code v-text="`<x-${component.key}>
  Stuff goes here...
</x-${component.key}>`"></code></pre>

    <h3 id="implementation" class="relative group">
      <span class="absolute -left-5 top-1.5 opacity-70 group-hover:opacity-100">
        <x-bladebook::icon.hashtag size="4" color="text-pink-500" />
      </span>
      @lang('bladebook::bladebook.parameters')
    </h3>

    <ul class="text-sm">
      <li v-for="parameter in component.parameters">
        <span class="font-mono opacity-60" v-text="parameter.type"></span>
        <span class="font-mono" v-text="'$' + parameter.name"></span>
      </li>
    </ul>

</div>
