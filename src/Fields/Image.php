<?php

namespace SertxuDeveloper\Lyra\Fields;

use Illuminate\Support\Facades\Storage;

class Image extends File {

  protected $component = "image-field";

//  public function updateValue($field, $old) {
//    if ($this->prunable) Storage::disk($this->disk)->delete($old);
//
//    $folder = (!$this->folder) ? $this->column : $this->folder;
//    $paths = [];
//
//    $column = $this->column;
//    $filesKeys = collect(request()->files->keys())->filter(function ($value) use ($column) {
//      $explode = explode('-', $value);
//      array_pop($explode);
//      return implode('-', $explode) === $column;
//    });
//
//    foreach ($filesKeys as $fileKey) {
//      $path = request()->file($fileKey)->store($folder, $this->disk);
//      array_push($paths, $path);
//    }
//
//    if(count($paths) === 0) return null;
//    return count($paths) > 1 ? json_encode($paths) : $paths[0];
//  }
}
