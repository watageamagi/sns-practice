<?php

namespace App\Console\Commands;

use App\Models\Calender;
use Carbon\Carbon;
use Illuminate\Console\Command;
use App\Services\LogService;
use \Artisan;
use Illuminate\Http\Request;

class MaintenanceModeChange extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'MaintenanceModeChange';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'メンテナンスモードバッチ';

    private $logService;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->logService = new LogService();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        // 現在時刻取得
        $now = Carbon::now();

        $calender = Calender::query()
            ->whereDate('date', $now)
            ->first();

//        $this->logService->batchInfo("-----\n");

        if(!empty($calender->down_time) && $now->format('H:i:s') >= $calender->down_time) {
            $res = Artisan::call('down');
            if($res == 0) {
                print("メンテナンスモードにしました！\n");
                $this->logService->batchInfo('メンテナンスモードにしました');
                Calender::query()
                    ->whereDate('date', $now)
                    ->first()
                    ->fill([
                        'down_time' => null
                    ])->save();
            }
        }

        if(!empty($calender->up_time) && $now->format('H:i:s') >= $calender->up_time) {
            $res = Artisan::call('up');
            if($res == 0) {
                print("メンテナンスモード解除しました！\n");
                $this->logService->batchInfo('メンテナンスモード解除しました');
                Calender::query()
                    ->whereDate('date', $now)
                    ->first()
                    ->fill([
                        'up_time' => null
                    ])->save();
            }
        }

    }
}
