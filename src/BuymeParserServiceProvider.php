<?php

declare(strict_types=1);

namespace Buyme\Parser;

use Illuminate\Support\ServiceProvider;

class BuymeParserServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadTranslationsFrom(__DIR__.'/../lang', 'buyme-parser-lang');

        $this->publishes([
            __DIR__.'/../lang' => $this->app->langPath('vendor/buyme_parser'),
        ]);

        $this->publishes([
            __DIR__.'/../config/buyme_parser.php' => config_path('buyme_parser.php')
        ], 'buyme-parser-config');
    }

    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/buyme_parser.php', 'buyme-parser-config');

        $this->app->bind(BuymeParser::class, function () {
            return new BuymeParser();
        });

        $this->app->alias(BuymeParser::class, 'laravel-buyme-parser');
    }
}