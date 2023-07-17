<?php

namespace SertxuDeveloper\Lyra\Actions;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;

abstract class Action implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public Model $model;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($model = null) {
        if (!empty($model)) {
            $this->model = $model;
        }
    }

    /**
     * Get the label of the resource
     */
    public static function label(): string {
        return Str::title(Str::snake(class_basename(get_called_class()), ' '));
    }

    /**
     * Get the slug of the resource
     */
    public static function slug(): string {
        return Str::kebab(class_basename(get_called_class()));
    }

    /**
     * Execute the action.
     *
     * @return void
     */
    abstract public function handle();
}
