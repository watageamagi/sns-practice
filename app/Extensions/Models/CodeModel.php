<?php

namespace App\Extensions\Models;

use Illuminate\Database\Eloquent\Model;

use App\Extensions\Models\ImportCsv;

abstract class CodeModel extends Model
{
    use ImportCsv;

    // プライマリーキーのカラム名
    protected $primaryKey = 'code';

    // プライマリーキーの型
    protected $keyType = 'string';

    // プライマリーキーは自動連番か？
    public $incrementing = false;
}
