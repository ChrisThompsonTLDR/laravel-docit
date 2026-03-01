<nav id="mobile-navigation" class="bg-white dark:bg-zinc-900 border-b border-zinc-900/10 dark:border-white/10 md:hidden flex justify-between w-full h-16 z-40 fixed left-0 top-0 p-4 leading-8 shadow-lg print:hidden">
    <strong class="px-2 mr-auto">
        @if(DocumentationPage::home() !== null)
            <a href="{{ DocumentationPage::home() }}">
                {{ config('docs.sidebar.header', 'Documentation') }}
            </a>
        @else
            {{ config('docs.sidebar.header', 'Documentation') }}
        @endif
    </strong>
    <ul class="flex items-center">
        <li class="h-8 flex mr-1">
            <x-hyde::navigation.theme-toggle-button class="opacity-75 hover:opacity-100"/>
        </li>
        <li class="h-8 flex">
            @include('hyde::components.docs.sidebar-toggle-button')
        </li>
    </ul>
</nav>