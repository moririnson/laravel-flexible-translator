<?php

namespace Laravel\Flexible\Translator\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Flexible\Translator\Schemas\TranslationValuesSchema;

class Translation extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'values' => TranslationValuesSchema::class,
    ];
} 