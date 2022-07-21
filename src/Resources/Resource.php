<?php

namespace SertxuDeveloper\Lyra\Resources;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\DelegatesToResource;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
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
     *
     * @var array
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
     * @param  Model  $resource
     * @return void
     */
    public function __construct(Model $resource) {
        $this->resource = $resource;
    }

    /**
     * Get related model primary key name
     *
     * @return string
     */
    public static function getKeyName(): string {
        return (new static::$model)->getKeyName();
    }

    /**
     * Get the label of the resource
     *
     * @return string
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
     *
     * @return Model
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
     *
     * @return string
     */
    public static function singular(): string {
        return Str::singular(static::label());
    }

    /**
     * Get the slug of the resource
     *
     * @return string
     */
    public static function slug(): string {
        return Str::kebab(class_basename(get_called_class()));
    }

    /**
     * Add the requested sorting method to the query
     *
     * @param  Request  $request
     * @param  Builder  $query
     * @return Builder
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
     *
     * @return array
     */
    abstract public function actions(): array;

    /**
     * The cards' resource definition
     *
     * @return array
     */
    abstract public function cards(): array;

    /**
     * The fields' resource definition
     *
     * @return array
     */
    abstract public function fields(): array;

    /**
     * Transform the resource into an array for the table header.
     *
     * @param $request
     * @return array
     */
    public function getHeader($request): array {
        $fields = [];
        foreach ($this->fields() as $field) {
            if (!$field->canShow($request)) {
                continue;
            }
            $fields[] = $field->toTableHeader($request);
        }

        return $fields;
    }

    /**
     * Get the JSON serialization options that should be applied to the resource response.
     *
     * @return int
     */
    public function jsonOptions(): int {
        return 0;
    }

    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray(Request $request): array {
        $fields = [];
        foreach ($this->fields() as $field) {
            if (!$field->canShow($request)) {
                continue;
            }
            $fields[] = $field->toArray($this->resource);
        }

        $response = [
            'key' => $this->resource->getKey(),
            'trashed' => (method_exists($this->resource, 'trashed')) ? $this->resource->trashed() : false,
            'fields' => $fields,
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
     * @param  Request  $request
     * @return array
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
     * @param  Request  $request
     * @return array
     *
     * @throws ValidationException
     */
    public function validateUpdate(Request $request): array {
        $validator = Validator::make($request->all(), $this->formatRules($request));

        return $validator->validate();
    }

    /**
     * Format the rules for the validation
     *
     * @param  Request  $request
     * @return array
     */
    protected function formatRules(Request $request): array {
        $rules = [];
        $isUpdating = Lyra::getRouteName($request) == 'resources.update';

        foreach ($this->fields() as $field) {
            if (!$field->canShow($request)) {
                continue;
            }
            $fieldRules = $isUpdating ? $field->updateRules : $field->creationRules;
            $rules[$field->column] = str_replace('{{resourceId}}', $this->resource->getKey(), $fieldRules);
        }

        return $rules;
    }
}
