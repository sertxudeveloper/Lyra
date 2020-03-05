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
    $this->info("Welcome to the Lyra package updater.");
    $this->call("vendor:publish", ["--provider" => LyraServiceProvider::class, "--tag" => "lyra-assets", "--force" => true]);
    $this->call("vendor:publish", ["--provider" => LyraServiceProvider::class, "--tag" => "lyra-migrations", "--force" => true]);
    $this->info('Successfully updated! Enjoy creating awesome things with Lyra.');
  }

}
