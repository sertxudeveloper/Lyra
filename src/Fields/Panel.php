<?php

namespace SertxuDeveloper\Lyra\Fields;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Panel
{
    public string $component = 'field-panel';

    public string $title = '';

    public string $description = '';

    public array $fields = [];

    /**
     * Create a new instance of the field
     *
     * @return $this
     */
    public static function make(string $title, array $fields): self {
        $field = new static;
        $field->title = $title;
        $field->fields = $fields;

        return $field;
    }

    /**
     * Set the description of the panel
     *
     * @return $this
     */
    public function description(string $description): self {
        $this->description = $description;

        return $this;
    }

    /**
     * Get the key of the field based on it's name
     */
    public function getKey(): string {
        return Str::snake($this->title);
    }

    /**
     * Transform the field into an array.
     */
    public function toArray(Model $model): array {
        return [
            'key' => $this->getKey(),
            'component' => $this->component,
            'title' => $this->title,
            'description' => $this->description,
            //            'fields' => collect($this->fields)->map(fn($field) => $field->toArray($model)),
        ];
    }

    public function fields(): array {
        return $this->fields;
    }
}
