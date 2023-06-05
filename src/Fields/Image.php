<?php

namespace SertxuDeveloper\Lyra\Fields;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Image extends Field
{
    public string $component = 'field-image';

    public ?string $folder = null;

    public ?string $disk = null;

    public bool $prunable = false;

    public bool $multiple = false;

    public bool $keepOriginalName = false;

    public string $accept = 'image/*';

    /**
     * Create a new instance of the field
     *
     * @param  string  $name
     * @param  object|string|null  $column
     * @return $this
     */
    public static function make(string $name, object|string $column = null): Field {
        $field = parent::make($name, $column);

        $field->folder = $field->getKey();
        $field->disk = config('filesystems.default');

        return $field;
    }

    /**
     * Set the accept attribute for the input
     *
     * @param  string  $accept
     * @return $this
     */
    public function accept(string $accept): self {
        $this->accept = $accept;

        return $this;
    }

    /**
     * Add field-specific data to the response
     *
     * @return array
     */
    public function additional(): array {
//        $value = is_callable($this->column) ? call_user_func($this->column, $model) : $model->{$this->column};
//        $value = collect($value)->map(fn ($item) => Storage::disk($this->disk)->url($this->folder.'/'.$item));

        return [
            //            'value' => [],
            //            'multiple' => $this->multiple,
            //            'files' => $value,
            //            'accept' => $this->accept,
        ];
    }

    /**
     * Set the disk where the images will be stored
     *
     * @param  string  $disk
     * @return $this
     */
    public function disk(string $disk): self {
        $this->disk = $disk;

        return $this;
    }

    /**
     * Set the folder where the images will be stored
     *
     * @param  string  $folder
     * @return $this
     */
    public function folder(string $folder): self {
        $this->folder = $folder;

        return $this;
    }

    /**
     * Keep original name
     *
     * @return $this
     */
    public function keepOriginalName(): self {
        $this->keepOriginalName = true;

        return $this;
    }

    /**
     * Allow multiple images
     *
     * @return $this
     */
    public function multiple(): self {
        $this->multiple = true;

        return $this;
    }

    /**
     * Allow removing the images from the disk when new ones are uploaded
     *
     * @return $this
     */
    public function prunable(): self {
        $this->prunable = true;

        return $this;
    }

    /**
     * Save the images to the disk and update the model
     *
     * @param  Model  $model
     * @param  array  $data
     * @return void
     */
    public function save(Model $model, array $data): void {
        $files = collect($data[$this->getKey()]);
        if ($files->isEmpty()) {
            return;
        }

        /**
         * If the field is prunable, delete the old images
         */
        if ($this->prunable) {
            $oldFiles = collect($model->{$this->getKey()});
            $oldFiles->each(fn ($file) => Storage::disk($this->disk)->delete($this->folder.'/'.$file));
        }

        /**
         * Store the new images
         */
        foreach ($files as $file) {
            $name = $this->keepOriginalName ? $file->getClientOriginalName() : $file->hashName();
            $file->storeAs($this->folder, $name, $this->disk);
        }

        /**
         * Save the image names to the model
         */
        $model->{$this->getKey()} = $this->multiple ? $files->map(function ($file) {
            return $this->keepOriginalName ? $file->getClientOriginalName() : $file->hashName();
        })->toArray() : $files->first()->hashName();
    }
}
