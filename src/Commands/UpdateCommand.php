<?php

namespace SertxuDeveloper\Lyra\Commands;

use Illuminate\Console\Command;
use SertxuDeveloper\Lyra\LyraServiceProvider;

class UpdateCommand extends Command {

  /**
   * The console command name.
   *
   * @var string
   */
  protected $name = 'lyra:update';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Update the Lyra package';


  /**
   * Execute the console command.
   * @return void
   */
  public function handle() {
    $this->info("Welcome to the Lyra package update. Let's start!");

    $this->info("First we're going to publish the assets files");
    $this->call("vendor:publish", ["--provider" => LyraServiceProvider::class, "--tag" => "lyra-assets", "--force" => true]);

    $this->info("After that, we're going to publish the database files");
    $this->call("vendor:publish", ["--provider" => LyraServiceProvider::class, "--tag" => "lyra-migrations", "--force" => true]);

    $this->info('Successfully updated! Enjoy creating awesome things with Lyra.');
  }

  /**
   * Handle the post-update Composer event.
   *
   * @param  \Composer\Script\Event $event
   * @return void
   */
  public static function postPackageUpdate($event) {
    (new static())->handle();
  }

}
