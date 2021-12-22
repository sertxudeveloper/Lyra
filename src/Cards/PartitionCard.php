<?php

namespace SertxuDeveloper\Lyra\Cards;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

abstract class PartitionCard extends Card {

  /**
   * The card component
   *
   * @var string
   */
  public string $component = 'card-partition';

  /**
   * Precision for the rounding method
   *
   * @var int
   */
  public int $precision = 0;

  /**
   * Limit the quantity of results
   *
   * @var int
   */
  public int $limit = 6;

  /**
   * Show the quantity instead of the percentage in the items list
   *
   * @var bool
   */
  public bool $showQuantity = false;

  /**
   * Calculate the value in the specified range
   * Supported 'count', 'min', 'max', 'avg' and 'sum' methods
   *
   * @param Request $request
   * @return array[]
   */
  abstract public function calculate(Request $request): array;

  /**
   * Get the available colors
   *
   * @return string[]
   */
  public function colors(): array {
    return [
      "#F87171",
      "#FBBF24",
      "#34D399",
      "#60A5FA",
      "#A78BFA",
      "#F472B6",
    ];
  }

  /**
   * Count instances of the model
   *
   * @param Request $request
   * @param string|Builder $model
   * @param string $groupBy
   * @param string|null $column
   * @return array[]
   */
  public function count(Request $request, Builder|string $model, string $groupBy, ?string $column = null): array {
    return $this->query($request, $model, 'count', $column, $groupBy);
  }

  /**
   * Get the maximum value of the specified column
   *
   * @param Request $request
   * @param string|Builder $model
   * @param string $column
   * @param string $groupBy
   * @return array[]
   */
  public function max(Request $request, Builder|string $model, string $column, string $groupBy): array {
    return $this->query($request, $model, 'max', $column, $groupBy);
  }

  /**
   * Get the minimum value of the specified column
   *
   * @param Request $request
   * @param string|Builder $model
   * @param string $column
   * @param string $groupBy
   * @return array[]
   */
  public function min(Request $request, Builder|string $model, string $column, string $groupBy): array {
    return $this->query($request, $model, 'min', $column, $groupBy);
  }

  /**
   * Return the result of the query
   *
   * @param Request $request
   * @param string|Builder $model
   * @param string $method
   * @param string|null $column
   * @param string $groupBy
   * @return array[]
   */
  public function query(Request $request, Builder|string $model, string $method, ?string $column, string $groupBy): array {
    $query = $model instanceof Builder ? $model : $model::query();
    $column = $column ?? $query->getModel()->getQualifiedKeyName();

    if (!collect($query->getQuery()->groups)->contains('label')) {
      $query = $query->addSelect(DB::raw("$groupBy as label"))->groupBy($groupBy);
    }

    $values = $query
      ->addSelect(DB::raw("$method($column) as value"))
      ->orderByDesc('value')
      ->get();

    return $values->toArray();
  }

  /**
   * Get the aggregate value of the specified column
   *
   * @param Request $request
   * @param string|Builder $model
   * @param string $column
   * @param string $groupBy
   * @return array[]
   */
  public function sum(Request $request, Builder|string $model, string $column, string $groupBy): array {
    return $this->query($request, $model, 'sum', $column, $groupBy);
  }

  /**
   * Transform the card into a JSON array.
   *
   * @param Request $request
   * @return array
   */
  public function toArray(Request $request): array {
    $values = collect($this->calculate($request));
    $total = $values->sum('value');

    /** Limit the quantity of results */
    if ($values->count() > $this->limit) {
      $values = $values->take($this->limit);
      $totalLimited = $values->sum('value');
    } else {
      $totalLimited = $total;
    }

    $values = $values->mapWithKeys(function ($item, $key) use ($totalLimited) {
      $item['color'] = $this->colors()[$key];
      $item['value'] = (float)$item['value'];
      $item['percent'] = round($item['value'] / $totalLimited * 100, 3);

      return [$key => $item];
    });

    return [
      'component' => $this->component,
      'label' => $this->label(),
      'slug' => $this->slug(),
      'showQuantity' => $this->showQuantity,
      'total' => $total,
      'values' => $values,
    ];
  }
}
