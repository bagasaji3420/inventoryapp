<?php

use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Format;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

if (!function_exists('upload_image_webp')) {

    function upload_image_webp($source, $folder = 'uploads', $quality = 80)
    {
        $manager = ImageManager::usingDriver(Driver::class);

        $filename = Str::uuid() . '.webp';

        // ✅ support file upload & raw binary
        if (is_object($source) && method_exists($source, 'get')) {
            $image = $manager->decode($source->get());
        } else {
            // dari HTTP response (string)
            $image = $manager->decode($source);
        }

        $encoded = $image->encodeUsingFormat(
            Format::WEBP,
            quality: $quality
        );

        Storage::disk('public')->put(
            $folder . '/' . $filename,
            (string) $encoded
        );

        return $folder . '/' . $filename;
    }
}
