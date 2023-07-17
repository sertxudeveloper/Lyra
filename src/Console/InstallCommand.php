<?php

namespace SertxuDeveloper\Lyra\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use SertxuDeveloper\Lyra\LyraServiceProvider;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lyra:install
    {--force : Overwrite existing files by default}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install the Lyra package';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle() {
        if ($this->isAlreadyInstalled() && !$this->option('force')) {
            $this->error('Lyra is already installed.');
            $this->newLine();
            $this->info('You can reconfigure Lyra with the --force option:');
            $this->comment('php artisan lyra:install --force');

            return;
        }

        $this->info('Installing Lyra...');
        $this->call('vendor:publish', [
            '--provider' => LyraServiceProvider::class, '--tag' => 'lyra-config', '--force' => $this->option('force'),
        ]);

        $this->info('Creating required directories...');
        $this->createDirectories();

        $this->info('Generating User resource...');
        $this->callSilent('lyra:resource', ['name' => 'Users']);

        $this->info('Lyra installed successfully.');
    }

    /**
     * Create the directories required for Lyra classes.
     *
     * @return void
     */
    protected function createDirectories() {
        if (!File::isDirectory(app_path('Lyra'))) {
            File::makeDirectory(app_path('Lyra'));
        }

        if (!File::isDirectory(app_path('Lyra/Actions'))) {
            File::makeDirectory(app_path('Lyra/Actions'));
        }

        if (!File::isDirectory(app_path('Lyra/Cards'))) {
            File::makeDirectory(app_path('Lyra/Cards'));
        }

        if (!File::isDirectory(app_path('Lyra/Resources'))) {
            File::makeDirectory(app_path('Lyra/Resources'));
        }
    }

    /**
     * Check if the Lyra package is already installed.
     */
    protected function isAlreadyInstalled(): bool {
        return file_exists(config_path('lyra.php'));
    }
}
