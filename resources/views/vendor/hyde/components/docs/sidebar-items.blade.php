@if(! $sidebar->hasGroups())
    <ul id="sidebar-items" role="list" class="pl-2">
        @php
            $guideItem = \Hyde\Framework\Features\Navigation\NavigationItem::create(
                \Hyde\Pages\DocumentationPage::home(),
                config('docs.sidebar.labels.' . \Hyde\Pages\DocumentationPage::homeRouteName(), 'Guide')
            );
        @endphp
        @include('hyde::components.docs.sidebar-item', ['item' => $guideItem])
        @foreach ($sidebar->getItems() as $item)
            @include('hyde::components.docs.sidebar-item')
        @endforeach
    </ul>
@else
    <ul id="sidebar-items" role="list">
        @php /** @var \Hyde\Framework\Features\Navigation\NavigationGroup $group */ @endphp
        @foreach ($sidebar->getItems() as $group)
            <li class="sidebar-group" role="listitem" @if($sidebar->isCollapsible()) x-data="{ groupOpen: {{ ($sidebar->getActiveGroup() === $group) ? 'true' : 'false' }} }" @endif>
                <header @class(['sidebar-group-header p-2 px-4 -ml-2 flex justify-between items-center', 'group hover:bg-zinc-900/10 dark:hover:bg-white/10' => $sidebar->isCollapsible()]) @if($sidebar->isCollapsible()) @click="groupOpen = ! groupOpen" @endif>
                    <h4 @class(['sidebar-group-heading text-base font-semibold text-zinc-900 dark:text-white', 'cursor-pointer dark:group-hover:text-white' => $sidebar->isCollapsible()])>{{ $group->getLabel() }}</h4>
                    @if($sidebar->isCollapsible())
                        @include('hyde::components.docs.sidebar-group-toggle-button')
                    @endif
                </header>
                <ul class="sidebar-group-items ml-4 px-2 mb-2" role="list" @if($sidebar->isCollapsible()) x-show="groupOpen" @endif>
                    @foreach ($group->getItems() as $item)
                        @include('hyde::components.docs.sidebar-item', ['grouped' => true])
                    @endforeach
                </ul>
            </li>
        @endforeach
    </ul>
@endif