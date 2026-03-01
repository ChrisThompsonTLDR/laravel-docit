<?php

declare(strict_types=1);

namespace App\Markdown;

use League\CommonMark\Environment\EnvironmentBuilderInterface;
use League\CommonMark\Extension\CommonMark\Node\Block\FencedCode;
use League\CommonMark\Extension\CommonMark\Node\Block\IndentedCode;
use League\CommonMark\Extension\ExtensionInterface;
use Spatie\CommonMarkHighlighter\FencedCodeRenderer;
use Spatie\CommonMarkHighlighter\IndentedCodeRenderer;

final class HighlightExtension implements ExtensionInterface
{
    public function register(EnvironmentBuilderInterface $environment): void
    {
        $languages = ['php', 'javascript', 'js', 'bash', 'yaml', 'json', 'html', 'css', 'md', 'markdown', 'xml', 'sql'];
        $environment
            ->addRenderer(FencedCode::class, new FencedCodeRenderer($languages), 10)
            ->addRenderer(IndentedCode::class, new IndentedCodeRenderer($languages), 10);
    }
}
