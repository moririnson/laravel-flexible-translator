# Laravel Flexible Translator
This is package of translations for Laravel

See also [example](./example/).

## Install

```bash
composer require morimorim/laravel-flexisble-translator
```

## Usage
### 1.Run migrate to create `translations` table

```
php artisan migrate
```

### 2.Insert translations

```
Translation::create([
    'key' => self::KEY_DB,
    'texts' => [
        'ja' => '(DB) :num 例',
        'en' => '(DB) example :num',
    ],
]);
```

### 3.Use `trans` like same as default.

```
trans('messages.example');
__('messages.example');
Lang::choice('messages.example.choice', 10);
```