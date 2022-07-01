<div class="flex items-stretch h-screen px-6 lg:px-12 bg-gray-50 dark:bg-black">

  <div class="w-48 flex-shrink-0 overflow-y-hidden hover:overflow-y-scroll">
    <div class="sticky top-0 py-10">
      @include('bladepack::bladepack.sidebar')
    </div>
  </div>

  <div class="flex-1 p-10">
    <div class="overflow-y-auto shadow-2xl dark:shadow-gray-600 max-w-7xl mx-auto rounded-3xl h-full bg-white dark:bg-black">
      <div class="mx-auto max-w-2xl p-4">
        @include('bladepack::bladepack.main')
      </div>
    </div>
  </div>

  <div class="lg:w-48"></div>

</div>

<div>
  @include('bladepack::bladepack.search')
</div>