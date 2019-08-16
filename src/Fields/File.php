<?php

namespace SertxuDeveloper\Lyra\Fields;

use Illuminate\Support\Facades\Storage;

class File extends Field {

  protected $component = "file-field";

  protected $hideOnIndex = true;
  protected $multiple = false;
  protected $disk = 'public';
  protected $prunable = false;
  protected $folder = false;
  protected $originalName = false;

  public function multiple() {
    $this->multiple = true;
    return $this;
  }

  public function disk($disk) {
    $this->disk = $disk;
    return $this;
  }

  public function prunable() {
    $this->prunable = true;
    return $this;
  }

  public function folder($folder) {
    $this->folder = $folder;
    return $this;
  }

  public function originalName() {
    $this->originalName = true;
    return $this;
  }

  public function get() {

    return [
      "component" => $this->component,
      "name" => $this->name,
      "column" => $this->column,
      "description" => $this->description,
      "sortable" => $this->sortable,
      "primary" => $this->primary,
      "multiple" => $this->multiple,
      "storage_path" => Storage::disk($this->disk)->url(null),
      "value" => ($this->multiple) ? json_decode($this->value) : $this->value,
    ];
  }

  public function saveValue($field, $resource) {
    if ($this->multiple) {
      $old = json_decode($resource[$this->column]);
      if (!$old) $old = [];
    } else {
      $old = $resource[$this->column];
    }

    if ($this->prunable) {
      collect($old)->diff($field['value'])->each(function ($file) {
        Storage::disk($this->disk)->delete($file);
      });
    }

    $folder = (!$this->folder) ? $this->column : $this->folder;
    $paths = collect($field['value'])->intersect($old);

    $column = $this->column;
    $filesKeys = collect(request()->files->keys())->filter(function ($value) use ($column) {
      $explode = explode('-', $value);
      array_pop($explode);
      return implode('-', $explode) === $column;
    });

    foreach ($filesKeys as $fileKey) {
      if ($this->originalName) {
        $path = request()->file($fileKey)->storeAs($folder, request()->file($fileKey)->getClientOriginalName(), $this->disk);
      } else {
        $path = request()->file($fileKey)->store($folder, $this->disk);
      }
      $paths->push($path);
    }

    $paths = $paths->values();
    $resource->{$this->column} = ($this->multiple) ? json_encode($paths->toArray()) : $paths->first() ;
  }
}
