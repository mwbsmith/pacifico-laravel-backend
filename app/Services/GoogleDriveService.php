<?php
// app/Services/GoogleDriveService.php
namespace App\Services;

use Google_Service_Drive;
use Google_Service_Drive_DriveFile;

class GoogleDriveService
{
    public function __construct(private Google_Service_Drive $drive) {}

    /**
     * Public entry: list all files under a folder tree.
     * $onlyMime like 'application/pdf' (null = no filter).
     */
    public function listFolderRecursive(string $folderId, ?string $onlyMime = null): array
    {
        // optional safety gate
        /*
        $allowed = config('services.google.drive_allowed_folder_id');
        if ($allowed && $allowed !== $folderId) {
            abort(403, 'Folder not allowed');
        }
        */

        $out = [];
        $this->walk($folderId, $onlyMime, $out, '');
        return $out;
    }

    /**
     * Depth-first traversal: includes real folders & follows folder shortcuts.
     * $path keeps a readable path like "Sub/Deeper/" (optional).
     */
    private function walk(string $folderId, ?string $onlyMime, array &$acc, string $path): void
    {
        // IMPORTANT: do NOT add $onlyMime here; we must see folders & shortcuts too
        $params = [
            'q'                         => "'$folderId' in parents and trashed = false",
            'fields'                    => 'nextPageToken,files(id,name,mimeType,size,createdTime,modifiedTime,parents,webViewLink,webContentLink,shortcutDetails(targetId,targetMimeType))',
            'supportsAllDrives'         => true,
            'includeItemsFromAllDrives' => true,
            'pageSize'                  => 200,
        ];

        do {
            $resp = $this->drive->files->listFiles($params);
            $files = $resp->getFiles() ?? [];

            foreach ($files as $f) {
                $mime = $f->getMimeType();
                $name = $f->getName();

                // 1) Real subfolder → recurse
                if ($mime === 'application/vnd.google-apps.folder') {
                    $this->walk($f->getId(), $onlyMime, $acc, $path.$name.'/');
                    continue;
                }

                // 2) Shortcut → follow target
                if ($mime === 'application/vnd.google-apps.shortcut') {
                    $sd = $f->getShortcutDetails();
                    $targetId   = $sd?->getTargetId();
                    $targetMime = $sd?->getTargetMimeType();

                    if ($targetId && $targetMime === 'application/vnd.google-apps.folder') {
                        $this->walk($targetId, $onlyMime, $acc, $path.$name.'/');
                        continue;
                    }

                    if ($targetId) {
                        $target = $this->drive->files->get($targetId, [
                            'fields' => 'id,name,mimeType,size,createdTime,modifiedTime,parents,webViewLink,webContentLink',
                            'supportsAllDrives' => true,
                        ]);
                        if (!$onlyMime || $target->getMimeType() === $onlyMime) {
                            $acc[] = $this->map($target, $path.$name);
                        }
                    }
                    continue;
                }

                // 3) Regular file at this level → apply filter if any
                if (!$onlyMime || $mime === $onlyMime) {
                    $acc[] = $this->map($f, $path.$name);
                }
            }

            $params['pageToken'] = $resp->getNextPageToken();
        } while ($params['pageToken'] ?? null);
    }

    /** Map Google file to array (adds a 'path' for UX). */
    private function map(Google_Service_Drive_DriveFile $f, string $pathWithoutTrailing = ''): array
    {
        $id = $f->getId();
        return [
            'id'            => $id,
            'name'          => $f->getName(),
            'path'          => $pathWithoutTrailing, // e.g. "Sub/Deeper/MyFile.pdf"
            'mimeType'      => $f->getMimeType(),
            'sizeBytes'     => $f->getSize() ? (int)$f->getSize() : null,
            'createdTime'   => $f->getCreatedTime(),
            'modifiedTime'  => $f->getModifiedTime(),
            'webViewLink'   => $f->getWebViewLink(),
            'webContentLink'=> $f->getWebContentLink(),
            'directDownload'=> $f->getWebContentLink() ?: "https://drive.google.com/uc?id={$id}&export=download",
        ];
    }
}