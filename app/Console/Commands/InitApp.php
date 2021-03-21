<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class InitApp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:init';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'アプリケーションを初期化する';

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
        print("-- start: ".get_class($this)."\n");

        // キャッシュクリア等
        Artisan::call('app:fresh');

        printf ("\e[32m%s: \e[m %s\n", "start", "migrate: fresh");
        Artisan::call('migrate:fresh', ['--seed' => true]);
        printf ("\e[32m%s: \e[m %s\n\n", "finish", "migrate: fresh");

//        Artisan::call('storage:clear', ['--all' => true]);
//        Artisan::call('firebase:clear');

        // ジョブ実行
//        Artisan::call('queue:work');

        print("-- finish: ".get_class($this)."\n\n");
    }
}
