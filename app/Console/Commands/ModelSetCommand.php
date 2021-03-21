<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Symfony\Component\Finder\SplFileInfo;

class ModelSetCommand extends Command
{

    protected $signature = 'model-set';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $names = $this->findMigrateFiles();

        collect($names)
            ->each(function (ModuleConverter $x) {
                $this->createModel($x);

                $this->writeFactory($x);

                $this->createSeeder($x);

                $this->createJsModel($x);
            });

        $this->line("<fg=green>All complete</>");
    }

    private function findMigrateFiles() {
        $files = File::files(base_path('database/migrations'));
        $names = collect($files)
            ->map(function (SplFileInfo $x) {
                $file = File::get($x->getRealPath());
                return ['name' => $x->getFilename(), 'file' => $file];
            })
            ->filter(function ($x) {
                return str_contains($x['name'], 'create');
            })
            ->map(function($x) {
                preg_match('/create.*table/' ,$x['name'], $matches);
                $a = str_replace('create_', '', $matches[0]);
                return [
                    'name' => str_replace('_table', '', $a),
                    'file' => $x['file']
                ];
            })
            ->map(function($x) {
                $s = str_singular($x['name']);
                return new ModuleConverter(studly_case($s), $x['file']);
            })
            ->toArray()
        ;

        return $names;
    }

    /**
     * @param ModuleConverter $converter
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    private function createModel(ModuleConverter $converter) {
        $path = app_path("Models/{$converter->name}.php");

        if(File::exists($path)) {
            return;
        }
        Artisan::call('make:model', ['name' => "Models/{$converter->name}", '--factory' => true]);

        $this->line("<fg=blue>Model[{$converter->name}]を生成しました</>");
        $this->line("<fg=blue>Factory[{$converter->name}Factory]を生成しました</>");
        $this->writeModel($converter);
    }

    /**
     * @param ModuleConverter $converter
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    private function writeModel(ModuleConverter $converter) {
        $path = app_path("Models/{$converter->name}.php");

        $file = File::get($path);
        $columns = collect($converter->columns)
            ->map(function ($x) {
                return "'{$x}'";
            })->toArray();
        $columns = implode($columns, ", ");
        $columns = '//protected $fillable = [' .$columns. ']';
        $file = str_replace('//', $columns, $file);

        File::put($path, $file);
    }

    /**
     * @param ModuleConverter $converter
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    private function writeFactory(ModuleConverter $converter) {
        $path = base_path("database/factories/{$converter->name}Factory.php");

        $file = File::get($path);

        $columns = implode($converter->columnsStr, "'',\n        ");
        $file = str_replace('//', $columns. "'',", $file);

        File::put($path, $file);
    }

    /**
     * @param ModuleConverter $converter
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    private function createSeeder(ModuleConverter $converter) {
        $name = str_plural($converter->name);
        $path = base_path("database/seeds/{$name}Seeder.php");

        if(File::exists($path)) {
            return;
        }

        $this->line("<fg=blue>Seeder[{$converter->name}Seeder]を生成しています</>");
        Artisan::call('make:seeder', ['name' => "{$name}Seeder"]);

        $this->writeSeeder($converter, $path);
    }

    /**
     * @param ModuleConverter $converter
     * @param $path
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    private function writeSeeder(ModuleConverter $converter, $path) {
        $file = File::get($path);

        $name = $converter->name;
        $file = str_replace(';', ";\nuse App\Models\\{$name};", $file);
        $file = str_replace('//', "{$name}::query()->truncate();", $file);
        File::put($path, $file);
    }

    private function createJsModel(ModuleConverter $converter) {
        $p = rtrim(ltrim(config('frontend.js_model_dir'), '/'), '/');
        $path = base_path("{$p}/{$converter->name}.js");

        if(File::exists($path)) {
            return;
        }
        $columns = implode($converter->columns, ',');
        Artisan::call('make:js-model', [
            'name' => "{$converter->name}",
            'columns' => $columns,
            'colType' => $converter->columnsType
        ]);
        $this->line("<fg=blue>js model[{$converter->name}]を生成しました</>");
    }

}


class ModuleConverter {

    public $name;
    public $file;
    public $columns = [];
    public $columnsStr = [];
    public $columnsType = [];

    function __construct(string $name, string $file)
    {
        $this->name = $name;
        $this->file = $file;

        $this->extractionColumns();
    }

    private function extractionColumns() {
        preg_match_all('/(?<=\$table\->).*?(?=\'\))/', $this->file, $matches);
        preg_match_all('/(?<=\(\').*/', implode($matches[0], "\n"), $mt);
        if(isset($mt[0])) {
            $this->columns = $mt[0];
            $this->columnsStr = collect($this->columns)
                ->map(function ($x) {
                    return "'{$x}' =>";
                })->toArray();
        }

        preg_match_all('/(?<=table\->).*?(?=\(\')/', $this->file, $m);
        if(isset($m[0])) {
            $this->columnsType = $m[0];
        }
    }
}
