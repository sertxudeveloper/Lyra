<?php

namespace SertxuDeveloper\Lyra\Fields;

use Closure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use SertxuDeveloper\Lyra\Fields\Concerns\InteractsWithProperties;
use SertxuDeveloper\Lyra\Fields\Concerns\Sortable;
use SertxuDeveloper\Lyra\Fields\Concerns\ValidationRules;
use SertxuDeveloper\Lyra\Lyra;

abstract class Field
{
    use InteractsWithProperties, ValidationRules;

    /**
     * The field's displayable name.
     *
     * @var string
     */
    public string $name = '';

    /**
     * Whether the field should be displayed in the index view.
     *
     * @var bool
     */
    public bool $showOnIndex = true;

    /**
     * Whether the field should be displayed in the show view.
     *
     * @var bool
     */
    public bool $showOnShow = true;

    /**
     * Whether the field should be shown on the create form.
     *
     * @var bool
     */
    public bool $showOnCreate = true;

    /**
     * Whether the field should be shown on the edit form.
     *
     * @var bool
     */
    public bool $showOnUpdate = true;

    /**
     * The field component to be used.
     *
     * @var string
     */
    protected string $component = '';

    /**
     * The model attribute or a closure to be used to get the field value.
     *
     * @var object|string
     */
    protected object|string $column = '';

    /**
     * Create a new instance of the field.
     *
     * @param  string  $name
     * @param  object|string|null  $column
     * @return $this
     */
    public static function make(string $name, object|string $column = null): self {
        $field = new static;
        $field->name = $name;
        $field->column = $column ?? Str::snake(Str::lower($name));

        if (is_callable($field->column)) {
            $field->showOnCreate = false;
            $field->showOnUpdate = false;
        }

        return $field;
    }

    /**
     * Check if the field can be displayed in the current view.
     *
     * @param  Request  $request
     * @return bool
     */
    public function canShow(Request $request): bool {
        return match (Lyra::getRouteName($request)) {
            'resources.index' => $this->showOnIndex,
            'resources.create', 'resources.store' => $this->showOnCreate,
            'resources.show' => $this->showOnShow,
            'resources.edit', 'resources.update' => $this->showOnUpdate,
            default => false,
        };
    }

    /**
     * Get the field's value.
     *
     * @param  Model  $model  The model to be displayed
     * @return mixed
     */
    public function getValue(Model $model): mixed {
        return is_callable($this->column) ? Closure::bind($this->column, $model)() : $model->{$this->column};
    }

    /**
     * Get the field's key.
     *
     * @return string
     */
    public function getKey(): string {
        return is_string($this->column) ? $this->column : Str::snake($this->name);
    }

    /**
     * Hide the field on form views.
     *
     * @return $this
     */
    public function hideOnForms(): self {
        $this->showOnCreate = false;
        $this->showOnUpdate = false;

        return $this;
    }

    /**
     * Hide the field on the index view.
     *
     * @return $this
     */
    public function hideOnIndex(): self {
        $this->showOnIndex = false;

        return $this;
    }

    /**
     * Hide the field on the index view.
     *
     * @return $this
     */
    public function hideOnShow(): self {
        $this->showOnShow = false;

        return $this;
    }

    /**
     * Set the rules for creation and update.
     *
     * @param  string[]  $rules
     * @return $this
     */
    public function rules(string ...$rules): self {
        $this->creationRules = $rules;
        $this->updateRules = $rules;

        return $this;
    }

    /**
     * Update the field value using the given data.
     *
     * @param  Model  $model  The model to be updated
     * @param  array  $data  The new validated data
     * @return void
     */
    public function save(Model $model, array $data): void {
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
    public function toArray(Model $model): array {
        $field = [
            'key' => $this->getKey(),
            'value' => $this->getValue($model),
        ];

        return array_merge($field, $this->getProperties());
    }

    /**
     * Transform the resource into an array for the table header.
     *
     * @param  Request  $request
     * @return array
     */
    public function toTableHeader(Request $request): array {
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
}
