<h2 class="text-base font-bold dark:text-gray-500 flex space-x-1 items-center">
  <x-bladepack::icon.bladepack size="6" color="text-pink-500 opacity-70"/>
  <span>@lang('bladepack::bladepack.bladepack')</span>
</h2>
<p class="dark:text-gray-600 text-xs mt-6 border-l-2 pl-2 dark:border-gray-800">
  {!! trans_choice(
    'bladepack::bladepack.you_currently_have_x_components', 
    count($components)
  ) !!}
</p>

<div class="mt-6 space-y-1.5">

  <div v-for="folder in folders">

    <button
      @click="folder.open = !folder.open; if (folder.parentComponent) { setComponent(folder.parentComponent); }"
      v-effect="folder.active = component && component.key === folder.parentComponent.key" 
      class="group flex items-start space-x-1 w-full"
      v-if="folder.key != 'none'"
    >
      <div class="pt-0.5">
        <x-bladepack::icon.folder v-if="!(folder.open || folder.active)" color="text-pink-500 opacity-50" />
        <x-bladepack::icon.folder-open v-if="(folder.open || folder.active)" color="text-pink-500 opacity-50" />
      </div>

      <div 
        class="text-sm"
        v-bind:class="( 
          folder.parentComponent && folder.active 
            ? 'font-medium text-gray-900 dark:text-gray-200' 
            : 'text-gray-400 dark:text-gray-500 group-hover:text-gray-600 dark:group-hover:text-gray-300 transition-colors' 
      )">
        <span class="truncate" v-text="(folder.parentComponent && folder.parentComponent.name) || folder.name"></span>
      </div>
    </button>

    <div v-if="folder.key === 'none' || folder.open" class="mt-1.5 space-y-1.5" v-bind:class="{ 'pl-4': folder.key !== 'none' }">
      <button 
        v-for="c in folder.components" 
        v-key="c.key" 
        @click="component = c; window.location.hash = c.key"
        v-effect="c.active = component && component.key === c.key" 
        class="group flex items-start space-x-1 w-full"
      >

        <div class="pt-0.5">
          <x-bladepack::icon.document v-if="!c.active" color="text-pink-500 group-hover:opacity-100 transition-colors opacity-50" />
          <x-bladepack::icon.document-text v-if="c.active" color="text-pink-500" />
        </div>

        <div 
          class="text-sm"
          v-bind:class="( 
            c.active 
              ? 'font-medium text-gray-900 dark:text-gray-200' 
              : 'text-gray-400 dark:text-gray-500 group-hover:text-gray-600 dark:group-hover:text-gray-300 transition-colors' 
        )">
          <span class="truncate">@{{ c.name }}</span>
        </div>

      </button>
    </div>

  </div>

</div>