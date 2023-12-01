<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Laravel\Flexible\Translator\Models\Translation;
use Laravel\Flexible\Translator\Schemas\TranslationValue;

class ExampleTranslate extends Command
{
    const KEY = 'messages.example';
    const KEY_DB = 'messages.example.db';
    const CHOICE_KEY = 'messages.example.choice';
    const CHOICE_KEY_DB = 'messages.example.choice.db';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'example:translate {--locale=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        App::setLocale($this->option('locale'));
        DB::beginTransaction();

        // use key if `.php` file exists.
        echo __(self::KEY, ['num' => '1']) . "\n";
        Translation::create([
            'key' => self::KEY,
            'texts' => [
                'ja' => '(DB) :num 例',
                'en' => '(DB) example :num',
            ],
        ]);
        echo __(self::KEY, ['num' => '1']) . "\n";

        Translation::create([
            'key' => self::KEY_DB,
            'texts' => [
                'ja' => '(DB) :num 例',
                'en' => '(DB) example :num',
            ],
        ]);
        echo __(self::KEY_DB, ['num' => '1']) . "\n";

        // choice
        echo Lang::choice(self::CHOICE_KEY, 1) . "\n";
        Translation::create([
            'key' => self::CHOICE_KEY,
            'texts' => [
                'ja' => '{0} (DB)  :count 個|{1} (DB)  :count 個|(DB) [2,*] :count 個',
                'en' => '{0} (DB)  :count items|{1} (DB)  :count item|(DB) [2,*] :count items',
            ],
        ]);
        echo Lang::choice(self::CHOICE_KEY, 1) . "\n";

        Translation::create([
            'key' => self::CHOICE_KEY_DB,
            'texts' => [
                'ja' => '{0} (DB)  :count 個|{1} (DB)  :count 個|(DB) [2,*] :count 個',
                'en' => '{0} (DB)  :count items|{1} (DB)  :count item|(DB) [2,*] :count items',
            ],
        ]);
        echo Lang::choice(self::CHOICE_KEY_DB, 1) . "\n";

        DB::rollBack();
    }
}
