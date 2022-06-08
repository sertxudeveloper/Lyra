<?php

namespace SertxuDeveloper\Lyra\Tests\Resources;

use Illuminate\Database\Eloquent\Model;
use SertxuDeveloper\Lyra\Fields\Password;
use SertxuDeveloper\Lyra\Fields\Text;
use SertxuDeveloper\Lyra\Resources\Resource;
use SertxuDeveloper\Lyra\Tests\Models\User;

class Users extends Resource {

    /**
     * The model related to the resource.
     *
     * @var class-string<Model>
     */
    static public string $model = User::class;

    /**
     * Columns where the search is enabled.
     *
     * @var string[] $search
     */
    static public array $search = [
        'name', 'email',
    ];

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
            Text::make('Name')
                ->rules('required', 'max:255'),

            Text::make('Email')
                ->creationRules('required', 'email', 'max:255', 'unique:users,email')
                ->updateRules('required', 'email', 'max:255', 'unique:users,email,{{resourceId}}'),

            Password::make('Password')
                ->rules('required', 'min:8'),
        ];
    }
}
