<?php

namespace Laravel\Flexible\Translator\Schemas;

use Illuminate\Support\Collection;
use Laravel\Flexible\Translator\Schemas\TranslationValue;

class TranslationValuesSchema
{
    public function get($model, string $key, $value, array $attributes): Collection
    {
        $items = collect([]);

        foreach (json_decode($value, true) as $translation_value) {
            $locale = key($translation_value);
            $test_item = new TranslationValue($locale, $translation_value[$locale]);
            $items->push($test_item);
        }

        return $items;
    }

    public function set($model, string $key, $value, array $attributes)
    {
        return json_encode($value);
    }
}
