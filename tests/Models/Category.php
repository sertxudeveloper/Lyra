<?php

namespace SertxuDeveloper\Lyra\Tests\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use SertxuDeveloper\Lyra\Tests\Database\Factories\CategoryFactory;

class Category extends Model {

    use HasFactory;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array<string>|bool
     */
    protected $guarded = [];

    /**
     * Create a new factory instance for the model.
     *
     * @return CategoryFactory
     */
    protected static function newFactory(): CategoryFactory {
        return new CategoryFactory();
    }
}
