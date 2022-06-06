<?php

namespace SertxuDeveloper\Lyra\Tests\Resources;

use Illuminate\Database\Eloquent\Model;
use SertxuDeveloper\Lyra\Resources\Resource;
use SertxuDeveloper\Lyra\Tests\Models\Tag;

class Tags extends Resource
{
    /**
     * The model related to the resource.
     *
     * @var class-string<Model>
     */
    public static string $model = Tag::class;

    /**
     * The actions' resource definition.
     *
     * @return array
     */
    public function actions(): array
    {
        return [
            //
        ];
    }

    /**
     * The cards' resource definition.
     *
     * @return array
     */
    public function cards(): array
    {
        return [
            //
        ];
    }

    /**
     * The fields' resource definition.
     *
     * @return array
     */
    public function fields(): array
    {
        return [
            //
        ];
    }
}
