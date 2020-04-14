<?php

namespace SertxuDeveloper\Lyra\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Testing\MimeType;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use ZipArchive;

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

    if ($element['mime'] === 'directory') {
      Storage::disk($selectedDisk)->deleteDirectory($element['path']);
    } else {
      Storage::disk($selectedDisk)->delete($element['path']);
    }
  }

  public function newFolder(Request $request) {
    $selectedDisk = $request->has('disk') && $request->get('disk') ? $request->get('disk') : config('filesystems.default');
    $path = $request->get('path');
    $name = $request->get('name');
    Storage::disk($selectedDisk)->makeDirectory("$path/$name");
  }

  public function upload(Request $request) {
    $selectedDisk = $request->has('disk') && $request->get('disk') ? $request->get('disk') : config('filesystems.default');
    $path = $request->get('path');

    /** Get the file keys as "file-1", "file-2", "file-X" */
    $fileKeys = $this->getFileKeys('file');

    /** Iterate the file keys getted above */
    foreach ($fileKeys as $fileKey) {
      $this->uploadFile($request, $selectedDisk, $path, $fileKey);
    }
  }

  public function uploadFolder(Request $request) {
    $selectedDisk = $request->has('disk') && $request->get('disk') ? $request->get('disk') : config('filesystems.default');
    $basePath = $request->get('path');

    /** Get the file keys as "file-1", "file-2", "file-X" */
    $fileKeys = $this->getFileKeys('file');

    /** Iterate the file keys getted above */
    foreach ($fileKeys as $fileKey) {
      $id = explode('-', $fileKey)[1];
      $relativePath = $request->get("folder-$id");
      if ($basePath === '/') $basePath = null;
      $path = "$basePath/$relativePath";
      $this->uploadFile($request, $selectedDisk, $path, $fileKey);
    }
  }

  public function download(Request $request) {
    $selectedDisk = $request->has('disk') && $request->get('disk') ? $request->get('disk') : config('filesystems.default');
    $path = $request->get('element')['path'];
    $directories = Storage::disk($selectedDisk)->allDirectories();

    if (collect($directories)->contains(substr($path, 1))) {
      return $this->downloadFolder($selectedDisk, $path);
    } else {
      return Storage::disk($selectedDisk)->download($path);
    }
  }

  private function downloadFolder($selectedDisk, $path) {
    $temp_folder = sys_get_temp_dir();
    $zip_file = basename($path) . '.zip';
    $zip = new ZipArchive();
    $zip->open("$temp_folder/$zip_file", ZipArchive::CREATE | ZipArchive::OVERWRITE);

    $directories = Storage::disk($selectedDisk)->directories($path);
    $files = Storage::disk($selectedDisk)->files($path, true);

    foreach ($directories as $directory) {
      $directory = str_replace($path, basename($path), "/$directory");
      $zip->addEmptyDir($directory);
    }

    foreach ($files as $file) {
      $file_path = str_replace($path, basename($path), "/$file");
      $zip->addFromString($file_path, Storage::disk($selectedDisk)->get($file));
    }

    $zip->close();

    return response()->download("$temp_folder/$zip_file");
  }

  private function getFileKeys($needle) {
    return collect(request()->files->keys())->filter(function ($value) use ($needle) {
      $explode = explode('-', $value);
      array_pop($explode);
      return implode('-', $explode) === $needle;
    });
  }

  private function uploadFile(Request $request, $disk, $path, $file) {
    /** Check if the file should maintain the original name */
    if (config('lyra.media_manager.keep_original_name')) {
      /** Save the file with the original name and get the path */
      $request->file($file)->storeAs($path, $request->file($file)->getClientOriginalName(), $disk);
    } else {
      /** Save the file with a new name and get the path */
      $request->file($file)->store($path, $disk);
    }
  }

  private function getFoldersInPath($selectedDisk, $selectedPath) {
    return collect(Storage::disk($selectedDisk)->directories($selectedPath))->map(function ($folder) use ($selectedDisk) {
      $visibility = config("filesystems.disks.$selectedDisk.visibility", 'private');

      return [
        "name" => basename($folder),
        "storage_path" => $visibility === 'public' ? Storage::url(null) : null,
        "path" => "/$folder",
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
      $visibility = config("filesystems.disks.$selectedDisk.visibility", 'private');

      return [
        "name" => basename($file),
        "storage_path" => $visibility === 'public' ? Storage::url($file) : null,
        "path" => "/$file",
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
