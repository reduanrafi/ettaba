<?php
namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Intervention\Image\Facades\Image;

trait CommonFunctions
{
    public function UploadImage(UploadedFile $imageFile, $folderName="images", $height=null, $width=null)
    {
        $fileName       =  time().$imageFile->getClientOriginalName();
        $uploads = 'assets/images/uploads';
        $filePath       ='assets/images/'.$folderName;
        $image_resize = Image::make($imageFile->getRealPath());
        if ($width>0 && $height>0) {
            $image_resize->resize(null, $height ,function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });
//            $image_resize->fit($width,$height, function ($constraint) {
//                $constraint->aspectRatio();
//                $constraint->upsize();
//            });

        }
        if (!file_exists($filePath)) {
            mkdir($filePath, 666, true);
        }
        $image_resize->save($filePath.'/' .$fileName);
        $image = $filePath.'/'.$fileName;
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