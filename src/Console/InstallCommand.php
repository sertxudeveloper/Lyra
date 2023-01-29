<?php

namespace SertxuDeveloper\Lyra\Console;

use Illuminate\Console\Command;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lyra:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install all of the Lyra resources';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void {
        $this->comment('Publishing Lyra assets...');
        $this->callSilent('vendor:publish', ['--tag' => 'lyra-assets']);

        $this->comment('Publishing Lyra configuration...');
        $this->callSilent('vendor:publish', ['--tag' => 'lyra-config']);

        $this->info('Lyra installed successfully.');
    }
}
