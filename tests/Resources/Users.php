<?php

namespace SertxuDeveloper\Lyra\Tests\Resources;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User;
use SertxuDeveloper\Lyra\Resources\Resource;

class Users extends Resource {

    /**
     * The model related to the resource.
     *
     * @var class-string<Model>
     */
    static public string $model = User::class;

    /**
     * The actions' resource definition.
     *
     * @return array
     */
    public function actions(): array {
        return [
            //
        ];
    }

    /**
     * The cards' resource definition.
     *
     * @return array
     */
    public function cards(): array {
        return [
            //
        ];
    }

    /**
     * The fields' resource definition.
     *
     * @return array
     */
    public function fields(): array {
        return [
            //
        ];
    }
}
