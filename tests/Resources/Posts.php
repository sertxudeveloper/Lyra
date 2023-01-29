<?php

namespace SertxuDeveloper\Lyra\Tests\Resources;

use Illuminate\Database\Eloquent\Model;
use SertxuDeveloper\Lyra\Fields\DateTime;
use SertxuDeveloper\Lyra\Fields\ID;
use SertxuDeveloper\Lyra\Fields\Image;
use SertxuDeveloper\Lyra\Fields\Slug;
use SertxuDeveloper\Lyra\Fields\Text;
use SertxuDeveloper\Lyra\Fields\Textarea;
use SertxuDeveloper\Lyra\Resources\Resource;
use SertxuDeveloper\Lyra\Tests\Models\Post;

class Posts extends Resource
{
    /**
     * The model related to the resource.
     *
     * @var class-string<Model>
     */
    public static string $model = Post::class;

    /**
     * Columns where the search is enabled.
     *
     * @var string[]
     */
    public static array $search = [
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
            ID::make('id'),

            Text::make('title')
                ->rules('required', 'max:255')
                ->sortable(),

            Slug::make('slug')
                ->rules('required', 'max:255'),

            Textarea::make('excerpt')
                ->rules('required', 'max:255'),

            Textarea::make('body')
                ->rules('required', 'max:255'),

            Image::make('poster')
                ->rules('required', 'max:255'),

            DateTime::make('published_at')
                ->hideOnForms()
                ->hideOnIndex(),
        ];
    }
}
