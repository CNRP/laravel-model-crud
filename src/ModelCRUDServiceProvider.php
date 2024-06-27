<?php

namespace Cnrp\ModelCRUD;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class ModelCRUDServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'modelcrud');
    }

    public function boot()
    {
        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/modelcrud'),
        ]);

        Livewire::component('model-crud', \Cnrp\ModelCRUD\Components\ModelCRUD::class);
		Livewire::component('flash-message', \Cnrp\ModelCRUD\Components\FlashMessage::class);

    }
}
