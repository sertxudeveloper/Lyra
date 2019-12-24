<?php

namespace SertxuDeveloper\Lyra\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use SertxuDeveloper\Lyra\LyraServiceProvider;
use SertxuDeveloper\Lyra\Traits\Seedable;
use Symfony\Component\Console\Input\InputOption;

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


//  /**
//   * Get the console command options.
//   *
//   * @return array
//   */
//  protected function getOptions() {
//    return [
////      ['with-dummy', null, InputOption::VALUE_NONE, 'Install with dummy data', null],
//    ];
//  }

  /**
   * Execute the console command.
   * @return void
   */
  public function handle() {
    $this->info("Welcome to the Lyra package installation. Let's start!");

    $this->info("First we're going to publish the assets files");
    $this->call("vendor:publish", ["--provider" => LyraServiceProvider::class, "--tag" => "lyra-assets"]);

    $this->info("After that, we're going to publish the database files");
    $this->call("vendor:publish", ["--provider" => LyraServiceProvider::class, "--tag" => "lyra-migrations"]);

    $this->info("Now, we're going to publish the config file");
    $this->call("vendor:publish", ["--provider" => LyraServiceProvider::class, "--tag" => "lyra-config"]);

    $this->info("Finally is your turn, I need you to decide if you want to publish the optional files o not");

    if ($this->confirm('Do you want to publish the views files?')) {
      $this->call("vendor:publish", ["--provider" => LyraServiceProvider::class, "--tag" => "lyra-views"]);
    }

    if ($this->confirm('Do you want to run the migrations to the database')) {
      $this->call('migrate', ['--path' => 'database/migrations/lyra']);
    }

//    if ($this->confirm('Do you want to add dummy data as an example in the database?')) {
//      $this->addDummyData();
//    }

    if ($this->confirm('Do you want to add the storage symlink to the public folder?')) {
      $this->call('storage:link');
    }

    $this->info('Successfully installed! Enjoy creating awesome things with Lyra.');
  }

//  protected function addDummyData() {
//    $this->seed('LyraDummyDatabaseSeeder');
//    $this->call("vendor:publish", ["--provider" => LyraServiceProvider::class, "--tag" => "lyra-demo_content"]);
//    $this->warn('Default admin user created. Now you will be able to login with the email "admin@example.com" and the password "secret"');
//  }

}
