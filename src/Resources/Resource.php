<?php

namespace SertxuDeveloper\Lyra\Resources;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\DelegatesToResource;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use SertxuDeveloper\Lyra\Fields\Panel;
use SertxuDeveloper\Lyra\Lyra;

abstract class Resource
{
    use DelegatesToResource;

    /** @var Model */
    public static string $model;

    public static string $icon = '';

    public static int $priority = 99;

    public static array $perPageOptions = [25, 50, 100];

    public static string $orderBy = 'desc'; // 'desc' or 'asc'

    /**
     * The relationships that should be eager loaded on index queries.
     */
    public static array $with = [];

    /**
     * Columns where the search is enabled
     *
     * @var string[]
     */
    public static array $search = [];

    /**
     * The resource instance.
     *
     * @var mixed
     */
    public $resource;

    public string $description = '';

    /**
     * Create a new resource instance.
     *
     * @return void
     */
    public function __construct(Model $resource) {
        $this->resource = $resource;
    }

    /**
     * Get related model primary key name
     */
    public static function getKeyName(): string {
        return (new static::$model)->getKeyName();
    }

    /**
     * Get the label of the resource
     */
    public static function label(): string {
        return Str::title(Str::snake(class_basename(get_called_class()), ' '));
    }

    /**
     * Create a new resource instance.
     *
     * @param  mixed  ...$parameters
     * @return $this
     */
    public static function make(...$parameters): self {
        return new static(...$parameters);
    }

    /**
     * Create a new instance of the provided model
     */
    public static function newModel(): Model {
        $model = static::$model;

        return new $model;
    }

    public static function searchInResource(Request $request, Builder $query): Builder {
        $searchTerm = $request->input('q');

        $query->where(function (Builder $query) use ($searchTerm) {
            foreach (static::$search as $column) {
                $query->orWhere($column, 'LIKE', "%{$searchTerm}%");
            }
        });

        return $query;
    }

    /**
     * Get the singular label of the resource
     */
    public static function singular(): string {
        return Str::singular(static::label());
    }

    /**
     * Get the slug of the resource
     */
    public static function slug(): string {
        return Str::kebab(class_basename(get_called_class()));
    }

    /**
     * Add the requested sorting method to the query
     */
    public static function sortResource(Request $request, Builder $query): Builder {
        $sortBy = explode(',', $request->query('sortBy'));
        $sortOrder = explode(',', $request->query('sortOrder'));

        /** Do not apply the sorting due to invalid params */
        if (count($sortBy) !== count($sortOrder)) {
            return $query;
        }

        $sort = collect($sortBy)->combine($sortOrder)->toArray();

        foreach ($sort as $column => $direction) {
            if ($direction !== 'asc' && $direction !== 'desc') {
                continue;
            }
            $query->orderBy($column, $direction);
        }

        return $query;
    }

    /**
     * The actions' resource definition
     */
    abstract public function actions(): array;

    /**
     * The cards' resource definition
     */
    abstract public function cards(): array;

    /**
     * The fields' resource definition
     */
    abstract public function fields(): array;

    /**
     * Transform the resource into an array for the table header.
     */
    public function getHeader($request): array {
        $allFields = [];

        // Get the fields that are not in a panel
        $fieldsWithoutPanels = array_filter($this->fields(), fn ($field) => !$field instanceof Panel);

        $allFields = array_merge($allFields, $fieldsWithoutPanels);

        $fields = [];
        foreach ($allFields as $field) {
            if (!$field->canShow($request)) {
                continue;
            }

            $fields[] = $field->toTableHeader($request);
        }

        return $fields;
    }

    /**
     * Get the JSON serialization options that should be applied to the resource response.
     */
    public function jsonOptions(): int {
        return 0;
    }

    public function serializeForIndex(Request $request): array {
        return [
            'key' => $this->resource->getKey(),
            'trashed' => $this->isTrashed(),
            'fields' => $this->getFields($request)->map->toArray($request, $this->resource),
        ];
    }

    public function serializeForCreate(Request $request): array {
        return [
            'key' => $this->resource->getKey(),
            'trashed' => $this->isTrashed(),
            'panels' => $this->getPanels($request),
        ];
    }

    public function serializeForEdit(Request $request): array {
        return [
            'key' => $this->resource->getKey(),
            'trashed' => $this->isTrashed(),
            'panels' => $this->getPanels($request),
        ];
    }

    public function serializeForShow(Request $request): array {
        return [
            'key' => $this->resource->getKey(),
            'trashed' => $this->isTrashed(),
            'panels' => $this->getPanels($request),
        ];
    }

    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array {
        dd('Resource toArray');
        $panels = $this->getPanels($request);

        foreach ($panels as $panel) {
            $panelFields = [];
            foreach ($panel->fields as $field) {
                if (!$field->canShow($request)) {
                    continue;
                }

                $panelFields[] = $field->toArray($this->resource);
            }

            $panel->fields = $panelFields;
        }

        $response = [
            'key' => $this->resource->getKey(),
            'trashed' => (method_exists($this->resource, 'trashed')) ? $this->resource->trashed() : false,
            'panels' => $panels,
        ];

        $route = Lyra::getRouteName($request);
        if ($route === 'resources.edit' || $route === 'resources.update') {
            $updated_at = (new $this->resource)->getUpdatedAtColumn();
            $response['updated_at'] = $this->resource->$updated_at;
        }

        return $response;
    }

    /**
     * Validate the update request received.
     *
     *
     * @throws ValidationException
     */
    public function validateCreation(Request $request): array {
        $validator = Validator::make($request->all(), $this->formatRules($request));

        return $validator->validate();
    }

    /**
     * Validate the update request received.
     *
     *
     * @throws ValidationException
     */
    public function validateUpdate(Request $request): array {
        $validator = Validator::make($request->all(), $this->formatRules($request));

        return $validator->validate();
    }

    /**
     * Format the rules for the validation
     */
    protected function formatRules(Request $request): array {
        $rules = [];
        $isUpdating = Lyra::getRouteName($request) === 'resources.update';

        foreach ($this->getFields($request) as $field) {
            $fieldRules = $isUpdating ? $field->updateRules : $field->creationRules;
            $rules[$field->column] = str_replace('{{resourceId}}', $this->resource->getKey(), $fieldRules);
        }

        return $rules;
    }

    protected function getFields(Request $request) {
        return collect($this->fields())
            ->map(fn ($field) => !$field instanceof Panel ? $field : $field->fields())
            ->flatten()
            ->filter(fn ($field) => $field->canShow($request))
            ->values();
    }

    /**
     * @return array
     */
    protected function getPanels(Request $request) {
        $panels = collect();
        $fields = collect($this->fields());

        $fieldsWithoutPanels = $fields
            ->filter(fn ($field) => !$field instanceof Panel)
            ->values();

        // Get the fields that are not in a panel and add them to a new panel
        if (!$fieldsWithoutPanels->isEmpty()) {
            $title = match (Lyra::getRouteName($request)) {
                'resources.create' => 'Create '.static::singular(),
                'resources.edit' => 'Edit '.static::singular(),
                default => static::singular().' details',
            };

            $panel = Panel::make($title, $fieldsWithoutPanels->toArray())
                ->description('This is the basic information of the resource.');

            $panels->push($panel);
        }

        // Get the fields that are in a panel and add them to the panels array
        $panels = $panels->merge($fields->filter(fn ($field) => $field instanceof Panel));

        return $panels->each(fn ($panel) => $panel->fields = collect($panel->fields)
            ->filter(fn ($field) => $field->canShow($request))->values()->toArray()
        )->values();
    }

    protected function isTrashed(): bool {
        return (method_exists($this->resource, 'trashed')) ? $this->resource->trashed() : false;
    }
}
