<?php

namespace SertxuDeveloper\Lyra\Fields;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use SertxuDeveloper\Lyra\Http\Controllers\TranslatorController;
use SertxuDeveloper\Lyra\Lyra;

class MorphOneToOne extends Field {

  protected $component = "morph-one-to-one-field";
  protected $hideOnIndex = true;

  /**
   * Delete the file
   * The file will be deleted only if the prunable option is enabled
   *
   * @param $model
   */
  public function delete($model) {
    $this->removeMorphed($model);
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

    if ($type === 'create') {
      $value = [];
    } else if ($type === 'edit') {
      $value = $this->getOriginalValueEdit($model);
    } else if ($type === 'show') {
      $value = $this->getOriginalValueShow($model);
    }

    $this->data->put('value', $value);

    return $this->data->toArray();
  }

  /**
   * Overwrite hideOnIndex visibility
   *
   * @param bool $hide
   * @return static
   */
  public function hideOnIndex(bool $hide = true) {
    $this->hideOnIndex = true;
    return $this;
  }

  /**
   * Save the $field value in the model
   *
   * @param array $field
   * @param $model
   */
  public function saveValue(array $field, $model): void {
    $morphedModel = $model->{$this->data->get('column')};
    $removed = false;

    /** The morphed class has been modified */
    if ($morphedModel && get_class($morphedModel) !== $field['resource']::$model) {
      /** Remove the previous morphed */
      $removed = $this->removeMorphed($model);
    }

    if (!$morphedModel || $removed) {
      $morphedModel = $field['resource']::$model;
      $morphedModel = new $morphedModel;
    }

    $translatableFields = [];

    foreach ($field['value'] as $item) {
      if (TranslatorController::isTranslatorUsable() && $this->isFieldTranslatable($item)) {
        $translatableFields[$item['column']] = $item['value'];
      } else {
        $morphedModel->{$item['column']} = $item['value'];
      }
    }

    if (count($translatableFields)) {
      TranslatorController::updateTranslation($translatableFields, $morphedModel);
    }

    $morphedModel->save();
    $model->{$this->data->get('column')}()->associate($morphedModel);
  }

  public function types(array $types) {
    $resources = Lyra::getResources();
    $availableTypes = [];
    $this->data->put('resource', "");

    foreach ($types as $type) {
      $availableTypes[] = [
        "key" => $type,
        "value" => Arr::last(explode('\\', $type)),
        "fields" => $this->getFieldsType($type),
      ];
    }

    $this->data->put('types', $availableTypes);
    return $this;
  }

  private function getFieldsType($type) {
    $resourceCollection = new $type([]);
    return $resourceCollection->getCollection(request(), 'create')['collection']['data'][0];
  }

  /**
   * @param $model
   * @return mixed
   */
  private function getOriginalValueEdit($model) {
    $morphedModel = $model->{$this->data->get('column')};
    $resource = $this->setResource($model);

    if (!$morphedModel && !$resource) return [];

    $resourceCollection = new $resource(collect([$morphedModel]));

//    $resourceCollection->singular = Str::singular($this->data->get('name'));
//    $resourceCollection->plural = Str::plural($this->data->get('name'));

    $this->data->put('id', $morphedModel->getKey());
    return $resourceCollection->getCollection(request(), 'edit')['collection']['data'][0];
  }

  /**
   * @param $model
   * @return mixed
   */
  private function getOriginalValueShow($model) {
    $morphedModel = $model->{$this->data->get('column')};
    $resource = $this->setResource($model);

    if (!$morphedModel && !$resource) return [];

    $resourceCollection = new $resource(collect([$morphedModel]));

//    $resourceCollection->singular = Str::singular($this->data->get('name'));
//    $resourceCollection->plural = Str::plural($this->data->get('name'));

    return $resourceCollection->getCollection(request(), 'show')['collection']['data'][0];
  }

  private function isFieldTranslatable($field) {
    return isset($field['translatable']) && $field['translatable'] === true;
  }

  /**
   * @param $model
   * @return bool
   */
  private function removeMorphed($model) {
    $deleteModel = $model->{$this->data->get('column')};
    if (!$deleteModel) return false;

    if (TranslatorController::isTranslatorUsable()) {
      TranslatorController::removeTranslations($deleteModel);
    }

    $model->{$this->data->get('column')}()->dissociate();
    $deleteModel->delete();
    return true;
  }

  /**
   * @param $model
   * @return string|null
   */
  private function setResource($model) {
    if ($model->{$this->data->get('column')}) {
      $class = get_class($model->{$this->data->get('column')});

      foreach ($this->data->get('types') as $type) {
        if ($type['key']::$model === $class) {
          $this->data->put('resource', $type['key']);
          return $type['key'];
        }
      }
    }

    return null;
  }
}
