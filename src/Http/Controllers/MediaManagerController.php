<?php

namespace SertxuDeveloper\Lyra\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Testing\File;
use Illuminate\Http\Testing\MimeType;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use SertxuDeveloper\Lyra\Lyra;

class MediaManagerController extends Controller {

  public function disks(Request $request) {
    $selectedDisk = $request->has('disk') && $request->get('disk') ? $request->get('disk') : config('filesystems.default');
    $availableDisks = collect(config('filesystems.disks'))->keys()->toArray();
    return ["disks" => $availableDisks, "selected" => $selectedDisk];
  }

  public function tree(Request $request) {
    $selectedDisk = $request->has('disk') && $request->get('disk') ? $request->get('disk') : config('filesystems.default');
    $rootPath = Storage::disk($selectedDisk)->getDriver()->getAdapter()->getPathPrefix();
    $folderTree = $this->fillArrayWithFileNodes(Storage::disk($selectedDisk)->directories(), $selectedDisk, $rootPath);
    return [
      "children" => $folderTree,
      "files" => collect(Storage::disk($selectedDisk)->files('/'))->map(function ($file) {
        return ["name" => basename($file)];
      })
    ];
  }

  public function files(Request $request) {
    $selectedDisk = $request->has('disk') && $request->get('disk') ? $request->get('disk') : config('filesystems.default');
    $selectedPath = $request->has('path') && $request->get('path') ? $request->get('path') : '/';
    $folders = $this->getFoldersInPath($selectedDisk, $selectedPath);
    $files = $this->getFilesInPath($selectedDisk, $selectedPath);
    return array_merge($folders->toArray(), $files->toArray());
  }

  public function rename(Request $request) {
    $selectedDisk = $request->has('disk') && $request->get('disk') ? $request->get('disk') : config('filesystems.default');

    $oldPath = $request->get('element')['path'];
    $newPath = explode('/', $oldPath);
    array_pop($newPath);
    $newPath = implode('/', $newPath);
    $newPath = $newPath ? $newPath . '/' . $request->get('newName') : $request->get('newName');

    Storage::disk($selectedDisk)->move($oldPath, $newPath);
  }

  public function move(Request $request) {
    $selectedDisk = $request->has('disk') && $request->get('disk') ? $request->get('disk') : config('filesystems.default');
    $element = $request->get('element');
    $oldPath = $element['path'];
    $newPath = $request->get('newPath');
    $newPath = $newPath . '/' . $element['name'];

    Storage::disk($selectedDisk)->move($oldPath, $newPath);
  }

  public function copy(Request $request) {
    $selectedDisk = $request->has('disk') && $request->get('disk') ? $request->get('disk') : config('filesystems.default');
    $element = $request->get('element');
    $oldPath = $element['path'];
    $newPath = $request->get('newPath');
    $newPath = $newPath . '/' . $element['name'];

    if (Storage::disk($selectedDisk)->exists($newPath)) {
      $newPath = $request->get('newPath');
      $filename = pathinfo($element['name'])['filename'];
      $extension = pathinfo($element['name'])['extension'];
      $newPath = $newPath . '/' . $filename . '-copy.' . $extension;
    }

    Storage::disk($selectedDisk)->copy($oldPath, $newPath);
  }

  public function delete(Request $request) {
    $selectedDisk = $request->has('disk') && $request->get('disk') ? $request->get('disk') : config('filesystems.default');
    $element = $request->get('element');

    Storage::disk($selectedDisk)->delete($element['path']);
  }

  private function getFoldersInPath($selectedDisk, $selectedPath) {
    return collect(Storage::disk($selectedDisk)->directories($selectedPath))->map(function ($folder) use ($selectedDisk) {
      return [
        "name" => basename($folder),
        "storage_path" => Storage::url(null),
        "path" => $folder,
        "mime" => "directory",
        "files_count" => count(Storage::disk($selectedDisk)->files($folder)),
        "directories_count" => count(Storage::disk($selectedDisk)->directories($folder)),
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
        "children" => $this->fillArrayWithFileNodes(Storage::disk($disk)->directories($node), $disk, $rootPath),
        "files" => collect(Storage::disk($disk)->files($node))->map(function ($file) {
          return ["name" => basename($file)];
        })
      ]);
    }

    return $data;
  }
}
