<?php

namespace App\Services;

use App\Models\FixManuscript;
use App\Models\Manuscript;
use App\Models\ManuscriptCase;
use App\Models\Point;
use Carbon\Carbon;
use Chumper\Zipper\Facades\Zipper;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;
use Sukohi\FluentCsv\FluentCsv;
use function foo\func;

class LogService {

    private $storage = '';

    public function __construct(){
        $storage = \Storage::disk(env('STORAGE_DISK', 'public'));
        if(!$storage->exists('/logs')) {
            $storage->makeDirectory('/logs');
        }
    }

    /**
     * @param $message
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function batchInfo($message, $content=[]) {
        $this->putInfo('batch', $message, $content);
    }

    /**
     * @param $fileName
     * @param $massage
     * @param bool $isDiary
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    private function putInfo($fileName, $massage, $context = []) {
        $this->storage = \Storage::disk(env('STORAGE_DISK', 'public'));
        if(!$this->storage->exists('/logs')) {
            $this->storage->makeDirectory('/logs');
        }

        $log = new Logger($fileName);
        $path = storage_path('logs/'.$fileName);
        $level = config('app.log_level');
        $log->pushHandler(new RotatingFileHandler($path, 0, $level, true, 0777));
        $log->info($massage, $context);

        $now = Carbon::now()->format('Y-m-d');

        $file = \File::get(storage_path('logs/'.$fileName. '-' . $now));

        $this->storage->put('/logs/'.$fileName.'/'. $now, $file);
    }
}
