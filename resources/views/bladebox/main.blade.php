@forelse($components as $component)

  <div v-if="component.key === c.key" class="relative py-12 lg:px-12">
    
        <!--
      This example requires Tailwind CSS v2.0+ 
      
      This example requires some changes to your config:
      
      ```
      // tailwind.config.js
      module.exports = {
        // ...
        plugins: [
          // ...
          require('@tailwindcss/forms'),
        ],
      }
      ```
    -->
    <div>
      <div class="sm:hidden">
        <label for="tabs" class="sr-only">Select a tab</label>
        <!-- Use an "onChange" listener to redirect the user to the selected tab URL. -->
        <select id="tabs" name="tabs" class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
          <option>Applied</option>

          <option>Phone Screening</option>

          <option selected>Interview</option>

          <option>Offer</option>

          <option>Disqualified</option>
        </select>
      </div>
      <div class="hidden sm:block">
        <div class="border-b border-gray-200">
          <nav class="-mb-px flex space-x-8" aria-label="Tabs">
            <!-- Current: "border-indigo-500 text-indigo-600", Default: "border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-200" -->
            <a href="#" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-200 whitespace-nowrap flex py-4 px-1 border-b-2 font-medium text-sm">
              Applied

              <!-- Current: "bg-indigo-100 text-indigo-600", Default: "bg-gray-100 text-gray-900" -->
              <span class="bg-gray-100 text-gray-900 hidden ml-3 py-0.5 px-2.5 rounded-full text-xs font-medium md:inline-block">52</span>
            </a>

            <a href="#" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-200 whitespace-nowrap flex py-4 px-1 border-b-2 font-medium text-sm">
              Phone Screening

              <span class="bg-gray-100 text-gray-900 hidden ml-3 py-0.5 px-2.5 rounded-full text-xs font-medium md:inline-block">6</span>
            </a>

            <a href="#" class="border-indigo-500 text-indigo-600 whitespace-nowrap flex py-4 px-1 border-b-2 font-medium text-sm" aria-current="page">
              Interview

              <span class="bg-indigo-100 text-indigo-600 hidden ml-3 py-0.5 px-2.5 rounded-full text-xs font-medium md:inline-block">4</span>
            </a>

            <a href="#" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-200 whitespace-nowrap flex py-4 px-1 border-b-2 font-medium text-sm"> Offer </a>

            <a href="#" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-200 whitespace-nowrap flex py-4 px-1 border-b-2 font-medium text-sm"> Disqualified </a>
          </nav>
        </div>
      </div>
    </div>

    <div v-if="tab === 'canvas'" class="relative">
      <iframe src="{{ route('bladepack.canvas', ['component' => $component['key']]) }}" class="border-none absolute inset-0"></iframe>
    </div>

    <div class="prose dark:prose-invert prose-sm max-w-[37.5rem] mx-auto">

      <h2 class="mb-0">
        <span v-text="component.inDirectory" class="opacity-80"></span>@{{ c.name }}
      </h2>
      <div class="font-mono opacity-50 pb-2 border-b-2 border-gray-200 dark:border-gray-700" v-text="c.className"></div>
      
      <h3 id="implementation" class="relative group">
        <span class="absolute -left-5 top-1.5 opacity-70 group-hover:opacity-100">
          <x-bladepack::icon.hashtag size="4" color="text-pink-500" />
        </span>
        @lang('bladepack::bladepack.implementation')
      </h3>

      <pre class="dark:bg-gray-800"><code v-text="`<x-${c.key}>
    Stuff goes here...
  </x-${c.key}>`"></code></pre>

      <h3 id="implementation" class="relative group">
        <span class="absolute -left-5 top-1.5 opacity-70 group-hover:opacity-100">
          <x-bladepack::icon.hashtag size="4" color="text-pink-500" />
        </span>
        @lang('bladepack::bladepack.parameters')
      </h3>

      <ul class="text-sm">
        <li v-for="parameter in c.parameters">
          <span class="font-mono opacity-60" v-text="parameter.type"></span>
          <span class="font-mono" v-text="'$' + parameter.name"></span>
        </li>
      </ul>

    </div>

  </div>

@empty

  <p class="flex flex-col items-center">
    <x-bladepack::bladepack size="12" color="text-pink-400 opacity-20" />
    <div class="text-gray-500 dark:text-gray-500 text-xl text-center mt-5">
      @lang('bladepack::bladepack.you_dont_have_any_blade_components_yet')
    </div>
    <div class="flex justify-center space-x-4 mt-8">
      <x-bladepack::button text="bladepack::bladepack.make_one_for_me" />
      <x-bladepack::button secondary text="bladepack::bladepack.view_bladepack_components" />
    </div>
  </p>

@endforelse
