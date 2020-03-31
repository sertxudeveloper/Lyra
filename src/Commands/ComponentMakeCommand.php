<?php

namespace SertxuDeveloper\Lyra\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

class ComponentMakeCommand extends Command {

  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = "lyra:component {name}";

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Create a new Lyra component';

  public function handle() {
    (new Filesystem)->copyDirectory(__DIR__ . '/stubs/component', $this->getComponentPath());

    // Replace component.js file
    $this->replace('{{ component }}', $this->getComponentName(), $this->getComponentPath() . '/resources/js/component.js');

    // Replace Component.vue file
    $this->replace('{{ component }}', $this->getComponentName(), $this->getComponentPath() . '/resources/js/components/Component.vue');

    // Replace Component.stub file
    $this->replace('{{ namespace }}', $this->getComponentNamespace(), $this->getComponentPath() . '/src/Component.stub');
    $this->replace('{{ class }}', $this->getComponentClass(), $this->getComponentPath() . '/src/Component.stub');
    $this->rename('Component.stub', $this->getComponentClass() . '.php', $this->getComponentPath() . '/src/Component.stub');

    // Replace ComponentServiceProvider.stub file
    $this->replace('{{ namespace }}', $this->getComponentNamespace(), $this->getComponentPath() . '/src/ComponentServiceProvider.stub');
    $this->replace('{{ class }}', $this->getComponentClass(), $this->getComponentPath() . '/src/ComponentServiceProvider.stub');
    $this->replace('{{ name }}', $this->getComponentName(), $this->getComponentPath() . '/src/ComponentServiceProvider.stub');
    $this->rename('ComponentServiceProvider.stub', $this->getComponentClass() . 'ServiceProvider.php', $this->getComponentPath() . '/src/ComponentServiceProvider.stub');

    // Replace composer.json file
    $this->replace('{{ vendor }}', $this->getComponentVendor(), $this->getComponentPath() . '/composer.json');
    $this->replace('{{ package }}', $this->getComponentName(), $this->getComponentPath() . '/composer.json');
    $this->replace('{{ escapedNamespace }}', $this->getComponentEscapedNamespace(), $this->getComponentPath() . '/composer.json');
    $this->replace('{{ class }}', $this->getComponentClass(), $this->getComponentPath() . '/composer.json');

    $this->info('Component created successfully!');
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

  private function getComponentPath() {
    return base_path($this->getComponentRelativePath());
  }

  private function getComponentRelativePath() {
    return 'lyra-components/' . Str::snake($this->getComponentName());
  }

  private function getComponentVendor() {
    return explode('/', $this->argument('name'))[0];
  }

  private function getComponentName() {
    return explode('/', $this->argument('name'))[1];
  }

  private function getComponentNamespace() {
    return Str::studly($this->getComponentVendor()) . '\\' . $this->getComponentClass();
  }

  private function getComponentClass() {
    return Str::studly($this->getComponentName());
  }

  private function rename($from, $to, $file) {
    (new Filesystem)->move($file, str_replace($from, $to, $file));
  }

  private function getComponentEscapedNamespace() {
    return str_replace('\\', '\\\\', $this->getComponentNamespace());
  }

}
