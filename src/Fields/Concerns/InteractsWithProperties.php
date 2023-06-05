<?php

namespace SertxuDeveloper\Lyra\Fields\Concerns;

use ReflectionObject;

trait InteractsWithProperties
{
    /**
     * Get the public properties of the field.
     *
     * @return array
     */
    public function getProperties(): array {
        $publicProperties = array_filter((new ReflectionObject($this))->getProperties(), function ($property) {
            return $property->isPublic() && !$property->isStatic();
        });

        $data = [];

        foreach ($publicProperties as $property) {
            $data[$property->getName()] = $property->isInitialized($this) ? $property->getValue($this) : null;
        }

        return $data;
    }
}
