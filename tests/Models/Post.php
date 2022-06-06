<?php

namespace SertxuDeveloper\Lyra\Tests\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use SertxuDeveloper\Lyra\Tests\Database\Factories\PostFactory;

class Post extends Model {

    use HasFactory, SoftDeletes;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array<string>|bool
     */
    protected $guarded = [];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'published_at' => 'datetime',
    ];

    /**
     * Create a new factory instance for the model.
     *
     * @return PostFactory
     */
    protected static function newFactory(): PostFactory {
        return new PostFactory();
    }
}
