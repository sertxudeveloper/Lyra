<?php

namespace SertxuDeveloper\Lyra\Console;

use Illuminate\Support\Collection;
use SertxuDeveloper\Lyra\LyraServiceProvider;

class InstallCommand extends Command {

  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'lyra:install
    {--force : Overwrite existing files by default}
    {--auth= : Set the authentication provider ("default" or "lyra")}';

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
    $this->call('vendor:publish', ['--provider' => LyraServiceProvider::class, '--tag' => 'lyra-config']);



    $this->info('Lyra installed successfully.');
  }

  /**
   * Check if the Lyra package is already installed.
   *
   * @return bool
   */
  protected function isAlreadyInstalled(): bool {
    return file_exists(config_path('lyra.php'));
  }
}
