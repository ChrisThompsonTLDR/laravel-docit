<?php

namespace App\Commands;

use LaravelZero\Framework\Commands\Command;

class DocitCommand extends Command
{
    protected $signature = 'docit';

    protected $description = 'Build the static docs site and copy output to the consuming project\'s docs/ directory';

    public function handle(): int
    {
        $script = base_path('bin/build-docs');

        if (! file_exists($script)) {
            $this->error('Build script not found: ' . $script);

            return self::FAILURE;
        }

        $this->info('Building docs...');

        passthru('php ' . escapeshellarg($script), $code);

        return $code === 0 ? self::SUCCESS : self::FAILURE;
    }
}
