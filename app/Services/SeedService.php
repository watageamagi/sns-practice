<?php

namespace App\Services;

use DB;

class SeedService {

    private $model;
    private $useFactory = false;
    private $foreignKeyCheck = true;
    private $isTruncate = false;

    public function __construct()
    {
        $this->csv = new CsvService();
    }

    /**
     * @param $fileName
     * @return $this
     * @throws \Exception
     */
    public function csv($fileName) {
        $this->csv->set(public_path("data/master/{$fileName}.csv"));
        $this->csv->setSameHeader()->parse();
        return $this;
    }

    public function useFactory() {
        $this->useFactory = true;
        return $this;
    }

    public function setModel($model) {
        $this->model = $model;
        return $this;
    }

    public function notForeignKeyCheck() {
        $this->foreignKeyCheck = false;
        return $this;
    }

    public function truncate() {
        $this->isTruncate = true;
        return $this;
    }

    /**
     * @throws \Throwable
     */
    public function create() {

        if (!$this->foreignKeyCheck) {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        }

        if ($this->isTruncate) {
            $this->model::query()->truncate();
        }

        DB::transaction(function () use(&$success){
            collect($this->csv->data)
                ->each(function($x) {

                    $val = collect($x)->filter(function ($value) {
                        return $value || is_numeric($value);
                    })->toArray();

                    if ($this->useFactory) {
                        factory($this->model)->create($val);
                    } else {
                        $this->model::query()->create($val);
                    }
                });
        });

        if (!$this->foreignKeyCheck) {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }
    }

}
