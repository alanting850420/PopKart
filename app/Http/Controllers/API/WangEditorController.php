<?php

namespace App\Http\Controllers\API;

use App\Admin\Form\Field\UploadField;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WangEditorController extends Controller
{
    use UploadField;
    /**
     * 針對 wangEditor 所寫的圖片上傳控制器
     * 
     * @param  Request $requst
     * @return Response
     */
    public function postUploadPicture(Request $request)
    {
        if (config('wang-editor.usingLocalPackageUploadServer', false)) {
            $res = [
                'errno' => 9999,
                'data' => [
                ],
                'info' => '上傳圖片失敗，原因為：不允許本地上傳',
            ];
            if ($request->hasFile('wang-editor-image-file')) {
                //
                $files = $request->file('wang-editor-image-file');
                $maxCount = config('wang-editor.uploadImgMaxLength', 5);
                if (count($files) > $maxCount) {
                    $res = array_replace(['info' => '上傳圖片失敗，原因為：一次性最多可上傳 '.$maxCount.' 張圖片'], $res);
                } else {
                    $data = $request->all();
                    $rules = [
                        'wang-editor-image-file.*'    => 'mimes:jpeg,png,gif|max:5120',
                    ];
                    $messages = [
                        'wang-editor-image-file.*.required' => '必須傳入文件',
                        'wang-editor-image-file.*.mimes'    => '文件類型不允許,請上傳常規的圖片(jpg、png、gif)文件',
                        'wang-editor-image-file.*.max'      => '文件過大,文件大小不得超出5MB',
                    ];
                    $validator = Validator::make($data, $rules, $messages);
                    if ($validator->passes()) {
                        $_data = [];
                        foreach ($files as $file) {
                            $this->initStorage();
                            $this->name = $this->getStoreName($file);
                            $this->dir('content');
                            $this->renameIfExists($file);
                            $path = $this->storage->putFileAs($this->getDirectory(), $file, $this->name);

                            if ($file->isValid()) {
                                $_data[] = '/uploads/' .$path;
                            }
                        }
                        $res = [
                            'errno' => 0,
                            'data' => $_data,
                        ];
                    } else {
                        $res = array_replace(['info' => $validator->messages()->first()], $res);
                    }
                }
            }
            return response()->json($res);
        } else {
            return abort(404);
        }
    }
}
