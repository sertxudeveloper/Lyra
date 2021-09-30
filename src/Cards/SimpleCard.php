<?php

namespace SertxuDeveloper\Lyra\Cards;

use Illuminate\Http\Request;

abstract class SimpleCard extends Card {

  public string $component = 'card-simple';
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
   * Transform the card into a JSON array.
   *
   * @param Request $request
   * @return array
   */
  public function toArray(Request $request): array {
    $selected = $request->input('interval') ?? $this->defaultInterval;
    $value = $this->value($selected) ?? 0;

    return [
      'component' => $this->component,
      'label' => $this->label(),
      'slug' => $this->slug(),
      'interval' => $this->interval,
      'selected' => $selected,
      'value' => $value,
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
