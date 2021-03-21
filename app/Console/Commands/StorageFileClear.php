<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\LogService;
use Illuminate\Support\Facades\File;

class StorageFileClear extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'storage-file-clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '不要のストレージファイル削除';

    /** @var LogService  */
    private $logService;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
        $this->logService = new LogService();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {

        print("不要ストレージファイル削除バッチ開始\n");
        $this->logService->batchInfo('不要ストレージファイル削除バッチ開始');

        $files = [
            public_path('storage/pdf/manuscript'),
        ];

        collect($files)
            ->each(function ($x) {
                File::deleteDirectory($x);
            });

        print("不要ストレージファイル削除バッチ終了\n");
        $this->logService->batchInfo('不要ストレージファイル削除バッチ終了');

    }
}
