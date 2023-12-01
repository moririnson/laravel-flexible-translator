<?php

namespace Laravel\Flexible\Translator;

use DateInterval;
use Illuminate\Contracts\Translation\Translator as Contracts;
use Illuminate\Contracts\Translation\Loader;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Illuminate\Translation\Translator as IlluminateTranslator;
use Laravel\Flexible\translator\Models\Translation;

class Translator extends IlluminateTranslator
{
    private Translation $model;

    public function __construct(Translation $model, Loader $loader, string $locale)
    {
        $this->model = $model;
        parent::__construct($loader, $locale);
    }

    /**
     * Get the translation for a given key.
     *
     * @param  string  $key
     * @param  array  $replace
     * @param  string|null  $locale
     * @return mixed
     */
    public function get($key, array $replace = [], $locale = null, $fallback = true)
    {
        $default = parent::get($key, $replace, $locale, $fallback);
        if ($default !== $key) {
            return $default;
        }

        $locale = $locale ?? App::getLocale();
        $ttl = config('laravel-flexisble-translator.cache.ttl');
        $cache_key = implode('::', [$key, json_encode($replace), $locale]);
        $value = Cache::remember($cache_key, DateInterval::createFromDateString($ttl), fn () => $this->getValue($key, $locale));
        return parent::makeReplacements($value, $replace);
    }

    /**
     * Get a translation according to an integer value.
     *
     * @param  string  $key
     * @param  \Countable|int|array  $number
     * @param  array  $replace
     * @param  string|null  $locale
     * @return string
     */
    public function choice($key, $number, array $replace = [], $locale = null)
    {
        $default = parent::choice($key, $number, $replace, $locale);
        if ($default !== $key) {
            return $default;
        }

        $locale = $locale ?? App::getLocale();
        $ttl = config('laravel-flexisble-translator.cache.ttl');
        $cache_key = implode('::', [$key, $number, json_encode($replace), $locale]);
        $value = Cache::remember($cache_key, DateInterval::createFromDateString($ttl), fn () => $this->getValue($key, $locale));
        return parent::makeReplacements(
            parent::getSelector()->choose($value, $number, $locale), $replace,
        );
    }

    /**
     * Get the default locale being used.
     *
     * @return string
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * Set the default locale.
     *
     * @param  string  $locale
     * @return void
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;
    }

    private function getValue($key, $locale)
    {
        $translation = $this->model->where('key', $key)->take(1)->get()->first();
        if (!isset($translation) || !array_key_exists($locale, $translation->texts)) {
            return $key;
        }
        return $translation->texts[$locale];
    }
}