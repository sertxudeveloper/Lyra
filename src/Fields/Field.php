<?php

namespace SertxuDeveloper\Lyra\Fields;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use SertxuDeveloper\Lyra\Facades\Lyra;
use SertxuDeveloper\Lyra\Fields\Traits\Align;
use SertxuDeveloper\Lyra\Fields\Traits\Placeholder;
use SertxuDeveloper\Lyra\Fields\Traits\Sortable;

abstract class Field
{
    use Align;

    public string $component = '';

    public string $name = '';

    public object|string $column = '';

    public array $creationRules = [];

    public array $updateRules = [];

    public bool $showOnIndex = true;

    public bool $showOnShow = true;

    public bool $showOnCreate = true;

    public bool $showOnUpdate = true;

    /**
     * Create a new instance of the field
     *
     * @param  string  $name
     * @param  object|string|null  $column
     * @return $this
     */
    public static function make(string $name, object|string $column = null): self
    {
        $field = new static();
        $field->name = $name;
        $field->column = $column ?? Str::snake(Str::lower($name));

        if (is_callable($field->column)) {
            $field->showOnCreate = false;
            $field->showOnUpdate = false;
        }

        return $field;
    }

    /**
     * Add field-specific data to the response
     *
     * @param  Model  $model
     * @return array
     */
    public function additional(Model $model): array
    {
        return [];
    }

    /**
     * Check if the field can be displayed in the current view
     *
     * @param  Request  $request
     * @return bool
     */
    public function canShow(Request $request): bool
    {
        return match (Lyra::getRouteName($request)) {
            'resources.index' => $this->showOnIndex,
            'resources.create', 'resources.store' => $this->showOnCreate,
            'resources.show' => $this->showOnShow,
            'resources.edit', 'resources.update' => $this->showOnUpdate,
            default => false,
        };
    }

    /**
     * Set the rules for the creation
     *
     * @param  string[]  $rules
     * @return $this
     */
    public function creationRules(string ...$rules): self
    {
        $this->creationRules = $rules;

        return $this;
    }

    /**
     * Get the key of the field based on it's name
     *
     * @return string
     */
    public function getKey(): string
    {
        return is_string($this->column) ? $this->column : Str::snake($this->name);
    }

    /**
     * Hide the field on form views
     *
     * @return $this
     */
    public function hideOnForms(): self
    {
        $this->showOnCreate = false;
        $this->showOnUpdate = false;

        return $this;
    }

    /**
     * Hide the field on the index view
     *
     * @return $this
     */
    public function hideOnIndex(): self
    {
        $this->showOnIndex = false;

        return $this;
    }

    /**
     * Hide the field on the index view
     *
     * @return $this
     */
    public function hideOnShow(): self
    {
        $this->showOnShow = false;

        return $this;
    }

    /**
     * Set the rules for creation and update
     *
     * @param  string[]  $rules
     * @return $this
     */
    public function rules(string ...$rules): self
    {
        $this->creationRules = $rules;
        $this->updateRules = $rules;

        return $this;
    }

    /**
     * Update the field value using the given data
     *
     * @param  Model  $model The model to be updated
     * @param  array  $data The new validated data
     * @return void
     */
    public function save(Model $model, array $data): void
    {
        if (is_callable($this->column)) {
            return;
        }

        $model->{$this->getKey()} = $data[$this->getKey()];
    }

    /**
     * Transform the field into an array.
     *
     * @param  Model  $model
     * @return array
     */
    public function toArray(Model $model): array
    {
        $field = [
            'key' => $this->getKey(),
            'component' => $this->component,
            'name' => $this->name,
            'value' => is_callable($this->column) ? call_user_func($this->column, $model) : $model->{$this->column},
        ];

        /** @see Placeholder */
        if (isset($this->placeholder)) {
            $field['placeholder'] = $this->placeholder;
        }

        /** @see Align */
        if (isset($this->align)) {
            $field['align'] = $this->align;
        }

        return array_merge($field, $this->additional($model));
    }

    /**
     * Transform the resource into an array for the table header.
     *
     * @param  Request  $request
     * @return array
     */
    public function toTableHeader(Request $request): array
    {
        $sortBy = explode(',', $request->query('sortBy'));
        $sortOrder = explode(',', $request->query('sortOrder'));

        $sortIndex = array_search($this->getKey(), $sortBy);
        if ($sortIndex !== false) {
            $order = $sortOrder[$sortIndex];
        }

        return [
            'key' => $this->getKey(),
            'name' => $this->name,

            /** @see Sortable */
            'sortable' => $this->sortable ?? false,

            /** @see Aling */
            'align' => $this->align ?? 'left',

            'order' => $order ?? null,
        ];
    }

    /**
     * Set the rules for the update
     *
     * @param  string[]  $rules
     * @return $this
     */
    public function updateRules(string ...$rules): self
    {
        $this->updateRules = $rules;

        return $this;
    }
}
