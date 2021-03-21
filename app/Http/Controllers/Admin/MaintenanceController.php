<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin;
use App\Models\MaintenanceAccess;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use \Artisan;
use Illuminate\Validation\ValidationException;
use Validator;


class MaintenanceController extends Controller
{
    /**
     * 管理画面　メンテナンスモード
     * @param Request $request
     * @return Admin[]|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function index () {

        $isMaintenance = $this->isDownForMaintenance() ? true : false;
        $ipList = MaintenanceAccess::query()->get();

        $res = [
            'isMaintenance' => $isMaintenance,
            'ipList' => $ipList
        ];

        return $res;
    }

    /**
     * IPアドレス追加
     */
    public function addIpAddress(Request $request) {
        Validator::make($request->all(), [
            'ip' => 'required',
        ],[
            'ip.required' => 'IPアドレスは必須です'
        ])->validate();

        MaintenanceAccess::query()->create($request->all());

        return response()->json(['message' => 'IPアドレスを追加しました']);
    }

    public function deleteIpAddress($id) {
        $res = MaintenanceAccess::find($id);
        if(!$res) {
            throw ValidationException::withMessages(['message' =>'IPアドレス削除に失敗しました']);
        }

        $res->delete();
        return response()->json(['message' => 'IPアドレスを削除しました']);
    }


    /**
     * 管理画面　メンテナンスモード変更
     */
    public function change(Request $request) {

        if($request->isMaintenance) {
            $res = Artisan::call('up');
            if($res == 0) {
                return response()->json([
                    'message' => '通常モードに変更しました',
                    'isMaintenance' => $this->isDownForMaintenance()
                ]);
            }
        } else {
            $res = Artisan::call('down');
            if($res == 0) {
                return response()->json([
                    'message' => 'メンテナンスモードに変更しました',
                    'isMaintenance' => $this->isDownForMaintenance()
                ]);
            }
        }
    }


    // メンテナンスモードかチェック
    public function isDownForMaintenance()
    {
        return \File::exists(storage_path().'/framework/down');
    }

}
