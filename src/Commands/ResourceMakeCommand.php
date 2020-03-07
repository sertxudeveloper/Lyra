<?php

namespace SertxuDeveloper\Lyra\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Symfony\Component\Console\Input\InputOption;

class ResourceMakeCommand extends GeneratorCommand {

  /**
   * The console command name.
   *
   * @var string
   */
  protected $name = 'lyra:resource';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Create a new Lyra resource class';

  /**
   * The type of class being generated.
   *
   * @var string
   */
  protected $type = 'Resource';


  /**
   * Get the stub file for the generator.
   *
   * @return string
   */
  protected function getStub(){
    $stub = '/stubs/resource.stub';
    return __DIR__.$stub;
  }

  /**
   * Get the default namespace for the class.
   *
   * @param  string  $rootNamespace
   * @return string
   */
  protected function getDefaultNamespace($rootNamespace) {
    return $rootNamespace.'\Lyra';
  }

  /**
   * Build the class with the given name.
   *
   * Remove the base controller import if we are already in base namespace.
   *
   * @param string $name
   * @return string
   * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
   */
  protected function buildClass($name) {
    $replace = [];
    $controllerNamespace = $this->getNamespace($name);

    if ($this->option('model')) {
      $replace = $this->buildModelReplacements($replace);
    } else {
      array_merge($replace, ['DummyFullModelClass' => "App\\$name"]);
    }

    $replace["use {$controllerNamespace}\Controller;\n"] = '';
    return str_replace(
      array_keys($replace), array_values($replace), parent::buildClass($name)
    );
  }

  /**
   * Build the model replacement values.
   *
   * @param  array  $replace
   * @return array
   */
  protected function buildModelReplacements(array $replace) {
    $modelClass = $this->parseModel($this->option('model'));
    if (! class_exists($modelClass)) {
      if ($this->confirm("A {$modelClass} model does not exist. Do you want to generate it?", true)) {
        $this->call('make:model', ['name' => $modelClass]);
      }
    }
    return array_merge($replace, [
      'DummyFullModelClass' => $modelClass,
    ]);
  }

  /**
   * Get the fully-qualified model class name.
   *
   * @param  string  $model
   * @return string
   *
   * @throws \InvalidArgumentException
   */
  protected function parseModel($model) {
    if (preg_match('([^A-Za-z0-9_/\\\\])', $model)) {
      throw new InvalidArgumentException('Model name contains invalid characters.');
    }
    $model = trim(str_replace('/', '\\', $model), '\\');
    if (! Str::startsWith($model, $rootNamespace = $this->laravel->getNamespace())) {
      $model = $rootNamespace.$model;
    }
    return $model;
  }

  /**
   * Get the console command options.
   *
   * @return array
   */
  protected function getOptions() {
    return [
      ['model', 'm', InputOption::VALUE_OPTIONAL, 'Generate a Lyra resource for the given model.'],
    ];
  }

}
