<?php

namespace SertxuDeveloper\Lyra\Tests\Lyra\Resources;

use SertxuDeveloper\Lyra\Fields\DateTime;
use SertxuDeveloper\Lyra\Fields\ID;
use SertxuDeveloper\Lyra\Fields\Password;
use SertxuDeveloper\Lyra\Fields\Text;
use SertxuDeveloper\Lyra\Resources\Resource;
use SertxuDeveloper\Lyra\Tests\Models\User;

class Users extends Resource
{
    public static string $model = User::class;

    public static array $search = [
        'id', 'name', 'email',
    ];

    /**
     * The actions' resource definition
     *
     * @return array
     */
    public function actions(): array {
        return [
            //
        ];
    }

    /**
     * The cards' resource definition
     *
     * @return array
     */
    public function cards(): array {
        return [
            //
        ];
    }

    /**
     * The fields' resource definition
     *
     * @return array
     */
    public function fields(): array {
        return [
            ID::make('Id'),

            Text::make('Name')
              ->rules('required', 'max:255')
              ->sortable(),

            Text::make('Email')
              ->creationRules('required', 'email', 'unique:users,email')
              ->updateRules('required', 'email', 'unique:users,email,{{resourceId}}')
              ->sortable(),

            Password::make('Password')
                ->creationRules('required', 'string', 'min:6')
                ->updateRules('nullable', 'string', 'min:6'),

            DateTime::make('Email verified at'),
        ];
    }
}
