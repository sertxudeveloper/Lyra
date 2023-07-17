<?php

namespace SertxuDeveloper\Lyra\Cards;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

abstract class SimpleCard extends Card
{
    /**
     * The card component
     */
    public string $component = 'card-simple';

    /**
     * Precision for the rounding method
     */
    public int $precision = 0;

    /**
     * Get the average value of the specified column
     */
    public function avg(Request $request, Builder|string $model, string $column): float {
        return $this->query($request, $model, 'avg', $column);
    }

    /**
     * Calculate the value of the card.
     * Supported 'count', 'min', 'max', 'avg' and 'sum' methods
     */
    abstract public function calculate(Request $request): float;

    /**
     * Count instances of the model
     */
    public function count(Request $request, Builder|string $model, string $column = null): float {
        return $this->query($request, $model, 'count', $column);
    }

    /**
     * Get the maximum value of the specified column
     */
    public function max(Request $request, Builder|string $model, string $column): float {
        return $this->query($request, $model, 'max', $column);
    }

    /**
     * Get the minimum value of the specified column
     */
    public function min(Request $request, Builder|string $model, string $column): float {
        return $this->query($request, $model, 'min', $column);
    }

    /**
     * Return the result of the query
     */
    public function query(Request $request, Builder|string $model, string $method, string $column = null): float {
        $query = $model instanceof Builder ? $model : $model::query();
        $column = $column ?? $query->getModel()->getQualifiedKeyName();

        return round($query->{$method}($column), $this->precision);
    }

    /**
     * Get the aggregate value of the specified column
     */
    public function sum(Request $request, Builder|string $model, string $column): float {
        return $this->query($request, $model, 'sum', $column);
    }

    /**
     * Transform the card into a JSON array.
     */
    public function toArray(Request $request): array {
        $value = $this->calculate($request) ?? 0;

        return [
            'component' => $this->component,
            'label' => $this->label(),
            'slug' => $this->slug(),
            'value' => $value,
        ];
    }
}
