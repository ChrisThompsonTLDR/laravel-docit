{{-- Protocol-style header: spans main content area, search + nav + theme --}}
<header id="docs-header" class="hidden lg:flex fixed top-0 right-0 h-14 items-center justify-between gap-12 px-4 sm:px-6 lg:left-72 lg:z-30 lg:px-8 xl:left-80 backdrop-blur-sm bg-white/80 dark:bg-zinc-900/80 border-b border-zinc-900/10 dark:border-white/10">
    <button type="button" @click="searchWindowOpen = true"
            class="flex h-8 w-full max-w-md items-center gap-2 rounded-full bg-white pr-3 pl-3 text-sm text-zinc-500 ring-1 ring-zinc-900/10 transition hover:ring-zinc-900/20 dark:bg-white/5 dark:text-zinc-400 dark:ring-white/10 dark:hover:ring-white/20">
        <svg viewBox="0 0 20 20" fill="none" aria-hidden="true" class="h-5 w-5 stroke-zinc-500">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12.01 12a4.25 4.25 0 1 0-6.02-6 4.25 4.25 0 0 0 6.02 6Zm0 0 3.24 3.25"/>
        </svg>
        Find something...
        <kbd class="ml-auto text-xs text-zinc-400 dark:text-zinc-500 font-sans">Ctrl K</kbd>
    </button>
    <div class="flex items-center gap-5">
        <nav class="hidden md:block">
            <ul role="list" class="flex items-center gap-8">
                <li>
                    <a href="{{ \Hyde\Pages\DocumentationPage::home() }}" class="text-sm text-zinc-600 transition hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-white">Guide</a>
                </li>
                @php $docsHome = (string) \Hyde\Pages\DocumentationPage::home(); @endphp
                @foreach (app('navigation.main')->getItems() as $item)
                    @if(! $item instanceof \Hyde\Framework\Features\Navigation\NavigationGroup && $item->getLink() !== $docsHome)
                        <li>
                            <a href="{{ $item->getLink() }}" class="text-sm text-zinc-600 transition hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-white">{{ $item->getLabel() }}</a>
                        </li>
                    @endif
                @endforeach
            </ul>
        </nav>
        <div class="hidden md:block h-5 w-px bg-zinc-900/10 dark:bg-white/15"></div>
        <x-hyde::navigation.theme-toggle-button class="opacity-75 hover:opacity-100"/>
    </div>
</header>
