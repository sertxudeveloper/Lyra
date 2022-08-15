<?php

namespace SertxuDeveloper\Lyra\Fields\Concerns;

use Illuminate\Database\Eloquent\Model;

trait ValidationRules
{
    /**
     * The creation validation rules.
     *
     * @var array
     */
    protected array $creationRules = [];

    /**
     * The update validation rules.
     *
     * @var array
     */
    protected array $updateRules = [];

    /**
     * Set the rules for the creation.
     *
     * @param  string[]  $rules
     * @return $this
     */
    public function creationRules(string ...$rules): self {
        $this->creationRules = $rules;

        return $this;
    }

    /**
     * Set the rules for the update.
     *
     * @param  string[]  $rules
     * @return $this
     */
    public function updateRules(string ...$rules): self {
        $this->updateRules = $rules;

        return $this;
    }

    /**
     * Get the creation rules.
     *
     * @param  Model  $model
     * @return array
     */
    public function getCreationRules(Model $model): array {
        return $this->prepareRules($model, $this->creationRules);
    }

    /**
     * Get the update rules.
     *
     * @param  Model  $model
     * @return array
     */
    public function getUpdateRules(Model $model): array {
        return $this->prepareRules($model, $this->updateRules);
    }

    /**
     * Prepare the rules for validation.
     *
     * @param  Model  $model
     * @param  array  $rules
     * @return array
     */
    protected function prepareRules(Model $model, array $rules): array {
        return str_replace('{{resourceId}}', $model->getKey(), $rules);
    }
}
