<?php

namespace App\Utils;

use Exception;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class ImageUploader
{
    /**
     * Get image by path
     *
     * @param string $image
     * @return string local path
     */
    public function upload(string $imagePath, string $disk = 'public'): string
    {
        $name = uniqid("", true) . ".png";
        $path = date('Y/m/d/') . $name;
        $image = Image::make($imagePath);
        $image->fit(200, 400);
        $writed = Storage::disk($disk)
            ->put(
                $path,
                $image->psrResponse()
                ->getBody()
                ->getContents()
            );
        if (!$writed) {
            throw new Exception("Can't write file $path");
        }
        return $path;
    }
}
