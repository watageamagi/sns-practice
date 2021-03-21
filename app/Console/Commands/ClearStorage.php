<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class ClearStorage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'storage:clear {--all}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'ストレージのファイルを削除する';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        print("--- start: ".get_class($this)."\n");

        $directories = new Collection();

        // allオプション設定時
        if ($this->option("all")) {
        };


        $this->deleteStorage($directories);

        print("--- finish: ".get_class($this)."\n\n");
    }

    /**
     * @param Collection $collection
     */
    private function deleteStorage(Collection $collection) {
        $collection->each(function ($x) {
            Storage::deleteDirectory($x);
            printf ("\e[32m%s: \e[m %s\n", "Deleted", $x);
        });
    }
}
