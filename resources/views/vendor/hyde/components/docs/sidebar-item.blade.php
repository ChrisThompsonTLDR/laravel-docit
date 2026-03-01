@props(['grouped' => false])
@use('Hyde\Framework\Actions\GeneratesTableOfContents')
@php /** @var \Hyde\Framework\Features\Navigation\NavigationItem $item */ @endphp
<li class="relative" role="listitem">
    @if($item->isActive())
        <div class="absolute left-2 top-1 h-6 w-px bg-emerald-500" aria-hidden="true"></div>
    @endif
    @if($item->isActive())
        <a href="{{ $item->getLink() }}" aria-current="true" @class([
            'flex justify-between gap-2 py-1 pr-3 text-sm transition',
            $grouped ? 'pl-7' : 'pl-4',
            'text-zinc-900 dark:text-white'
        ])>
            <span class="truncate">{{ $item->getLabel() }}</span>
        </a>

        @if(config('docs.sidebar.table_of_contents.enabled', true))
            <span class="sr-only">Table of contents</span>
            <x-hyde::docs.table-of-contents :items="(new GeneratesTableOfContents($page->markdown))->execute()" />
        @endif
    @else
        <a href="{{ $item->getLink() }}" @class([
            'flex justify-between gap-2 py-1 pr-3 text-sm transition',
            $grouped ? 'pl-7' : 'pl-4',
            'text-zinc-600 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-white'
        ])>
            <span class="truncate">{{ $item->getLabel() }}</span>
        </a>
    @endif
</li>