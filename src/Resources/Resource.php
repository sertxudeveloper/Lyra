<?php

namespace SertxuDeveloper\Lyra\Resources;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\DelegatesToResource;
use Illuminate\Support\Str;
use SertxuDeveloper\Lyra\Facades\Lyra;

/**
 * Resource class.
 *
 * @package SertxuDeveloper\Lyra
 */
abstract class Resource {

    use DelegatesToResource;

    /**
     * The model related to the resource.
     *
     * @var class-string<Model>
     */
    static public string $model;
    static public string $icon = '';
    static public int $priority = 99;
    static public array $perPageOptions = [25, 50, 100];
static public string $orderBy = 'desc';
        /**
     * The relationships that should be eager loaded on index queries.
     *
     * @var array
     */
    static public array $with = []; // 'desc' or 'asc'
    /**
     * Columns where the search is enabled.
     *
     * @var string[] $search
     */
    static public array $search = [];
    /**
     * The resource instance.
     *
     * @var mixed
     */
    protected $resource;

    /**
     * Create a new resource instance.
     *
     * @param Model $resource
     * @return void
     */
    public function __construct(Model $resource) {
        $this->resource = $resource;
    }

    /**
     * Get related model primary key name.
     *
     * @return string
     */
    public static function getKeyName(): string {
        return static::newModel()->getKeyName();
    }

    /**
     * Get the label of the resource.
     *
     * @return string
     */
    static public function label(): string {
        return Str::title(Str::snake(class_basename(get_called_class()), ' '));
    }

    /**
     * Create a new resource instance.
     *
     * @param mixed ...$parameters
     * @return $this
     */
    static public function make(...$parameters): self {
        return new static(...$parameters);
    }

    /**
     * Create a new instance of the provided model.
     *
     * @return Model
     */
    static public function newModel(): Model {
        return new static::$model;
    }

    /**
     * Search inside the resource using the available search columns.
     *
     * @param Request $request
     * @param Builder $query
     * @return Builder
     */
    static public function searchInResource(Request $request, Builder $query): Builder {
        $searchTerm = $request->input('q');

        $query->where(function (Builder $query) use ($searchTerm) {
            foreach (static::$search as $column) {
                $query->orWhere($column, 'LIKE', "%$searchTerm%");
            }
        });

        return $query;
    }

    /**
     * Get the singular label of the resource.
     *
     * @return string
     */
    static public function singular(): string {
        return Str::singular(static::label());
    }

    /**
     * Get the slug of the resource.
     *
     * @return string
     */
    static public function slug(): string {
        return Str::kebab(class_basename(get_called_class()));
    }

    /**
     * Add the requested sorting method to the query.
     *
     * @param Request $request
     * @param Builder $query
     * @return Builder
     */
    static public function sortResource(Request $request, Builder $query): Builder {
        $sortBy = explode(',', $request->query('sortBy'));
        $sortOrder = explode(',', $request->query('sortOrder'));

        /** Do not apply the sorting due to invalid params */
        if (count($sortBy) !== count($sortOrder))
            return $query;

        $sort = collect($sortBy)->combine($sortOrder)->toArray();

        foreach ($sort as $column => $direction) {
            if ($direction !== 'asc' && $direction !== 'desc')
                continue;

            $query->orderBy($column, $direction);
        }

        return $query;
    }

    /**
     * The actions' resource definition.
     *
     * @return array
     */
    abstract public function actions(): array;

    /**
     * The cards' resource definition.
     *
     * @return array
     */
    abstract public function cards(): array;

    /**
     * The fields' resource definition.
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
            if (!$field->canShow($request)) continue;
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
     * @param Request $request
     * @return array
     */
    public function toArray(Request $request): array {
        $fields = [];
        foreach ($this->fields() as $field) {
            if (!$field->canShow($request)) continue;
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
}
