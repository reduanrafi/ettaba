<?php
namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Intervention\Image\Facades\Image;

trait CommonFunctions
{
    public function UploadImage(UploadedFile $imageFile, $folderName="images", $height=null, $width=null)
    {
        $safeOriginal   = preg_replace('/[^A-Za-z0-9._-]/', '_', $imageFile->getClientOriginalName());
        $fileName       = time() . '_' . $safeOriginal;
        $uploads        = 'assets/images/uploads';
        $filePath       = 'assets/images/' . $folderName;
        $image_resize   = Image::make($imageFile->getRealPath());
        if ($width > 0 && $height > 0) {
            $image_resize->resize(null, $height, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
        }
        if (!file_exists($filePath)) {
            @mkdir($filePath, 0755, true);
        }
        @chmod($filePath, 0755);

        $fullSavedPath = $filePath . '/' . $fileName;
        $image_resize->save($fullSavedPath);
        @chmod($fullSavedPath, 0644);

        // Also mirror to sibling website public assets if on the same server
        try {
            $siblingWebsiteDir = dirname(base_path(), 2) . '/ettabashop-website/' . $filePath;
            if (is_dir(dirname(base_path(), 2) . '/ettabashop-website')) {
                if (!file_exists($siblingWebsiteDir)) {
                    @mkdir($siblingWebsiteDir, 0755, true);
                }
                @chmod($siblingWebsiteDir, 0755);
                @copy($fullSavedPath, $siblingWebsiteDir . '/' . $fileName);
                @chmod($siblingWebsiteDir . '/' . $fileName, 0644);
            }
        } catch (\Throwable $e) {
            // ignore mirror error
        }

        $image = $filePath . '/' . $fileName;
        return $image;
    }

    public function GetCheckBoxValue($data,$fieldName)
    {
        if (isset($data[$fieldName]) && $data[$fieldName]=='on') {
            return 1;
        }
        else
            return 0;
    }
}
