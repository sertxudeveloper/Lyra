<?php

namespace SertxuDeveloper\Lyra\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Testing\File;
use Illuminate\Http\Testing\MimeType;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;

class MediaManagerController extends Controller {

//  public function index(Request $request) {
//    $disk = $request->has('disk') ? $request->get('disk') : config('filesystems.default');
//
//    dd(Storage::disk($disk)->allDirectories()[0]);
//  }

  public function disks(Request $request) {
    $selectedDisk = $request->has('disk') && $request->get('disk') ? $request->get('disk') : config('filesystems.default');
    $availableDisks = collect(config('filesystems.disks'))->keys()->toArray();
    return ["disks" => $availableDisks, "selected" => $selectedDisk];
  }

  public function tree(Request $request) {
    $selectedDisk = $request->has('disk') && $request->get('disk') ? $request->get('disk') : config('filesystems.default');
    $rootPath = Storage::disk($selectedDisk)->getDriver()->getAdapter()->getPathPrefix();
    $folderTree = $this->fillArrayWithFileNodes(Storage::disk($selectedDisk)->directories(), $selectedDisk, $rootPath);
    return $folderTree;
  }

  public function files(Request $request) {
    $selectedDisk = $request->has('disk') && $request->get('disk') ? $request->get('disk') : config('filesystems.default');
    $selectedPath = $request->has('path') && $request->get('path') ? $request->get('path') : '/';
    $folders = $this->getFoldersInPath($selectedDisk, $selectedPath);
    $files = $this->getFilesInPath($selectedDisk, $selectedPath);
    return array_merge($folders->toArray(), $files->toArray());
  }

  private function getFoldersInPath($selectedDisk, $selectedPath) {
    return collect(Storage::disk($selectedDisk)->directories($selectedPath))->map(function ($folder) use ($selectedDisk) {
      return [
        "name" => basename($folder),
        "storage_path" => Storage::url(null),
        "path" => $folder,
        "mime" => "directory",
        "items" => count(Storage::disk($selectedDisk)->files($folder)) + count(Storage::disk($selectedDisk)->directories($folder)),
        "last_modified" => Storage::disk($selectedDisk)->lastModified($folder),
        "visibility" => Storage::disk($selectedDisk)->getVisibility($folder),
      ];
    });
  }

  private function getFilesInPath($selectedDisk, $selectedPath) {
    return collect(Storage::disk($selectedDisk)->files($selectedPath))->map(function ($file) use ($selectedDisk) {
      return [
        "name" => basename($file),
        "storage_path" => Storage::url($file),
        "path" => $file,
        "mime" => MimeType::from($file),
        "size" => Storage::disk($selectedDisk)->size($file),
        "last_modified" => Storage::disk($selectedDisk)->lastModified($file),
        "visibility" => Storage::disk($selectedDisk)->getVisibility($file),
      ];
    });
  }

  private function fillArrayWithFileNodes($dir, $disk, $rootPath) {
    $data = collect([]);

    foreach ($dir as $node) {
      $data->push([
        "name" => Arr::last(explode('/', $node)),
        "path" => str_replace($rootPath, "", $node),
        "children" => $this->fillArrayWithFileNodes(Storage::disk($disk)->directories($node), $disk, $rootPath)
      ]);
    }

    return $data;
  }
}
