<?php

namespace SertxuDeveloper\Lyra\Commands;

use Illuminate\Console\GeneratorCommand;

class CardMakeCommand extends GeneratorCommand {

  /**
   * The console command name.
   *
   * @var string
   */
  protected $name = 'lyra:card';

  protected $stub;

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Create a new Lyra card class';

  /**
   * The type of class being generated.
   *
   * @var string
   */
  protected $type = 'Card';

  /**
   * Execute the console command.
   *
   * @return bool|null
   *
   * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
   */
  public function handle() {

    $type = $this->choice('Select a type of card', ['Simple Card', 'Metrics Card', 'Ordered List']);

    switch ($type) {
      case 'Simple Card':
        $this->stub = '/stubs/cards/simple-card.stub';
        break;

      case 'Metrics Card':
        $this->stub = '/stubs/cards/metrics-card.stub';
        break;

      case 'Ordered List':
        $this->stub = '/stubs/cards/ordered-list.stub';
        break;
    }

    return parent::handle();
  }

  /**
   * Get the stub file for the generator.
   *
   * @return string
   */
  protected function getStub(){
    return __DIR__.$this->stub;
  }

  /**
   * Get the default namespace for the class.
   *
   * @param  string  $rootNamespace
   * @return string
   */
  protected function getDefaultNamespace($rootNamespace) {
    return $rootNamespace.'\Lyra\Dashboards\Cards';
  }

}
