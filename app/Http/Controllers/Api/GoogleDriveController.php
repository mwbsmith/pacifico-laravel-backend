<?php

// app/Http/Controllers/Api/GoogleDriveController.php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\GoogleDriveService;
use Illuminate\Http\Request;

class GoogleDriveController extends Controller
{
    public function __construct(private GoogleDriveService $drive) {}

    public function index(string $folderId, Request $request)
    {
        $onlyParam = $request->query('only');
        $onlyMime  = $onlyParam === 'pdf' ? 'application/pdf' : null;
        // you can also accept ?mime=... and prefer that:
        $onlyMime  = $request->query('mime', $onlyMime);

        $files = $this->drive->listFolderRecursive($folderId, $onlyMime);

        return response()->json([
            'ok'       => true,
            'count'    => count($files),
            'folderId' => $folderId,
            'files'    => $files,
        ]);
    }
}