<?php

namespace SertxuDeveloper\Lyra\Fields;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use SertxuDeveloper\Lyra\Fields\Traits\Placeholder;

class Password extends Field
{
    use Placeholder;

    public string $component = 'field-password';

    public bool $showOnIndex = false;

    /**
     * Create a new instance of the field
     *
     * @param  string  $name
     * @param  object|string|null  $column
     * @return $this
     */
    public static function make(string $name, object|string $column = null): Field {
        $field = parent::make($name, $column);

        $field->placeholder(Str::repeat('*', 12));

        return $field;
    }

    /**
     * Add field-specific data to the response
     *
     * @return array
     */
    public function additional(): array {
        return [

        ];
    }

    /**
     * Update the field value using the given data.
     *
     * @param  Model  $model The model to be updated
     * @param  array  $data The new validated data
     * @return void
     */
    public function save(Model $model, array $data): void {
        if ($data[$this->getKey()]) {
            $model->{$this->getKey()} = $data[$this->getKey()];
        }
    }


    /**
     * Get the field value.
     *
     * @param  Model  $model The model to be displayed
     * @return mixed
     */
    public function get(Model $model): mixed {
        return null;
    }
}
