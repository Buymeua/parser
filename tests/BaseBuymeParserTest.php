<?php

declare(strict_types=1);

namespace Buyme\Parser\Tests;

use Buyme\Parser\BuymeParserServiceProvider;
use Illuminate\Support\Facades\Config;
use Orchestra\Testbench\TestCase as BaseTestCase;

abstract class BaseBuymeParserTest extends BaseTestCase
{
    protected string $xmlFile = __DIR__ . '/files/xml/data.xml';

    protected function getPackageProviders($app): array
    {
        return [BuymeParserServiceProvider::class];
    }

    protected function tearDown(): void
    {
        parent::tearDown();

         Config::set('buyme-parser-config.adapters', []);
    }
    protected function array_diff_assoc_recursive($array1, $array2): array
    {
        $difference = [];
        foreach ($array1 as $key => $value) {
            if (is_array($value)) {
                if (!isset($array2[$key]) || !is_array($array2[$key])) {
                    $difference[$key] = $value;
                } else {
                    $new_diff = $this->array_diff_assoc_recursive($value, $array2[$key]);
                    if (!empty($new_diff))
                        $difference[$key] = $new_diff;
                }
            } else if (!array_key_exists($key, $array2) || $array2[$key] !== $value) {
                $difference[$key] = $value;
            }
        }
        return $difference;
    }

    protected function mockFunction($className, $functionName, $returnValue)
    {
        $mock = $this->getMockBuilder($className)
            ->onlyMethods([$functionName])
            ->getMock();

        $mock->method($functionName)
            ->willReturn($returnValue);

        $this->app->instance($className, $mock);

        return $mock;
    }
}
