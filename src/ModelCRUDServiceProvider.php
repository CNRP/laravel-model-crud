<?php

namespace Cnrp\ModelCRUD;

use Illuminate\Support\ServiceProvider;

class ModelCRUDServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'model-crud');
    }

    public function boot()
    {
        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/model-crud'),
        ]);

        \Livewire\Livewire::component('model-crud', \Cnrp\ModelCRUD\Livewire\ModelCRUD::class);
    }
}
