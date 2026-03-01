<?php

declare(strict_types=1);

namespace App\Markdown\Renderer;

use App\Markdown\Node\ContainerBlock;
use Hyde\Markdown\Models\Markdown;
use League\CommonMark\Node\Node;
use League\CommonMark\Renderer\ChildNodeRendererInterface;
use League\CommonMark\Renderer\NodeRendererInterface;
use League\CommonMark\Util\HtmlElement;

final class ContainerRenderer implements NodeRendererInterface
{
    public function render(Node $node, ChildNodeRendererInterface $childRenderer): \Stringable
    {
        ContainerBlock::assertInstanceOf($node);

        $block = $node;
        $content = $block->data->get('content', '');

        if ($block->type === 'card-grid') {
            $cardsHtml = $this->parseCardGridContent($content);
            return new HtmlElement('div', [
                'class' => 'not-prose grid gap-6 sm:grid-cols-2 lg:grid-cols-3 my-8',
            ], $cardsHtml);
        }

        $htmlContent = trim(Markdown::render($content));
        $classes = 'block p-6 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 shadow hover:shadow-lg transition-shadow';

        $titleEl = $block->title !== ''
            ? new HtmlElement('h3', ['class' => 'text-xl font-semibold text-zinc-900 dark:text-white mb-2'], $block->title)
            : '';
        $bodyEl = new HtmlElement('div', [
            'class' => 'text-zinc-600 dark:text-zinc-300 prose dark:prose-invert prose-sm max-w-none',
        ], $htmlContent);

        $inner = (string) $titleEl . (string) $bodyEl;

        if ($block->href !== '') {
            return new HtmlElement('a', [
                'href' => $block->href,
                'class' => $classes . ' no-underline hover:no-underline',
            ], $inner);
        }

        return new HtmlElement('div', ['class' => $classes], $inner);
    }

    /**
     * Parse card-grid content without recursive Markdown::render to avoid build stall.
     * Extracts ::: card Title url ... ::: blocks and renders each card's body with simple markdown.
     */
    private function parseCardGridContent(string $content): string
    {
        $cards = [];
        $pattern = '/::: card ([^\n]+)\n(.*?)(?=:::|\z)/s';

        if (preg_match_all($pattern, $content, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $match) {
                $header = trim($match[1]);
                $body = trim($match[2]);
                $parts = explode(' ', $header);
                $href = '';
                if ($parts !== [] && (str_contains(end($parts), '/') || str_ends_with(end($parts), '.html'))) {
                    $href = array_pop($parts);
                }
                $title = implode(' ', $parts);
                $bodyHtml = trim(Markdown::render($body));
                $cards[] = $this->renderCard($title, $href, $bodyHtml);
            }
        }

        return implode('', $cards);
    }

    private function renderCard(string $title, string $href, string $bodyHtml): string
    {
        $classes = 'block p-6 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 shadow hover:shadow-lg transition-shadow';
        $titleEl = $title !== ''
            ? '<h3 class="text-xl font-semibold text-zinc-900 dark:text-white mb-2">' . htmlspecialchars($title) . '</h3>'
            : '';
        $bodyEl = '<div class="text-zinc-600 dark:text-zinc-300 prose dark:prose-invert prose-sm max-w-none">' . $bodyHtml . '</div>';

        if ($href !== '') {
            return '<a href="' . htmlspecialchars($href) . '" class="' . $classes . ' no-underline hover:no-underline">' . $titleEl . $bodyEl . '</a>';
        }

        return '<div class="' . $classes . '">' . $titleEl . $bodyEl . '</div>';
    }
}
