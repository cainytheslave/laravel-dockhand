<?php

namespace Cainy\Vessel\Commands;

use Illuminate\Console\Command;

class VesselCommand extends Command
{
    public $signature = 'laravel-oci';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
