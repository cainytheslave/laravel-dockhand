<?php

namespace Cainy\Dockhand\Commands;

use Cainy\Dockhand\Facades\Token;
use Illuminate\Console\Command;

class NotifyTokenCommand extends Command
{
    public $signature = 'dockhand:notify-token';

    public $description = 'Create the authentication token for notifying the application of registry events';

    public function handle(): int
    {
        $this->info('Generated new authentication token:');
        $this->newLine();
        $this->line(Token::withClaim('access', 'notify'));

        return self::SUCCESS;
    }
}
