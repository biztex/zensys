<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use InterventionImage;
use App\Models\Company;
use Auth;

class CompaniesController extends Controller
{
    // 一覧画面
    public function index()
    {
        return view('client.companies.index');
    }
    // 作成画面 
    public function create()
    {
        $companies = Company::all();
        return view('client.companies.create', compact('companies'));
    }
    // 作成処理
    public function store(Request $request)
    {
        ini_set('memory_limit', '256M');
        $rules = [
            //'is_listed' => ['required'],
            'name' => ['required', 'string', 'max:50'],
            'title' => ['required', 'string', 'max:100'],
            'link' => ['required', 'string', 'max:200'],
            'pubdate' => ['required', 'string', 'max:50'],
            'copyright' => ['required', 'string', 'max:50'],
            'description' => ['required', 'string', 'max:15'],
            'file_path1' => 'mimes:jpeg,jpg,png,gif|max:5000',
            'file_path2' => 'mimes:jpeg,jpg,png,gif|max:5000',
        ];
        $this->validate($request, $rules);
        if ($file1 = $request->file_path1) {
            // オリジナルサイズで保存
            $time = time();
            $fileName1 = "origin-" . $time . $file1->getClientOriginalName();
            $target_path = public_path('uploads/');
            $maked_file1 = InterventionImage::make($file1);
            $maked_file1->save($target_path .'/' . $fileName1);
            // ファイルコピーしてリサイズ保存
            copy($target_path . "/". $fileName1, $target_path . "/" . $time . $file1->getClientOriginalName());
            $thumbnail_file1 = $time . $file1->getClientOriginalName();
            $thumbnail_path1 = $target_path . "/" . $time . $file1->getClientOriginalName();
            $maked_thumbnail_file1 = InterventionImage::make($thumbnail_path1);
            if ($maked_thumbnail_file1->width() > $maked_thumbnail_file1->height()) {
                $maked_thumbnail_file1
                    ->resizeCanvas($maked_thumbnail_file1->width(), floor($maked_thumbnail_file1->width() * 0.667))
                    ->save($thumbnail_path1);
            } else if ($maked_thumbnail_file1->width() == $maked_thumbnail_file1->height()) {
                $maked_thumbnail_file1->resizeCanvas($maked_thumbnail_file1->width(), $maked_thumbnail_file1->width())
                    ->save($thumbnail_path1);
            } else {
                $maked_thumbnail_file1
                    ->resizeCanvas(floor($maked_thumbnail_file1->height() * 1.5), $maked_thumbnail_file1->height())
                    ->save($thumbnail_path1);
            }
        } else {
            $thumbnail_file1 = "";
        }


        if ($logo = $request->logo) {
            // オリジナルサイズで保存
            $time = time();
            $logoNAMe = "origin-" . $time . $logo->getClientOriginalName();
            $target_path = public_path('uploads/');
            $maked_file1 = InterventionImage::make($logoNAMe);
            $maked_file1->save($target_path .'/' . $logoNAMe);
            // ファイルコピーしてリサイズ保存
            copy($target_path . "/". $logoNAMe, $target_path . "/" . $time . $logo->getClientOriginalName());
            $logo_file1 = $time . $logo->getClientOriginalName();
            $thumbnail_path1 = $target_path . "/" . $time . $logo->getClientOriginalName();
            $maked_thumbnail_file1 = InterventionImage::make($thumbnail_path1);
            if ($maked_thumbnail_file1->width() > $maked_thumbnail_file1->height()) {
                $maked_thumbnail_file1
                    ->resizeCanvas($maked_thumbnail_file1->width(), floor($maked_thumbnail_file1->width() * 0.667))
                    ->save($thumbnail_path1);
            } else if ($maked_thumbnail_file1->width() == $maked_thumbnail_file1->height()) {
                $maked_thumbnail_file1->resizeCanvas($maked_thumbnail_file1->width(), $maked_thumbnail_file1->width())
                    ->save($thumbnail_path1);
            } else {
                $maked_thumbnail_file1
                    ->resizeCanvas(floor($maked_thumbnail_file1->height() * 1.5), $maked_thumbnail_file1->height())
                    ->save($thumbnail_path1);
            }
        } else {
            $logo_file1 = "";
        }
        if ($file2 = $request->file_path2) {
            // オリジナルサイズで保存
            $time = time();
            $fileName2 = "origin-" . $time . $file2->getClientOriginalName();
            $target_path = public_path('uploads/');
            $maked_file2 = InterventionImage::make($file2);
            $maked_file2->save($target_path .'/' . $fileName2);
            // ファイルコピーしてリサイズ保存
            copy($target_path . "/". $fileName2, $target_path . "/" . $time . $file2->getClientOriginalName());
            $thumbnail_file2 = $time . $file2->getClientOriginalName();
            $thumbnail_path2 = $target_path . "/" . $time . $file2->getClientOriginalName();
            $maked_thumbnail_file2 = InterventionImage::make($thumbnail_path2);
            if ($maked_thumbnail_file2->width() > $maked_thumbnail_file2->height()) {
                $maked_thumbnail_file2
                    ->resizeCanvas($maked_thumbnail_file2->width(), floor($maked_thumbnail_file2->width() * 0.667))
                    ->save($thumbnail_path2);
            } else if ($maked_thumbnail_file2->width() == $maked_thumbnail_file2->height()) {
                $maked_thumbnail_file2->resizeCanvas($maked_thumbnail_file2->width(), $maked_thumbnail_file2->width())
                    ->save($thumbnail_path2);
            } else {
                $maked_thumbnail_file2
                    ->resizeCanvas(floor($maked_thumbnail_file2->height() * 1.5), $maked_thumbnail_file2->height())
                    ->save($thumbnail_path2);
            }
        } else {
            $thumbnail_file2 = "";
        }

        $companies = Company::find($id);
        $companies->id = $request->id;
        $companies->name = $request->name;
        $companies->title = $request->title;
        $companies->link = $request->link;
        $companies->description = $request->description;
        $companies->pubdate = $request->pubdate;
        $companies->copyright = $request->copyright;
        $companies->file_path1 = $thumbnail_file1;
        $companies->file_path2 = $thumbnail_file2;

        $companies->save();
        return redirect()->back()->with('message', '変更が完了しました');
    }

    // 詳細画面
    public function show($id)
    {
    }

    // 編集画面
    public function edit($id)
    {
        $companies = Company::find($id);
        return view('client.companies.edit', compact('companies'));
    }
 
    // 更新処理
    public function update(Request $request, $id)
    {
        ini_set('memory_limit', '256M');
        $rules = [
            'name' => ['required', 'string', 'max:50'],
            'company_number' => ['required', 'string', 'max:255'],
            'postal_code' => ['required', 'string', 'max:8'],
            'prefecture' => ['required', 'string', 'max:10'],
            // 'area' => ['required', 'string', 'max:100'],
            'address' => ['required', 'string', 'max:200'],
            'buildings' => ['nullable', 'string', 'max:100'],
            'url' => ['nullable', 'string', 'max:200'],
            'url2' => ['nullable', 'string', 'max:200'],
            'tel_inquiry' => ['required', 'string', 'max:15'],
            'memo' => ['nullable', 'string', 'max:600'],
            'file_path1' => 'mimes:jpeg,jpg,png,gif|max:5000',
            'file_path2' => 'mimes:jpeg,jpg,png,gif|max:5000',
        ];
        $this->validate($request, $rules);
        if ($file1 = $request->file_path1) {
            // オリジナルサイズで保存
            $time = time();
            $fileName1 = "origin-" . $time . $file1->getClientOriginalName();
            $target_path = public_path('uploads/');
            $maked_file1 = InterventionImage::make($file1);
            $maked_file1->save($target_path .'/' . $fileName1);
            // ファイルコピーしてリサイズ保存
            copy($target_path . "/". $fileName1, $target_path . "/" . $time . $file1->getClientOriginalName());
            $thumbnail_file1 = $time . $file1->getClientOriginalName();
            $thumbnail_path1 = $target_path . "/" . $time . $file1->getClientOriginalName();
            $maked_thumbnail_file1 = InterventionImage::make($thumbnail_path1);
            if ($maked_thumbnail_file1->width() > $maked_thumbnail_file1->height()) {
                $maked_thumbnail_file1
                    ->resizeCanvas($maked_thumbnail_file1->width(), floor($maked_thumbnail_file1->width() * 0.667))
                    ->save($thumbnail_path1);
            } else if ($maked_thumbnail_file1->width() == $maked_thumbnail_file1->height()) {
                $maked_thumbnail_file1->resizeCanvas($maked_thumbnail_file1->width(), $maked_thumbnail_file1->width())
                    ->save($thumbnail_path1);
            } else {
                $maked_thumbnail_file1
                    ->resizeCanvas(floor($maked_thumbnail_file1->height() * 1.5), $maked_thumbnail_file1->height())
                    ->save($thumbnail_path1);
            }
        } else {
            $fileName1 = "";
            $thumbnail_file1 = NULL;
        }
        if ($file2 = $request->file_path2) {
            // オリジナルサイズで保存
            $time = time();
            $fileName2 = "origin-" . $time . $file2->getClientOriginalName();
            $target_path = public_path('uploads/');
            $maked_file2 = InterventionImage::make($file2);
            $maked_file2->save($target_path .'/' . $fileName2);
            // ファイルコピーしてリサイズ保存
            copy($target_path . "/". $fileName2, $target_path . "/" . $time . $file2->getClientOriginalName());
            $thumbnail_file2 = $time . $file2->getClientOriginalName();
            $thumbnail_path2 = $target_path . "/" . $time . $file2->getClientOriginalName();
            $maked_thumbnail_file2 = InterventionImage::make($thumbnail_path2);
            if ($maked_thumbnail_file2->width() > $maked_thumbnail_file2->height()) {
                $maked_thumbnail_file2
                    ->resizeCanvas($maked_thumbnail_file2->width(), floor($maked_thumbnail_file2->width() * 0.667))
                    ->save($thumbnail_path2);
            } else if ($maked_thumbnail_file2->width() == $maked_thumbnail_file2->height()) {
                $maked_thumbnail_file2->resizeCanvas($maked_thumbnail_file2->width(), $maked_thumbnail_file2->width())
                    ->save($thumbnail_path2);
            } else {
                $maked_thumbnail_file2
                    ->resizeCanvas(floor($maked_thumbnail_file2->height() * 1.5), $maked_thumbnail_file2->height())
                    ->save($thumbnail_path2);
            }
        } else {
            $fileName2 = "";
            $thumbnail_file2 = NULL;
        }
        $companies = Company::find($id);
        $companies->id = $request->id;
        $companies->name = $request->name;
        $companies->postal_code = $request->postal_code;
        $companies->prefecture = $request->prefecture;
        // $companies->area = $request->area;
        $companies->address = $request->address;
        $companies->buildings = $request->buildings;
        $companies->url = $request->url;
        $companies->url2 = $request->url2;
        $companies->tel_inquiry = $request->tel_inquiry;
        $companies->memo = $request->memo;

          $companies->company_number = $request->company_number;
        if (!empty($companies->file_path1) && $companies->file_path1 == $request->old_file_path1) {
        } else {
            $companies->file_path1 = $thumbnail_file1;
        }
        if (!empty($companies->file_path2) && $companies->file_path2 == $request->old_file_path2) {
        } else {
            $companies->file_path2 = $thumbnail_file2;
        }
        $companies->save();
        return redirect()->back()->with('message', '変更が完了しました');
    }

    // 複製処理
    public function replicate($id)
    {
        $oldrow = Company::find($id);
        $newrow = $oldrow->replicate();
        $newrow->save();
        return redirect()->back();
    }

    // 削除処理
    public function destroy($id)
    {
        $companies = Company::destroy($id);
        return redirect()->back();
    }

    // 選択削除処理
    public function destroySelected(Request $request)
    {
        $ids = explode(',', $request->ids);
        $companies = Company::destroy($ids);
        return redirect()->back();
    }

    // JSON返却
    public function json()
    {
        try {
           $result = Company::all();
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
}

