@if(config('hyde.footer') !== false)
    <footer aria-label="Page footer" class="flex py-4 px-6 w-full text-center mt-auto bg-zinc-100 dark:bg-zinc-900 border-t border-zinc-900/10 dark:border-white/10">
        <div class="prose dark:prose-invert text-center mx-auto">
            {!! \Hyde\Support\Includes::markdown('footer', config('hyde.footer', 'Site proudly built with [HydePHP](https://github.com/hydephp/hyde) 🎩')) !!}
        </div>
        <a href="#app" aria-label="Go to top of page" class="float-right">
            <button title="Scroll to top">
                <svg width="1.5rem" height="1.5rem" role="presentation" class="fill-current text-zinc-500 h-6 w-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                    <path d="M7.41 15.41L12 10.83l4.59 4.58L18 14l-6-6-6 6z" />
                </svg>
            </button>
        </a>
    </footer>
@endif