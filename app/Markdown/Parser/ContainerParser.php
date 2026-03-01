<?php

declare(strict_types=1);

namespace App\Markdown\Parser;

use App\Markdown\Node\ContainerBlock;
use League\CommonMark\Parser\Block\AbstractBlockContinueParser;
use League\CommonMark\Parser\Block\BlockContinue;
use League\CommonMark\Parser\Block\BlockContinueParserInterface;
use League\CommonMark\Parser\Cursor;
use League\CommonMark\Util\ArrayCollection;

final class ContainerParser extends AbstractBlockContinueParser
{
    private ContainerBlock $block;

    /** @var ArrayCollection<string> */
    private ArrayCollection $lines;

    private int $nesting = 1;

    public function __construct(ContainerBlock $block)
    {
        $this->block = $block;
        $this->lines = new ArrayCollection();
    }

    public function getBlock(): ContainerBlock
    {
        return $this->block;
    }

    public function tryContinue(Cursor $cursor, BlockContinueParserInterface $activeBlockParser): ?BlockContinue
    {
        $line = trim($cursor->getLine());

        if ($line === ':::') {
            $this->nesting--;
            if ($this->nesting <= 0) {
                return BlockContinue::finished();
            }
            return BlockContinue::at($cursor);
        }

        if (preg_match('/^:::(\s+)([\w-]+)/', $line)) {
            $this->nesting++;
        }

        return BlockContinue::at($cursor);
    }

    public function canHaveLazyContinuationLines(): bool
    {
        return true;
    }

    public function addLine(string $line): void
    {
        $this->lines[] = $line;
    }

    public function closeBlock(): void
    {
        $this->block->data->set('content', implode("\n", $this->lines->toArray()));
    }
}
