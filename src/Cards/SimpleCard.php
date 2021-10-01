<?php

namespace SertxuDeveloper\Lyra\Cards;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

abstract class SimpleCard extends Card {

  /**
   * The card component
   *
   * @var string
   */
  public string $component = 'card-simple';

  /**
   * Precision for the rounding method
   *
   * @var int
   */
  public int $precision = 0;

  /**
   * Get the average value of the specified column
   *
   * @param Request $request
   * @param Builder|string $model
   * @param string $column
   * @param string|null $dateColumn
   * @return float
   */
  public function avg(Request $request, $model, string $column, ?string $dateColumn = null): float {
    return $this->query($request, $model, 'avg', $column, $dateColumn);
  }

  /**
   * Calculate the value in the specified range
   * Supported 'count', 'min', 'max', 'avg' and 'sum' methods
   *
   * @param Request $request
   * @return float
   */
  abstract public function calculate(Request $request): float;

  /**
   * Count instances of the model
   *
   * @param Request $request
   * @param Builder|string $model
   * @param string|null $column
   * @param string|null $dateColumn
   * @return float
   */
  public function count(Request $request, $model, ?string $column = null, ?string $dateColumn = null): float {
    return $this->query($request, $model, 'count', $column, $dateColumn);
  }

  /**
   * Generate current range from selected interval
   *
   * @param Request $request
   * @return array
   */
  public function currentRange(Request $request): array {
    $range = $request->input('range') ?? $this->defaultRange();
    return [now()->sub($range), now()];
  }

  /**
   * Get the key of the default range
   *
   * @return string
   */
  public function defaultRange(): string {
    return '3 months';
  }

  /**
   * Get the maximum value of the specified column
   *
   * @param Request $request
   * @param Builder|string $model
   * @param string $column
   * @param string|null $dateColumn
   * @return float
   */
  public function max(Request $request, $model, string $column, ?string $dateColumn = null): float {
    return $this->query($request, $model, 'max', $column, $dateColumn);
  }

  /**
   * Get the minimum value of the specified column
   *
   * @param Request $request
   * @param Builder|string $model
   * @param string $column
   * @param string|null $dateColumn
   * @return float
   */
  public function min(Request $request, $model, string $column, ?string $dateColumn = null): float {
    return $this->query($request, $model, 'min', $column, $dateColumn);
  }

  /**
   * Return the result of the query
   *
   * @param Request $request
   * @param Builder|string $model
   * @param string $method
   * @param string|null $column
   * @param string|null $dateColumn
   * @return float
   */
  public function query(Request $request, $model, string $method, ?string $column = null, ?string $dateColumn = null): float {
    $query = $model instanceof Builder ? $model : $model::query();
    $column = $column ?? $query->getModel()->getQualifiedKeyName();
    $createdAt = $query->getModel()->getCreatedAtColumn();

    return round($query
      ->whereBetween($dateColumn ?? $createdAt, $this->currentRange($request))
      ->{$method}($column), $this->precision);
  }

  /**
   * Get the available ranges for the card
   *
   * @return string[]
   */
  public function ranges(): array {
    return [
      '1 month' => '1 month',
      '3 months' => '3 months',
      '6 months' => '6 months',
      '1 year' => '1 year',
    ];
  }

  /**
   * Get the aggregate value of the specified column
   *
   * @param Request $request
   * @param Builder|string $model
   * @param string $column
   * @param string|null $dateColumn
   * @return float
   */
  public function sum(Request $request, $model, string $column, ?string $dateColumn = null): float {
    return $this->query($request, $model, 'sum', $column, $dateColumn);
  }

  /**
   * Transform the card into a JSON array.
   *
   * @param Request $request
   * @return array
   */
  public function toArray(Request $request): array {
    $value = $this->calculate($request) ?? 0;

    return [
      'component' => $this->component,
      'label' => $this->label(),
      'slug' => $this->slug(),
      'ranges' => $this->ranges(),
      'range' => $request->input('range') ?: $this->defaultRange(),
      'value' => $value,
    ];
  }
}
