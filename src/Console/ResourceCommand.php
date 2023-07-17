<?php

namespace SertxuDeveloper\Lyra\Console;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;

class ResourceCommand extends GeneratorCommand
{
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
     * Build the class with the given name.
     *
     * @param  string  $name
     *
     * @throws FileNotFoundException
     */
    protected function buildClass($name): string {
        $stub = $this->files->get($this->getStub());

        $stub = $this->replaceNamespace($stub, $name)->replaceClass($stub, $name);

        if ($this->hasOption('model')) {
            if (!$model = $this->option('model')) {
                $model = Str::singular($this->getNameInput());
            }

            if (!$this->files->isFile($model)) {
                $this->createModel($model);
            }

            $stub = $this->replaceModel($stub, $model);
        }

        return $stub;
    }

    protected function createModel(string $model) {
        $this->callSilent('make:model', ['name' => $model]);
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     */
    protected function getDefaultNamespace($rootNamespace): string {
        return is_dir(app_path('Lyra/Resources')) ? $rootNamespace.'\\Lyra\\Resources' : $rootNamespace;
    }

    /**
     * Get the console command options.
     */
    protected function getOptions(): array {
        return [
            ['force', null, InputOption::VALUE_NONE, 'Create the class even if the model already exists'],
            ['model', 'm', InputOption::VALUE_OPTIONAL, 'Generate and/or attach a model to the resource'],
        ];
    }

    /**
     * Get the stub file for the generator.
     */
    protected function getStub(): string {
        if ($this->getNameInput() === 'Users') {
            return __DIR__.'/stubs/user-resource.stub';
        }

        return __DIR__.'/stubs/resource.stub';
    }

    /**
     * Replace the class name for the given stub.
     *
     * @param  string  $stub The stub to replace the class name in
     * @param  string  $name The name of the class
     */
    protected function replaceModel(string $stub, string $name): string {
        $namespace = is_dir(app_path('Models')) ? $this->rootNamespace()."Models\\$name" : $this->rootNamespace().$name;

        return str_replace(['DummyModel', '{{ model }}', '{{model}}'], "\\$namespace", $stub);
    }
}
