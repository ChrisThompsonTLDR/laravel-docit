@props(['grouped' => false])
@use('Hyde\Framework\Actions\GeneratesTableOfContents')
@php /** @var \Hyde\Framework\Features\Navigation\NavigationItem $item */ @endphp
<li @class(['sidebar-item -ml-4 pl-4', $grouped
        ? 'active -ml-8 pl-8 bg-zinc-900/5 dark:bg-white/5'
        : 'active bg-zinc-900/5 dark:bg-white/5' => $item->isActive()
    ]) role="listitem">
    @if($item->isActive())
        <a href="{{ $item->getLink() }}" aria-current="true" @class([$grouped
            ? '-ml-8 pl-4 py-1 px-2 block text-emerald-600 dark:text-emerald-400 dark:font-medium border-l-[0.325rem] border-emerald-500 transition-colors duration-300 ease-in-out hover:bg-zinc-900/10 dark:hover:bg-white/10'
            : '-ml-4 p-2 block hover:bg-zinc-900/5 dark:hover:bg-white/5 text-emerald-600 dark:text-emerald-400 dark:font-medium border-l-[0.325rem] border-emerald-500 transition-colors duration-300 ease-in-out'
        ])>
            {{ $item->getLabel() }}
        </a>

        @if(config('docs.sidebar.table_of_contents.enabled', true))
            <span class="sr-only">Table of contents</span>
            <x-hyde::docs.table-of-contents :items="(new GeneratesTableOfContents($page->markdown))->execute()" />
        @endif
    @else
        <a href="{{ $item->getLink() }}" @class([$grouped
            ? '-ml-8 pl-4 py-1 px-2 block text-zinc-600 dark:text-zinc-400 border-l-[0.325rem] border-transparent transition-colors duration-300 ease-in-out hover:bg-zinc-900/10 dark:hover:bg-white/10 hover:text-zinc-900 dark:hover:text-white'
            : 'block -ml-4 p-2 text-zinc-600 dark:text-zinc-400 border-l-[0.325rem] border-transparent hover:bg-zinc-900/5 dark:hover:bg-white/5 hover:text-zinc-900 dark:hover:text-white'
        ])>
            {{ $item->getLabel() }}
        </a>
    @endif
</li>