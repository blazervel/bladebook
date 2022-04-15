<div class="flex items-stretch h-screen p-6 space-x-6 lg:space-x-12 lg:p-12 bg-white dark:bg-black">

  <div class="w-48 flex-shrink-0 overflow-scroll">
    <div class="sticky top-0 py-12">
      @include('bladebook::bladebook.sidebar')
    </div>
  </div>

  <div class="overflow-y-auto flex-1">
    <div class="shadow-2xl dark:shadow-gray-600 max-w-7xl mx-auto rounded-3xl">
      <div class="mx-auto max-w-2xl p-4">
        @include('bladebook::bladebook.main')
      </div>
    </div>
  </div>

  <div class="lg:w-48"></div>

</div>

<div>
  @include('bladebook::bladebook.search')
</div>