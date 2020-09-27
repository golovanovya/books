<?php

namespace App\Book;

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
    public function upload(string $imagePath): string
    {
        $name = uniqid("", true) . ".png";
        $path = date('Y/m/d/') . $name;
        $image = Image::make($imagePath);
        $image->fit(200, 400);
        $writed = Storage::disk('public')
            ->put(
                $path,
                $image->psrResponse()
                ->getBody()
                ->getContents()
            );
        if (!$writed) {
            throw new Exception("Can't write fiel $path");
        }
        return $path;
    }
}
