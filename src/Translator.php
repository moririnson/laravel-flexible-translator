<?php

namespace Laravel\Flexible\Translator;

use DateInterval;
use Illuminate\Contracts\Translation\Translator as Contracts;
use Illuminate\Contracts\Translation\Loader;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Illuminate\Translation\Translator as IlluminateTranslator;
use Laravel\Flexible\translator\Models\Translation;

class Translator implements Contracts
{
    private string $locale;
    private Translation $model;
    private IlluminateTranslator $default;

    public function __construct(string $locale, Translation $model, IlluminateTranslator $default)
    {
        $this->setLocale($locale);
        $this->model = $model;
        $this->default = $default;
    }

    /**
     * Get the translation for a given key.
     *
     * @param  string  $key
     * @param  array  $replace
     * @param  string|null  $locale
     * @return mixed
     */
    public function get($key, array $replace = [], $locale = null)
    {
        $default = $this->default->get($key, $replace, $locale, false);
        if ($default !== $key) {
            return $default;
        }

        $locale = $locale ?? App::getLocale();
        $ttl = config('laravel-flexisble-translator.cache.ttl');
        return Cache::remember($key . '::' . $locale, DateInterval::createFromDateString($ttl), function () use ($key, $replace, $locale) {
            $translation = $this->model->where('key', $key)->take(1)->get()->first();
            if (!isset($translation)) {
                return $this->default->get($key, $replace, $locale);
            }
            $value = $translation->values->firstWhere('locale', $locale);
            if (!isset($value)) {
                return $this->default->get($key, $replace, $locale);
            }
            return array_reduce(array_keys($replace), fn ($carry, $k) => str_replace(':' . $k, $replace[$k], $carry), $value->value); 
        });
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
}