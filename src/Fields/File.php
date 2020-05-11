<?php

namespace SertxuDeveloper\Lyra\Fields;

use Illuminate\Support\Facades\Storage;

class File extends Field {

  protected $component = "file-field";
  protected $defaultDisk = 'public';
  protected $hideOnIndex = true;

  /**
   * Enable multiple file upload
   * @return $this
   */
  public function multiple() {
    $this->data->put('multiple', true);
    return $this;
  }

  /**
   * Set the disk to store
   *
   * @param $disk
   * @return $this
   */
  public function disk($disk) {
    $this->data->put('disk', $disk);
    return $this;
  }

  /**
   * Remove the previous file when replaced with a new one
   *
   * @return $this
   */
  public function prunable() {
    $this->data->put('prunable', true);
    return $this;
  }

  /**
   * Set the folder to save the files
   *
   * @param $folder
   * @return $this
   */
  public function folder($folder) {
    $this->data->put('folder', $folder);
    return $this;
  }

  /**
   * Save the file with its original name
   *
   * @return $this
   */
  public function originalName() {
    $this->data->put('originalName', true);
    return $this;
  }

  /**
   * Delete the file
   * The file will be deleted only if the prunable option is enabled
   *
   * @param $model
   */
  public function delete($model) {
    if ($this->data->has('prunable')) {
      $file = $model[$this->data->get('column')];
      if (!$this->data->get('disk')) $this->data->put('disk', $this->defaultDisk);
      Storage::disk($this->data->get('disk'))->delete($file);
    }
  }

  /**
   * Get the value of the Field
   *
   * @param $model
   * @param string $type Can be 'index', 'edit', 'show' or 'create'
   * @return array
   */
  public function getValue($model, string $type): array {
    if (!$this->data->has('disk')) $this->data->put('disk', $this->defaultDisk);
    $this->data->put('storage_path', Storage::disk($this->data->get('disk'))->url(null));

    return parent::getValue($model, $type);
  }

  /**
   * Save the $field value in the model
   *
   * @param array $field
   * @param $model
   */
  public function saveValue(array $field, $model): void {
    /** Check if there is a disk defined, if not set the default disk */
    if (!$this->data->has('disk')) $this->data->put('disk', $this->defaultDisk);

    /** Check if the field can handle multiple files and get the old paths saved in the database */
    if ($this->data->has('multiple')) {
      $old = $model[$this->data->get('column')];
      if (!$old) $old = [];
    } else {
      $old = $model[$this->data->get('column')];
    }

    /** Check if the field is prunable */
    if ($this->data->has('prunable')) {
      /** Get the old files not longer used and remove it from the disk */
      collect($old)->diff($field['value'])->each(function ($file) {
        Storage::disk($this->data->get('disk'))->delete($file);
      });
    }

    /** If there's not a folder name defined use the column name */
    $folder = (!$this->data->has('folder')) ? $this->data->get('column') : $this->data->get('folder');

    /** Check if the folder exists, if not create it */
    if (!Storage::disk($this->data->get('disk'))->exists($folder)) {
      Storage::disk($this->data->get('disk'))->makeDirectory($folder);
    }

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
      if ($this->data->has('originalName')) {
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
    $model[$this->data->get('column')] = ($this->data->has('multiple')) ? $paths->toArray() : $paths->first();
  }
}
