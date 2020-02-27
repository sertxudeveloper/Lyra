<?php

namespace SertxuDeveloper\Lyra\Fields;

use Illuminate\Support\Facades\Storage;

class File extends Field {

  protected $component = "file-field";
  protected $defaultDisk = 'public';

  public function multiple() {
    $this->data->put('multiple', true);
    return $this;
  }

  public function disk($disk) {
    $this->data->put('disk', $disk);
    return $this;
  }

  public function prunable() {
    $this->data->put('prunable', true);
    return $this;
  }

  public function folder($folder) {
    $this->data->put('folder', $folder);
    return $this;
  }

  public function originalName() {
    $this->data->put('originalName', true);
    return $this;
  }

  public function delete($model) {
    if ($this->data->get('prunable')) {
      $file = $model[$this->data->get('column')];
      if (!$this->data->get('disk')) $this->data->put('disk', $this->defaultDisk);
      Storage::disk($this->data->get('disk'))->delete($file);
    }
  }

  public function getValue($model, $type) {
    if (!$this->data->get('disk')) $this->data->put('disk', $this->defaultDisk);
    $this->data->put('storage_path', Storage::disk($this->data->get('disk'))->url(null));

    return parent::getValue($model, $type);
  }

  protected function retrieveValue($model) {
    if (!isset($model[$this->data->get('column')])) return null;

    /** Decode the value if required */
    if ($this->data->get('multiple')) {
      return json_decode($model[$this->data->get('column')]);
    } else {
      return $model[$this->data->get('column')];
    }
  }

  public function saveValue($field, $resource) {
    /** Check if there is a disk defined, if not set the default disk */
    if (!$this->data->get('disk')) $this->data->put('disk', $this->defaultDisk);

    /** Check if the field can handle multiple files and get the old paths saved in the database */
    if ($this->data->get('multiple')) {
      $old = json_decode($resource[$this->data->get('column')]);
      if (!$old) $old = [];
    } else {
      $old = $resource[$this->data->get('column')];
    }

    /** Check if the field is prunable */
    if ($this->data->get('prunable')) {
      /** Get the old files not longer used and remove it from the disk */
      collect($old)->diff($field['value'])->each(function ($file) {
        Storage::disk($this->data->get('disk'))->delete($file);
      });
    }

    /** If there's not a folder name defined use the column name */
    $folder = (!$this->data->get('folder')) ? $this->data->get('column') : $this->data->get('folder');

    /** Check if the folder exists, if not create it */
    if (!Storage::disk($this->data->get('disk'))->exists($folder)) Storage::disk($this->data->get('disk'))->makeDirectory($folder);

    /** Get the array with the files paths */
    $paths = collect($field['value'])->intersect($old);

    /** Get the file keys as "file-1", "file-2", "file-X" because that are the new files */
    $column = $this->data->get('column');
    $filesKeys = collect(request()->files->keys())->filter(function ($value) use ($column) {
      $explode = explode('-', $value);
      array_pop($explode);
      return implode('-', $explode) === $column;
    });

    /** Iterate the file keys getted above */
    foreach ($filesKeys as $fileKey) {
      /** Check if the file should maintain the original name */
      if ($this->data->get('originalName')) {
        /** Save the file with the original name and get the path */
        $path = request()->file($fileKey)->storeAs($folder, request()->file($fileKey)->getClientOriginalName(), $this->data->get('disk'));
      } else {
        /** Save the file with a new name and get the path */
        $path = request()->file($fileKey)->store($folder, $this->data->get('disk'));
      }
      /** Add the new path to the paths collection */
      $paths->push($path);
    }

    /** Get all the paths values as an array */
    $paths = $paths->values();

    /** Save the path in the resource column, if the field handle multiple files save the paths as an encoded json */
    $resource->{$this->data->get('column')} = ($this->data->get('multiple')) ? json_encode($paths->toArray()) : $paths->first();
  }
}
