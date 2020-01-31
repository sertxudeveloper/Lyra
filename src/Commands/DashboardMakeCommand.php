<?php

namespace SertxuDeveloper\Lyra\Commands;

use Illuminate\Console\GeneratorCommand;

class DashboardMakeCommand extends GeneratorCommand {

  /**
   * The console command name.
   *
   * @var string
   */
  protected $name = 'lyra:dashboard';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Create a new Lyra dashboard class';

  /**
   * The type of class being generated.
   *
   * @var string
   */
  protected $type = 'Dashboard';


  /**
   * Get the stub file for the generator.
   *
   * @return string
   */
  protected function getStub(){
    $stub = '/stubs/dashboard.stub';
    return __DIR__.$stub;
  }

  /**
   * Get the default namespace for the class.
   *
   * @param  string  $rootNamespace
   * @return string
   */
  protected function getDefaultNamespace($rootNamespace) {
    return $rootNamespace.'\Lyra\Dashboards';
  }

}
