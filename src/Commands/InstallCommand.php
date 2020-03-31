<?php

namespace SertxuDeveloper\Lyra\Commands;

use Illuminate\Console\Command;
use SertxuDeveloper\Lyra\LyraServiceProvider;
use SertxuDeveloper\Lyra\Traits\Seedable;

class InstallCommand extends Command {
  use Seedable;

  protected $seedersPath = __DIR__ . '/../../publishable/database/seeds/';

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

    $this->info("First we're going to publish the assets files");
    $this->call("vendor:publish", ["--provider" => LyraServiceProvider::class, "--tag" => "lyra-assets"]);

    $this->info("Now, we're going to publish the config file");
    $this->call("vendor:publish", ["--provider" => LyraServiceProvider::class, "--tag" => "lyra-config"]);

    if ($this->confirm("Are you going to use the advanced working mode?")) {
      $this->info("After that, we're going to publish the database files and run the migrations");
      $this->call("vendor:publish", ["--provider" => LyraServiceProvider::class, "--tag" => "lyra-migrations"]);

      $this->call('migrate', ['--path' => 'database/migrations/lyra']);
    }

    $this->info("Finally is your turn, I need you to decide if you want to publish the optional files o not");

    if ($this->confirm('Do you want to publish the views files?')) {
      $this->call("vendor:publish", ["--provider" => LyraServiceProvider::class, "--tag" => "lyra-views"]);
    }

    if ($this->confirm('Do you want to add the storage symlink to the public folder?')) {
      $this->call('storage:link');
    }

    $this->info('Successfully installed! Enjoy creating awesome things with Lyra.');
  }
}
