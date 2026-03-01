<a href="{{ $item }}" {{ $attributes->except('item')->class([
    'navigation-link block my-2 md:my-0 md:inline-block py-1 text-zinc-700 hover:text-zinc-900 dark:text-zinc-100 dark:hover:text-white',
    'navigation-link-active border-l-4 border-emerald-500 md:border-none font-medium -ml-6 pl-5 md:ml-0 md:pl-0 bg-zinc-100 dark:bg-zinc-800 md:bg-transparent dark:md:bg-transparent' => $item->isActive()
])->merge($item->getExtraAttributes())->merge([
    'aria-current' => $item->isActive() ? 'page' : false,
]) }}>{{ $item->getLabel() }}</a>