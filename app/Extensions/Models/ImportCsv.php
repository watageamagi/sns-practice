<?php
namespace App\Extensions\Models;

use App\Services\CsvService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use function PHPSTORM_META\type;
use Illuminate\Support\Facades\Storage;

trait ImportCsv
{
    protected $csvColumns = [];

    /**
     *  CSVからデータをDBに取り込む
     */
    public function importCsv(Request $request, $doTruncate = false)
    {
        if (($errors = $this->isUploadFile($request)) !== true) {
            return response()->json($errors);
        }

        $className = get_class($this);

        //テーブルのカラム名
        $columns = $this->targetColumns();

        $dirName = uniqid(rand());
        $path = $request->file->store('csv/'.$dirName);
        $csv = new CsvService();
        $csv->set(storage_path("app/{$path}"));
        $csv->setSameHeader()->parse($columns);

        Storage::deleteDirectory('csv/'.$dirName);


        $collection = collect($csv->data);
        $csv->close();

        //エラーのみ
        $errors = $collection
            ->filter(function($x) {
                return Validator::make($x, $this->importRule())->fails();
            })
            ->map(function($x) {
                return Validator::make($x, $this->importRule())->errors()->all();
            })
            ->toArray();

        //正常なデータのみのコレクション生成
        $successCollection = $collection->filter(function($x) {
                        return Validator::make($x, $this->importRule())->passes();
                    });
        //正常なデータがあればインサート処理
        if ($successCollection->count() > 0) {
            \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            //サクセスのみ
            DB::transaction(function () use($csv, &$successCollection, $className, $doTruncate){

                if ($doTruncate) {
                    $className::query()->truncate();
                }

                //正常なデータのみインサートorアップデート
                $successCollection->map(function($x) use($className) {

                    $x = collect($x)->filter(function ($value) {
                        return $value;
                    })->toArray();

                    if (gettype($this->primaryKey) == 'array') {
                        $key = null;
                        $model = $className::create($x);

                    } else {
                        if (isset($x[$this->primaryKey]) && $this->keyType == 'int') {
                            $x[$this->primaryKey] = intval($x[$this->primaryKey]);
                        }

                        $key = isset($x[$this->primaryKey]) ? $x[$this->primaryKey] : null;
                        $model = $className::firstOrNew([$this->primaryKey => $key]);
                        $model->fill($x);
                        $model->save();
                    }

                    return $model;
                });


            });
            \DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }

        $success = $successCollection->toArray();

        return response()->json(['success' => $success, 'errors' => $errors]);
    }

    /**
     *  バリデーションルール（オーバーライド用）
     *  対象のModelに記述
     */
    protected function importRule() {
        return [];
    }

    /**
     *  対象のカラム設定（オーバーライド用）
     *  対象のModelに記述
     */
    public function targetColumns() {
        return [];
    }


    /**
     *  アップロードファイル判定
     */
    private function isUploadFile(Request $request)
    {
        $validator = Validator::make($request->all(),
                            [
                                'file' => 'required|file|mimetypes:text/plain|mimes:csv,txt'
                            ],
                            [
                               'file.required'  => 'ファイルを選択してください。',
                               'file.file'      => 'ファイルアップロードに失敗しました。',
                               'file.mimetypes' => 'ファイル形式が不正です。',
                               'file.mimes'     => 'ファイル拡張子が異なります。',
                            ]);
        if ($validator->fails()) {
            return $validator->errors();
        }
        return true;
    }

    /**
     *  appendsを無効
     */
    public function invalidAppends()
    {
        $this->appends = [];
    }
}
