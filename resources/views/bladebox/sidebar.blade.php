<h2 class="text-base font-bold dark:text-gray-500 flex space-x-1 items-center">
  <x-bladebox::icon.bladebox size="6" color="text-pink-500 opacity-70"/>
  <span>@lang('bladebox::bladebox.bladebox')</span>
</h2>
<p class="dark:text-gray-600 text-xs mt-6 border-l-2 pl-2 dark:border-gray-800">
  {!! trans_choice(
    'bladebox::bladebox.you_currently_have_x_components', 
    count($components)
  ) !!}
</p>

<div class="mt-6 space-y-2">

  <button 
    v-for="c in components" 
    v-key="c.key" 
    @click="component = c" 
    v-effect="c.active = component && component.key === c.key" 
    class="flex items-start space-x-1 group"
  >

    <!--
    <div v-if="c.isDirectory" class="pt-0.5">
      <x-bladebox::icon.folder v-if="!c.active" color="text-pink-500 opacity-50" />
      <x-bladebox::icon.folder-open v-if="c.active" color="text-pink-500" />
    </div>
    -->

    <div v-if="!c.isDirectory" class="pt-0.5">
      <x-bladebox::icon.document v-if="!c.active" color="text-pink-500 group-hover:opacity-100 transition-colors opacity-50" />
      <x-bladebox::icon.document-text v-if="c.active" color="text-pink-500" />
    </div>

    <div 
      class="text-sm"
      v-bind:class="( 
        c.active 
          ? 'font-medium text-gray-900 dark:text-gray-200' 
          : 'text-gray-400 dark:text-gray-500 group-hover:text-gray-600 dark:group-hover:text-gray-300 transition-colors' 
    )">
      <span v-text="c.inDirectory" class="opacity-80"></span>@{{ c.name }}
    </div>

  </button>

</div>