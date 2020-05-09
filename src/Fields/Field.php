<?php

namespace SertxuDeveloper\Lyra\Fields;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

abstract class Field {

  protected $component;

  protected $callback = null;

  protected $hideOnIndex = false;
  protected $hideOnShow = false;
  protected $hideOnCreate = false;
  protected $hideOnEdit = false;

  protected $data = null;
  protected $rules = [];

  /**
   * Create a new instance of the Field
   *
   * @param string $name
   * @param null $column
   * @return static
   */
  public static function make(string $name, $column = null) {
    $class = new static();
    $class->data = collect([]);

    if (!$column) {
      $column = Str::snake($name);
    } else {
      if (is_callable($column) && gettype($column) === 'object') {
        $class->callback = $column;
        $column = Str::snake($name);
        $class->hideOnCreate = true;
        $class->hideOnEdit = true;
      }
    }

    $class->data->put('component', $class->component);
    $class->data->put('name', $name);
    $class->data->put('column', $column);
    return $class;
  }

  /**
   * Add a description to the Field
   *
   * @param string $text
   * @return $this
   */
  public function description(string $text) {
    $this->data->put('description', $text);
    return $this;
  }

  /**
   * Get the Eloquent column name for the field
   *
   * @return string
   */
  public function getColumnName(): string {
    return $this->data->get('column');
  }

  /**
   * Get the value of the Field
   *
   * @param $model
   * @param string $type Can be 'index', 'edit', 'show' or 'create'
   * @return array
   */
  public function getValue($model, string $type): array {
    $value = null;

    if (is_callable($this->callback)) {
      $this->callback = $this->callback->bindTo($model);
      $value = call_user_func($this->callback);
    } else {
      if ($this->isTranslatable()) {
        $value = $this->getTranslatedValue($model, $type);
      } else {
        $value = $this->getOriginalValue($model, $type);
      }
    }

    $this->data->put('value', $value);

    return $this->data->toArray();
  }

  /**
   * Get the visibility of the Field
   *
   * @return array
   */
  public function getVisibility(): array {
    return [
      "hideOnIndex" => $this->hideOnIndex,
      "hideOnShow" => $this->hideOnShow,
      "hideOnCreate" => $this->hideOnCreate,
      "hideOnEdit" => $this->hideOnEdit,
    ];
  }

  /**
   * Overwrite hideOnCreate visibility
   *
   * @param bool $hide
   * @return static
   */
  public function hideOnCreate(bool $hide = true) {
    $this->hideOnCreate = $hide;
    return $this;
  }

  /**
   * Overwrite hideOnEdit visibility
   *
   * @param bool $hide
   * @return static
   */
  public function hideOnEdit(bool $hide = true) {
    $this->hideOnEdit = $hide;
    return $this;
  }

  /**
   * Overwrite hideOnIndex visibility
   *
   * @param bool $hide
   * @return static
   */
  public function hideOnIndex(bool $hide = true) {
    $this->hideOnIndex = $hide;
    return $this;
  }

  /**
   * Overwrite hideOnShow visibility
   *
   * @param bool $hide
   * @return static
   */
  public function hideOnShow(bool $hide = true) {
    $this->hideOnShow = $hide;
    return $this;
  }

  /**
   * Return true if the Field is primary or false if not
   *
   * @return bool
   */
  public function isPrimary(): bool {
    return (bool)$this->data->get('primary');
  }

  /**
   * Get if the Field is translatable or not
   *
   * @return bool
   */
  public function isTranslatable(): bool {
    return config('lyra.translator.enabled') && $this->data->get('translatable')
      && request()->input('lang') && request()->input('lang') !== config('lyra.translator.default_locale');
  }

  /**
   * Add validation rules to Field
   *
   * @param mixed ...$rules
   * @return $this
   */
  public function rules(...$rules): self {
    $this->rules = $rules;
    return $this;
  }

  /**
   * Save the $field value in the model
   *
   * @param array $field
   * @param $model
   */
  public function saveValue(array $field, $model): void {
    $model[$this->data->get('column')] = $field['value'];
  }

  /**
   * Set the Field as sortable
   *
   * @return $this
   */
  public function sortable() {
    $this->data->put('sortable', true);
    return $this;
  }

  /**
   * Set the Field as translatable
   *
   * @return $this
   */
  public function translatable() {
    $this->data->put('translatable', true);
    return $this;
  }

  /**
   * Validate the Field data
   *
   * @param $value
   * @param $resource
   * @return \Illuminate\Contracts\Validation\Validator
   */
  public function validate($value, $resource) {
    preg_match('/{{(\w+)}}/', json_encode($this->rules), $matches);
    if (!empty($matches)) $rules = str_replace('{{id}}', $resource[$matches[1]], $this->rules);
    return Validator::make([$this->data->get('column') => $value], [
      $this->data->get('column') => isset($rules) ? $rules : $this->rules,
    ]);
  }

  /**
   * Get the original value of the Field
   * @param $model
   * @param string $type Can be 'index', 'edit', 'show' or 'create'
   * @return mixed
   */
  protected function getOriginalValue($model, string $type) {
    return (isset($model[$this->data->get('column')])) ? $model[$this->data->get('column')] : null;
  }

  /**
   * Get the translated value of the Field
   * The language is specified as a request GET input
   *
   * @param $model
   * @param string $type Can be 'index', 'edit', 'show' or 'create'
   * @return mixed
   */
  protected function getTranslatedValue($model, string $type) {
    return $model->getTranslated($this->data->get('column'), request()->input('lang'));
  }
}
