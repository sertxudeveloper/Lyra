<?php

namespace SertxuDeveloper\Lyra\Cards;

use Illuminate\Http\Request;

abstract class MetricCard extends Card {

  public string $component = 'card-metric';
  public string $column = '';
  public string $class = '';

  /**
   * Supported Eloquent 'count', 'min', 'max', 'avg' and 'sum' methods
   * @var string
   */
  public string $method = 'count';
  public int $precision = 0;

  public array $interval = [
    '1 month' => '1 month',
    '3 months' => '3 months',
    '6 months' => '6 months',
    '1 year' => '1 year',
  ];

  public string $defaultInterval = '3 months';

  /**
   * Get the column used by the selected interval
   *
   * @return string
   */
  public function column(): string {
    return $this->column ?: $this->class::CREATED_AT;
  }

  /**
   * Generate current range from selected interval
   *
   * @param $interval
   * @return array
   */
  public function currentRange($interval): array {
    return [now()->sub($interval), now()];
  }

  /**
   * Calculate the difference between the two intervals
   *
   * @param $value
   * @param $previous
   * @return float|int
   */
  public function difference($value, $previous) {
    return ($previous == 0) ? ($value * 100) : (($value - $previous) / $previous * 100);
  }

  /**
   * Return the instance count in the previous interval
   *
   * @param $interval
   * @return float
   */
  public function previous($interval): float {
    $column = (new $this->class)->getQualifiedKeyName();
    $value = $this->class::query()
      ->whereBetween($this->column(), $this->previousRange($interval))
      ->{$this->method}($column);

    return round($value, $this->precision);
  }

  /**
   * Generate previous range from selected interval
   *
   * @param $interval
   * @return array
   */
  public function previousRange($interval): array {
    return [now()->sub($interval)->sub($interval), now()->sub($interval)];
  }

  /**
   * Transform the card into a JSON array.
   *
   * @param Request $request
   * @return array
   */
  public function toArray(Request $request): array {
    $selected = $request->input('interval') ?? $this->defaultInterval;
    $value = $this->value($selected) ?? 0;
    $previous = $this->previous($selected) ?? 0;
    $difference = $this->difference($value, $previous);
    $difference = $difference > 0 ? "+$difference%" : "$difference%";

    return [
      'component' => $this->component,
      'label' => $this->label(),
      'slug' => $this->slug(),
      'interval' => $this->interval,
      'selected' => $selected,
      'value' => $value,
      'difference' => $difference,
    ];
  }

  /**
   * Return the instance count in the current interval
   *
   * @param $interval
   * @return float
   */
  public function value($interval): float {
    $column = (new $this->class)->getQualifiedKeyName();
    $value = $this->class::query()
      ->whereBetween($this->column(), $this->currentRange($interval))
      ->{$this->method}($column);

    return round($value, $this->precision);
  }
}
