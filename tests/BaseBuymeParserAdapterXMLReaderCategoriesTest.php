<?php

declare(strict_types=1);

namespace Buyme\Parser\Tests;

use Buyme\Parser\Adapter\XMLReader;
use Buyme\Parser\BuymeParser;
use Buyme\Parser\Entities\CategoryEntity;
use Exception;
use Generator;
use Illuminate\Support\Facades\Config;

class BaseBuymeParserAdapterXMLReaderCategoriesTest extends BaseBuymeParserTest
{
    /**
     * @throws Exception
     */
    public function testGetCategoriesAsGenerator()
    {
         Config::set('buyme-parser-config.adapters', [
            'xml' => XMLReader::class,
        ]);

        $parser = new BuymeParser();

        $parser->open($this->xmlFile);

        $categories = $parser->getCategories();

        $this->assertIsArray($categories);
    }
    /**
     * @throws Exception
     */
    public function testGetCategoryItemAsCategoriesEntity()
    {
         Config::set('buyme-parser-config.adapters', [
            'xml' => XMLReader::class,
        ]);

        $parser = new BuymeParser();

        $parser->open($this->xmlFile);

        foreach ($parser->getCategories() as $item) {
            $this->assertTrue($item->offsetExists('id'));
            $this->assertTrue($item->offsetExists('value'));
            $this->assertNotNull($item);
            $this->assertInstanceOf(CategoryEntity::class, $item);
        }
    }
}
