<?php

namespace SertxuDeveloper\Lyra\Cards;

use Illuminate\Http\Request;

abstract class MetricCard extends Card {

  public string $component = 'card-metric';
  public string $column = '';
  public string $class = '';

  public array $interval = [
    '1 month' => '1 month',
    '3 months' => '3 months',
    '6 months' => '6 months',
    '1 year' => '1 year',
  ];

  public string $defaultInterval = '3 months';

  /**
   * Return the instance count in the current interval
   *
   * @param $interval
   * @return mixed
   */
  public function value($interval) {
    return $this->class::query()->whereBetween($this->column(), [now()->sub($interval), now()])->count();
  }

  /**
   * Return the instance count in the previous interval
   *
   * @return mixed
   */
  public function previous($interval) {
    return $this->class::query()->whereBetween($this->column(), [now()->sub($interval)->sub($interval), now()->sub($interval)])->count();
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
   * Get the column used by the selected interval
   *
   * @return string
   */
  public function column(): string {
    return $this->column ?: $this->class::CREATED_AT;
  }

  /**
   * Transform the card into a JSON array.
   *
   * @param Request $request
   * @return array
   */
  public function toArray(Request $request): array {
    $selectedInterval = $request->input('interval') ?? $this->defaultInterval;
    $value = $this->value($selectedInterval);
    $previous = $this->previous($selectedInterval);
    $difference = $this->difference($value, $previous);

    return [
      'component' => $this->component,
      'label' => $this::label(),
      'slug' => $this::slug(),
      'interval' => $this->interval,
      'selectedInterval' => $selectedInterval,
      'value' => $value,
      'difference' => $difference,
    ];
  }
}
