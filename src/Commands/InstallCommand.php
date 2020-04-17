<?php

namespace SertxuDeveloper\Lyra\Commands;

use Illuminate\Console\Command;
use SertxuDeveloper\Lyra\LyraServiceProvider;
use SertxuDeveloper\Lyra\Traits\Seedable;

class InstallCommand extends Command {
  use Seedable;

  /**
   * The console command name.
   *
   * @var string
   */
  protected $name = 'lyra:install';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Install the Lyra package';

  /**
   * Execute the console command.
   * @return void
   */
  public function handle() {
    $this->info("Welcome to the Lyra package installation. Let's start!");

    $this->info("First, we're going to publish the config file");
    $this->call("vendor:publish", ["--provider" => LyraServiceProvider::class, "--tag" => "lyra-config"]);

    if ($this->confirm("Are you going to use the advanced working mode?")) {
      $this->replace('Lyra::MODE_BASIC', 'Lyra::MODE_ADVANCED', config_path('lyra.php'));
      $this->replace('LaravelUser::class','LyraUser::class', config_path('lyra.php'));

      $this->info("After that, we're going to publish the database files and run the migrations");
      $this->call("vendor:publish", ["--provider" => LyraServiceProvider::class, "--tag" => "lyra-migrations"]);

      $this->call('migrate', ['--path' => 'database/migrations/lyra']);
    }

    $this->info('Successfully installed! Enjoy creating awesome things with Lyra.');
  }

  /**
   * Replace the $search with $replace in the $path file.
   *
   * @param $search
   * @param $replace
   * @param $path
   */
  private function replace($search, $replace, $path) {
    file_put_contents($path, str_replace($search, $replace, file_get_contents($path)));
  }
}
