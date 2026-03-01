<!DOCTYPE html>
<html lang="{{ config('hyde.language', 'en') }}" class="scroll-smooth">
<head>
    @include('hyde::layouts.head')
</head>
<body id="hyde-docs" class="bg-white dark:bg-zinc-900 dark:text-zinc-100 min-h-screen w-screen relative overflow-x-hidden overflow-y-auto"
      x-data="{ sidebarOpen: false, searchWindowOpen: false }"
      x-on:keydown.escape="searchWindowOpen = false; sidebarOpen = false"
      x-on:keydown.slash.window="searchWindowOpen = true"
      @keydown.window.prevent.ctrl.k="searchWindowOpen = true"
      @keydown.window.prevent.meta.k="searchWindowOpen = true">

    @include('hyde::components.skip-to-content-button')
    @include('hyde::components.docs.mobile-navigation')
    @include('hyde::components.docs.sidebar')
    @include('hyde::components.docs.docs-header')

    <main id="content" class="dark:bg-zinc-900 min-h-screen bg-white absolute top-16 md:top-0 w-screen md:left-64 md:w-[calc(100vw_-_16rem)] lg:left-72 lg:w-[calc(100vw_-_18rem)] lg:pt-14 xl:left-80 xl:w-[calc(100vw_-_20rem)] print:top-0">
        @include('hyde::components.docs.documentation-article')
    </main>

    <div id="support">
        @include('hyde::components.docs.sidebar-backdrop')

        @if(Hyde\Facades\Features::hasDocumentationSearch())
            @include('hyde::components.docs.search-modal')
        @endif
    </div>

    @include('hyde::layouts.scripts')
</body>
</html>
