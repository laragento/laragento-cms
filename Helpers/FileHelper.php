<?php


namespace Laragento\Cms\Helpers;


use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class FileHelper
{
    const COMPRESSIONRATE = 90;

    public function preparePath($folder): string
    {
        $hash = md5(microtime() . $folder);
        $folder = $folder . '/' . $hash;
        return $this->subPath($folder);
    }

    public function storeUploadedFile(
        UploadedFile $file,
        $path
    ) {

        if (!Storage::exists($path)) {
            try {
                Storage::makeDirectory($path);
            } catch (\Exception $exception) {
                throw $exception;
                dd($path);
            }
        }

        try {
            $img = Image::make($file);
            $this->compressImage($img,$path);
            $img->save(storage_path('app/' . $path . '/' . $file->getClientOriginalName()));
            $file = Storage::get($path . '/' . $file->getClientOriginalName());
        } catch (\Throwable $exception) {
            $file->move(storage_path('app/' . $path), $file->getClientOriginalName());
        }

        return $file;
    }

    protected function subPath($folder)
    {
        return 'public/cms/' . $folder;
    }

    /**
     * @param $file
     */
    protected function portraitToLandscape($img)
    {

    }

    /**
     * @param $file
     */
    protected function landscapeToPortrait($img)
    {

    }

    protected function compressImage($img,$path)
    {
        $img->resize(480, null, function ($constraint) {
            $constraint->aspectRatio();
        });
    }


}