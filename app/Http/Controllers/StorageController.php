<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StorageController extends Controller
{
    public function viewfile($filename){
        $fileUrl = \Storage::disk('azure')->temporaryUrl($filename, now()->addMinutes(10));
        return view('file-view', ['fileUrl' => $fileUrl]);
    }
}
