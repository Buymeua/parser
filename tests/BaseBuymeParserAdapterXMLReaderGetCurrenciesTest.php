<?php

declare(strict_types=1);

namespace Buyme\Parser\Tests;

use Buyme\Parser\Adapter\XMLReader;
use Buyme\Parser\BuymeParser;
use Buyme\Parser\Entities\CurrencyEntity;
use Exception;
use Illuminate\Support\Facades\Config;

class BaseBuymeParserAdapterXMLReaderGetCurrenciesTest extends BaseBuymeParserTest
{
    /**
     * @throws Exception
     */
    public function testGetCurrenciesAsGenerator()
    {
         Config::set('buyme-parser-config.adapters', [
            'xml' => XMLReader::class,
        ]);

        $parser = new BuymeParser();

        $parser->open($this->xmlFile);

        $categories = $parser->getCurrencies();

        $this->assertIsArray($categories);
    }
    /**
     * @throws Exception
     */
    public function testGetCurrencyItemAsCurrencyEntity()
    {
         Config::set('buyme-parser-config.adapters', [
            'xml' => XMLReader::class,
        ]);

        $parser = new BuymeParser();

        $parser->open($this->xmlFile);

        foreach ($parser->getCurrencies() as $item) {
            $this->assertTrue($item->offsetExists('id'));
            $this->assertIsString($item->offsetGet('id'));

            $this->assertTrue($item->offsetExists('value'));
            $this->assertIsString($item->offsetGet('value'));

            $this->assertTrue($item->offsetExists('rate'));
            $this->assertIsString($item->offsetGet('rate'));

            $this->assertNotNull($item);
            $this->assertInstanceOf(CurrencyEntity::class, $item);
        }
    }
}
