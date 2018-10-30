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


  /**
   * Get the console command options.
   *
   * @return array
   */
  protected function getOptions() {
    return [
      ['with-dummy', null, InputOption::VALUE_NONE, 'Install with dummy data', null],
    ];
  }

  /**
   * Execute the console command.
   *
   * @param \Illuminate\Filesystem\Filesystem $filesystem
   *
   * @return void
   */
  public function handle(Filesystem $filesystem) {
    $this->info("Welcome to the Lyra package installation. Let's start!");

    $this->info("First we're going to publish the assets files");
    $this->call("vendor:publish", ["--provider" => LyraServiceProvider::class, "--tag" => "lyra-assets"]);

    $this->info("After that, we're going to publish the database files");
    $this->call("vendor:publish", ["--provider" => LyraServiceProvider::class, "--tag" => "lyra-migrations"]);
    $this->call("vendor:publish", ["--provider" => LyraServiceProvider::class, "--tag" => "lyra-seeds"]);

    $this->info("Now is your turn, I need you to decide if you want to publish the optional files o not");

    if ($this->confirm('Do you want to publish the config files?')) {
      $this->call("vendor:publish", ["--provider" => LyraServiceProvider::class, "--tag" => "lyra-config"]);
    }

    if ($this->confirm('Do you want to publish the views files?')) {
      $this->call("vendor:publish", ["--provider" => LyraServiceProvider::class, "--tag" => "lyra-views"]);
    }

    $this->info("Let's make the migrations to the database");
    $this->call('migrate');


    $this->info('Attempting to set Lyra Users model as parent to App\Users');
    if (file_exists(app_path('Users.phpp'))) {
      $str = file_get_contents(app_path('Users.phpp'));

      if ($str !== false) {
        $str = str_replace('extends Authenticatable', "extends \SertxuDeveloper\Lyra\Models\User", $str);

        file_put_contents(app_path('Users.phpp'), $str);
      }
    } else {
      $this->warn('Unable to locate "app/Userss.php".  Did you move this file?');
      $this->warn('You will need to update this manually.  Change "extends Authenticatable" to "extends \SertxuDeveloper\Lyra\Models\Users" in your Users model');
    }

    $this->info('Seeding data into the database');
    $this->seed('LyraDatabaseSeeder');

    if ($this->option('with-dummy')) {
      $this->addDummyData();
    } else if ($this->confirm('Do you want to add dummy data as an example in the database?')) {
      $this->addDummyData();
    }

    $this->info('Adding the storage symlink to your public folder');
    $this->call('storage:link');

    $this->info('Successfully installed! Enjoy creating awesome things with Lyra.');
  }

  protected function addDummyData() {
    $this->seed('LyraDummyDatabaseSeeder');
    $this->call("vendor:publish", ["--provider" => LyraServiceProvider::class, "--tag" => "lyra-demo_content"]);
    $this->warn('Default admin user created. Now you will be able to login with the email "admin@example.com" and the password "secret"');
  }

}