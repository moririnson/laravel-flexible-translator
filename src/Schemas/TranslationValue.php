<?php

namespace Laravel\Flexible\Translator\Schemas;

use JsonSerializable;

class TranslationValue implements JsonSerializable
{
    public string $locale;
    public string $value;
    
    public function __construct(string $locale, string $value)
    {
        $this->locale = $locale;
        $this->value = $value;
    }

    public function jsonSerialize(): array
    {
        return [
            $this->locale => $this->value,
        ];
    }
}
