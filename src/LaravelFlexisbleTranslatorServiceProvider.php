<?php

namespace Laravel\Flexible\Translator;

use Illuminate\Contracts\Translation\Translator as TranslatorContracts;
use Illuminate\Translation\Translator as IlluminateTranslator;
use Laravel\Cache\Attribute\Cacheable;
use Laravel\Cache\Factories\WrapperFactory;
use Laravel\Cache\Wrappers\CacheableWrapper;
use Laravel\Flexible\Translator\Models\Translation;
use RecursiveIteratorIterator;
use ReflectionClass;

class LaravelFlexisbleTranslatorServiceProvider extends \Illuminate\Support\ServiceProvider
{
    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');
        $this->mergeConfigFrom(
            __DIR__ . '/config/laravel-flexisble-translator.php',
            'laravel-flexisble-translator',
        );
        $this->app->extend('translator', function () {
            $translation = $this->app->make(Translation::class);
            $loader = $this->app['translation.loader'];
            $locale = $this->app->getLocale();
            return new Translator($translation, $loader, $locale);
        });
    }
}