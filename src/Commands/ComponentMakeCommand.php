<?php

namespace SertxuDeveloper\Lyra\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use Symfony\Component\Process\Process;

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
    $this->replace('{{ name }}', $this->getComponentName(), $this->getComponentPath() . '/src/Component.stub');
    $this->rename('Component.stub', $this->getComponentClass() . '.php', $this->getComponentPath() . '/src/Component.stub');

    // Replace ComponentServiceProvider.stub file
    $this->replace('{{ namespace }}', $this->getComponentNamespace(), $this->getComponentPath() . '/src/ComponentServiceProvider.stub');
    $this->replace('{{ class }}', $this->getComponentClass(), $this->getComponentPath() . '/src/ComponentServiceProvider.stub');
    $this->rename('ComponentServiceProvider.stub', $this->getComponentClass() . 'ServiceProvider.php', $this->getComponentPath() . '/src/ComponentServiceProvider.stub');

    // Replace composer.json file
    $this->replace('{{ vendor }}', $this->getComponentVendor(), $this->getComponentPath() . '/composer.json');
    $this->replace('{{ package }}', $this->getComponentName(), $this->getComponentPath() . '/composer.json');
    $this->replace('{{ escapedNamespace }}', $this->getComponentEscapedNamespace(), $this->getComponentPath() . '/composer.json');
    $this->replace('{{ class }}', $this->getComponentClass(), $this->getComponentPath() . '/composer.json');

    if ($this->confirm('Do you want to register the component in your composer.json automatically?', true)) {
      $this->addPackageToComposer();

      if ($this->confirm('Do you want to update the composer.json dependencies?', true)) {
        $this->updateComposerDependencies();
      }
    }

    if ($this->confirm('Do you want to add the NPM scripts in your package.json file?', true)) {
      $this->addPackageScripts();
    }

    if ($this->confirm('Do you want to install the component package.json dependencies?', true)) {
      $this->installNpmDependencies();

      if ($this->confirm('Do you want to compile the style and script files?', true)) {
        $this->compile();
      }
    }

    $this->info('Component created successfully!');
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

  private function rename($from, $to, $file) {
    (new Filesystem)->move($file, str_replace($from, $to, $file));
  }

  private function getComponentEscapedNamespace() {
    return str_replace('\\', '\\\\', $this->getComponentNamespace());
  }

  private function addPackageToComposer() {
    $composer = json_decode(file_get_contents(base_path('composer.json')), true);

    $composer['repositories'][] = [
      'type' => 'path',
      'url' => './' . $this->getComponentRelativePath(),
    ];

    $composer['require'][$this->argument('name')] = '*';

    $composer = json_encode($composer, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    file_put_contents(base_path('composer.json'), $composer);
  }

  private function addPackageScripts() {
    $package = json_decode(file_get_contents(base_path('package.json')), true);

    $package['scripts'][$this->getComponentName() . '-dev'] = 'cd ' . $this->getComponentRelativePath() . ' && npm run dev';
    $package['scripts'][$this->getComponentName() . '-prod'] = 'cd ' . $this->getComponentRelativePath() . ' && npm run prod';

    $package = json_encode($package, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    file_put_contents(base_path('package.json'), $package);
  }

  private function installNpmDependencies() {
    $this->executeCommand('npm install', $this->getComponentPath());
  }

  private function updateComposerDependencies() {
    $this->executeCommand('composer update', base_path());
  }

  private function compile() {
    $this->executeCommand('npm run dev', $this->getComponentPath());
  }

  private function executeCommand($command, $cwd) {
    $process = new Process($command, $cwd);
    $process->setTimeout(null);
    $process->run(function ($type, $line) {
      $this->output->write($line);
    });
  }
}
