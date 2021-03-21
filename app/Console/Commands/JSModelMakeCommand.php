<?php

namespace App\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputArgument;

class JSModelMakeCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'make:js-model';

    protected $columns = '';

    protected $colType = '';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new js model class';

    /**
     * @var string
     */
    protected $type = 'jsModel';


    /**
     * @return bool|null
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function handle()
    {
        $name = $this->getNameInput();
        $path = $this->qualifyClass($name);

        if($this->alreadyExists($path)) {
            $this->error($this->type.' already exists!');
            return false;
        }

        $this->makeDirectory($path);

        $file = $this->buildClass($name);
        $this->files->put($path, $file);

        $this->info($this->type.' created successfully.');
    }

    protected function getStub()
    {
        return __DIR__.'/Stubs/js-model.js';
    }

    /**
     * Parse the class name and format according to the root namespace.
     *
     * @param  string  $name
     * @return string
     */
    protected function qualifyClass($name)
    {
        $p = rtrim(ltrim(config('frontend.js_model_dir'), '/'), '/');
        return base_path($p. '/'. $name. '.js');
    }

    protected function alreadyExists($path)
    {
        return $this->files->exists($path);
    }

    /**
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function buildClass($name)
    {
        $stub = $this->files->get($this->getStub());

        $stub = $this->replaceClass($stub, $name);

        $stub = $this->addFillable($stub);

        $stub = $this->addProperty($stub);

        return $stub;
    }

    /**
     * @param string $stub
     * @param string $name
     * @return mixed|string
     */
    protected function replaceClass($stub, $name) {

        $n = explode('/', $name);
        if(count($n) > 1) {
            $name = collect($n)->last();
        }
        return str_replace('DummyClass', $name, $stub);
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the class'],
            ['columns', InputArgument::OPTIONAL, 'The columns of the fillabel list'],
            ['colType', InputArgument::IS_ARRAY, 'The type of the fillabel type list'],
        ];
    }

    private function addFillable($stub) {
        $fill = $this->getColumnsInput();
        if(!$fill) {
            return $stub;
        }
        $fillable = collect(explode(',', $fill))
            ->map(function($x) {
                return "'".camel_case($x)."'";
            })->toArray();

        $fillStr = implode($fillable, ",\n    ");

        return str_replace('//fillable', $fillStr, $stub);
    }

    private function addProperty($stub) {
        $fill = $this->getColumnsInput();
        $types = $this->getColumnsTypeInput();
        if(!$fill || !$types) {
            return $stub;
        }

        $property = collect(explode(',', $fill))
            ->map(function($x, $i) use($types){
                return 'this.'. camel_case($x). ' = '. $this->typeValue($types[$i]);
            })->toArray();

        $propertyStr = implode($property, "\n        ");

        return str_replace('//property', $propertyStr, $stub);
    }

    private function getColumnsInput()
    {
        return trim($this->argument('columns'));
    }

    private function getColumnsTypeInput()
    {
        return $this->argument('colType');
    }

    private function typeValue($val) {
        switch ($val) {
            case 'increments':
            case 'integer':
                return 0;
                break;
            case 'boolean':
            case 'tinyInteger':
                return 'false';
                break;
            default:
                return "''";
                break;
        }
    }
}
