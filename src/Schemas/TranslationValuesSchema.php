<?php

namespace Laravel\Flexible\Translator\Schemas;

use Laravel\Flexible\Translator\Schemas\TranslationValue;

class TranslationValuesSchema
{
    public function get($model, string $key, $value, array $attributes): array
    {
        $values = json_decode($value, true);
        assert(is_array($values));
        foreach ($values as $k => $v) {
            assert(is_string($k));
            assert(is_string($v) || $v == null);
        }
        return $values;
    }

    public function set($model, string $key, $value, array $attributes)
    {
        assert(is_array($value));
        foreach ($value as $k => $v) {
            assert(is_string($k));
            assert(is_string($v) || $v == null);
        }
        return json_encode($value);
    }
}
