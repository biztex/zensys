<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use InterventionImage;
use App\Models\Kind;
use App\Models\User;
use Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class KindsController extends Controller
{
    // 一覧画面
    public function index()
    {
        return view('client.kinds.index');
    }
    // 作成画面 
    public function create()
    {
        return view('client.kinds.create');
    }
    // 作成処理
    public function store(Request $request)
    {
        $rules = [
            'name' => ['required', 'string', 'max:50'],
        ];
        $this->validate($request, $rules);
        $total_records = Kind::count();
        if ($total_records >= 20) {
            throw ValidationException::withMessages(['full_Kinds' => 'これ以上のサイトカテゴリは追加できません']);
        }
        $Kinds = new Kind;
        $max_number = Kind::max('number');
        $Kinds->number = $max_number + 1;
        $Kinds->name = $request->name;
        $Kinds->save();
        return redirect('/client/kinds')->with('message', '追加が完了しました');
    }

    // 詳細画面
    public function show($id)
    {
    }

    // 編集画面
    public function edit($id)
    {
        $kinds = Kind::find($id);
        return view('client.kinds.edit', compact('kinds'));
    }
    // 編集画面 users
    public function editUser($id)
    {
        $users = User::find($id);
        return view('kinds.users.edit', compact('users'));
    }
 
    // 更新処理
    public function update(Request $request, $id)
    {
         $rules = [
             'name' => ['required', 'string', 'max:50'],
         ];
         $this->validate($request, $rules);
         $Kinds = Kind::find($id);
         $Kinds->name = $request->name;
         $Kinds->save();
         return redirect()->back()->with('message', '変更が完了しました');
    }

    // 複製処理
    public function replicate($id)
    {
        $oldrow = Kind::find($id);
        $newrow = $oldrow->replicate();
        $newrow->save();
        return redirect()->back();
    }

    // 削除処理
    public function destroy($id)
    {
        $Kinds = Kind::destroy($id);
        return redirect()->back();
    }

    // 選択削除処理
    public function destroySelected(Request $request)
    {
        $ids = explode(',', $request->ids);
        $Kinds = Kind::destroy($ids);
        return redirect()->back();
    }

    // JSON返却
    public function json()
    {
        try {
           $result = Kind::all();
        } catch(\Exception $e){
            $result = [
                'result' => false,
                'error' => [
                    'messages' => [$e->getMessage()]
                ],
            ];
            return $this->resConversionJson($result, $e->getCode());
        }
        return $this->resConversionJson($result);
    }

    // 上記メソッド内ファンクション
    private function resConversionJson($result, $statusCode=200)
    {
        if(empty($statusCode) || $statusCode < 100 || $statusCode >= 600){
            $statusCode = 500;
        }
        return response()->json($result, $statusCode, ['Content-Type' => 'application/json'], JSON_UNESCAPED_SLASHES);
    }

    // ユーザー情報CSVダウンロード
    public function downloadUserCsv()
    {
        // コールバック関数に１行ずつ書き込んでいく処理を記述
        $callback = function () {
            // 出力バッファをopen
            $stream = fopen('php://output', 'w');
            // 文字コードをShift-JISに変換
            stream_filter_prepend($stream, 'convert.iconv.utf-8/cp932//TRANSLIT');
            // ヘッダー行
            fputcsv($stream, [
    	    'id',
    	    'is_member（1=会員）',
    	    'name_last',
    	    'name_first',
    	    'kana_last',
    	    'kana_first',
    	    'tel',
    	    'tel2',
    	    'email',
    	    'gender（0=回答なし,1=男性,2=女性）',
    	    'birth_day',
    	    'postal_code',
    	    'prefecture',
    	    'address',
    	    'created_at',
    	    'updated_at',
            ]);
            // データ
            $users = User::orderBy('id', 'asc');
            // ２行目以降の出力
            // cursor()メソッドで１レコードずつストリームに流す
            foreach ($users->cursor() as $user) {
                fputcsv($stream, [
                    $user->id,
                    $user->is_member,
                    $user->name_last,
                    $user->name_first,
                    $user->kana_last,
                    $user->kana_first,
                    $user->tel,
                    $user->tel2,
                    $user->email,
                    $user->gender,
                    $user->birthday,
                    $user->postal_code,
                    $user->prefecture,
                    $user->address,
                    $user->created_at,
                    $user->updated_at,
                ]);
            }
            fclose($stream);
        };
        // 保存ファイル名
        $filename = sprintf('会員リスト_%s.csv', date('Ymd'));
    	
        // ファイルダウンロードさせるために、ヘッダー出力を調整
        $header = [
            'Content-Type' => 'application/octet-stream',
        ];
        return response()->streamDownload($callback, $filename, $header);
    }
}

