<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;

class StorageController extends Controller
{
    public function viewfile($filename)
    {
        $filename = rawurldecode($filename);
        $fileNameOnly = basename($filename);
        /** @var \Illuminate\Filesystem\FilesystemAdapter $disk */
        $disk = Storage::disk('azure');

        abort_unless($disk->exists($filename), 404, 'File not found.');

        $fileUrl = $disk->temporaryUrl($filename, now()->addMinutes(10));

        return view('file-view', [
            'fileUrl' => $fileUrl,
            'fileName' => $fileNameOnly,
        ]);
    }
}
