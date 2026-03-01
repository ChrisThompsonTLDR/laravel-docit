<?php

declare(strict_types=1);

namespace App\Markdown;

use App\Markdown\Node\ContainerBlock;
use App\Markdown\Parser\ContainerStartParser;
use App\Markdown\Renderer\ContainerRenderer;
use League\CommonMark\Environment\EnvironmentBuilderInterface;
use League\CommonMark\Extension\ExtensionInterface;

final class ContainerExtension implements ExtensionInterface
{
    public function register(EnvironmentBuilderInterface $environment): void
    {
        $environment
            ->addBlockStartParser(new ContainerStartParser(), 55)
            ->addRenderer(ContainerBlock::class, new ContainerRenderer(), 0);
    }
}
