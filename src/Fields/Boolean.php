<?php

namespace SertxuDeveloper\Lyra\Fields;

use Illuminate\Database\Eloquent\Model;

class Boolean extends Field
{
    public string $component = 'field-boolean';

    public string $align = 'center';

    protected mixed $trueValue = true;

    protected mixed $falseValue = false;

    /**
     * Add field-specific data to the response
     */
    public function additional(Model $model): array {
        $value = is_callable($this->column) ? call_user_func($this->column, $model) : $model->{$this->column};

        return [
            'value' => $value == $this->trueValue,
        ];
    }

    /**
     * Define the false value
     *
     * @return $this
     */
    public function falseValue(mixed $value): self {
        $this->falseValue = $value;

        return $this;
    }

    /**
     * Update the field value using the given data
     *
     * @param  Model  $model  The model to be updated
     * @param  array  $data  The new validated data
     */
    public function save(Model $model, array $data): void {
        $model->{$this->getKey()} = $data[$this->getKey()] == true ? $this->trueValue : $this->falseValue;
    }

    /**
     * Define the true value
     *
     * @return $this
     */
    public function trueValue(mixed $value): self {
        $this->trueValue = $value;

        return $this;
    }
}
