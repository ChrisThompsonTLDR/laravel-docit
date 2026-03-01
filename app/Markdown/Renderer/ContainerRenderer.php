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
        $htmlContent = trim(Markdown::render($content));

        if ($block->type === 'card-grid') {
            return new HtmlElement('div', [
                'class' => 'not-prose grid gap-6 sm:grid-cols-2 lg:grid-cols-3 my-8',
            ], $htmlContent);
        }

        $classes = 'block p-6 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 shadow hover:shadow-lg transition-shadow';

        $titleEl = $block->title !== ''
            ? new HtmlElement('h3', ['class' => 'text-xl font-semibold text-gray-900 dark:text-white mb-2'], $block->title)
            : '';
        $bodyEl = new HtmlElement('div', [
            'class' => 'text-gray-600 dark:text-gray-300 prose dark:prose-invert prose-sm max-w-none',
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
}
