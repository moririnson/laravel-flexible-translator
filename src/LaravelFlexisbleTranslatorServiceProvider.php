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
            $locale = $this->app->getLocale();
            $loader = $this->app['translation.loader'];
            $default_translator = new IlluminateTranslator($loader, $locale);
            return new Translator($locale, new Translation, $default_translator);
        });
    }
}