<?php

namespace App\Http\Controllers\v1;

use App\Model\Common\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileController extends CommonController
{
    /**
     * 文件上传统一接口
     *
     * @param Request $request
     * @return array|\Illuminate\Http\UploadedFile|null
     */
    public function upload(Request $request)
    {
        $file =  $request->file('photo');
        $type = $request->header('type');
        $name = $file->getClientOriginalName();
        $path = Storage::putFile('public/file', $file);
        $file = File::create([
            'file_name' => $name,
            'file_path' => $path,
            'file_type' => $type
        ]);

        return $file;
    }
}
