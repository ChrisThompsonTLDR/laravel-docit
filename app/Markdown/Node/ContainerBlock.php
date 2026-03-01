<?php

declare(strict_types=1);

namespace App\Markdown\Node;

use League\CommonMark\Node\Block\AbstractBlock;

class ContainerBlock extends AbstractBlock
{
    public function __construct(
        public readonly string $type,
        public readonly string $title = '',
        public readonly string $href = '',
    ) {
        parent::__construct();
    }
}
