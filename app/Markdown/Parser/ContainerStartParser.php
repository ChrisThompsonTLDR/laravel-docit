<?php

declare(strict_types=1);

namespace App\Markdown\Parser;

use App\Markdown\Node\ContainerBlock;
use App\Markdown\Parser\ContainerParser;
use League\CommonMark\Parser\Block\BlockStart;
use League\CommonMark\Parser\Block\BlockStartParserInterface;
use League\CommonMark\Parser\Cursor;
use League\CommonMark\Parser\MarkdownParserStateInterface;

final class ContainerStartParser implements BlockStartParserInterface
{
    private const PATTERN = '/^:::(\s+)([\w-]+)(?:\s+(.+))?$/';

    public function tryStart(Cursor $cursor, MarkdownParserStateInterface $parserState): ?BlockStart
    {
        if ($cursor->isIndented()) {
            return BlockStart::none();
        }

        $line = trim($cursor->getLine());
        if (! str_starts_with($line, ':::')) {
            return BlockStart::none();
        }

        if (! preg_match(self::PATTERN, $line, $matches)) {
            return BlockStart::none();
        }

        $type = $matches[2];
        if (! in_array($type, ['card', 'card-grid'], true)) {
            return BlockStart::none();
        }

        $rest = trim($matches[3] ?? '');
        $title = '';
        $href = '';
        if ($type === 'card') {
            $parts = explode(' ', $rest);
            if ($parts !== [] && (str_contains(end($parts), '/') || str_ends_with(end($parts), '.html'))) {
                $href = array_pop($parts);
            }
            $title = implode(' ', $parts);
        }

        return BlockStart::of(new ContainerParser(new ContainerBlock($type, $title, $href)))->at($cursor);
    }
}
