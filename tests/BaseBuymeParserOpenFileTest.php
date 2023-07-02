<?php

declare(strict_types=1);

namespace Buyme\Parser\Tests;

use Buyme\Parser\Adapter\SimpleXML;
use Buyme\Parser\Adapter\XMLReader;
use Buyme\Parser\BuymeParser;
use Exception;
use Illuminate\Support\Facades\Config;

class BaseBuymeParserOpenFileTest extends BaseBuymeParserTest
{
    private string $realHttpLinkWithParameters = 'https://hammerite.prom.ua/products_feed.xml?hash_tag=9f6c4c8a66f08e2c7875b8441ec445ea&sales_notes=&product_ids=&label_ids=&exclude_fields=&html_description=0&yandex_cpa=&process_presence_sure=&languages=uk%2Cru&group_ids=114925901&nested_group_ids=114925901';

    /**
     * @throws Exception
     */
    public function testOpenLocalFileWithSimpleXMLAdapter()
    {
        Config::set('buyme-parser-config.adapters', [
            'xml' => SimpleXML::class,
        ]);

        $parser = new BuymeParser();

        $parser->open($this->xmlFile);

        $this->assertInstanceOf(SimpleXML::class, $parser->getDriver());
    }

    /**
     * @throws Exception
     */
    public function testOpenLocalFileWithXMLReaderAdapter()
    {
        Config::set('buyme-parser-config.adapters', [
            'xml' => XMLReader::class,
        ]);

        $parser = new BuymeParser();

        $parser->open($this->xmlFile);

        $this->assertInstanceOf(XMLReader::class, $parser->getDriver());
    }

    /**
     * @throws Exception
     */
    public function testOpenHttpFileWithParametersSimpleXMLAdapter()
    {
        Config::set('buyme-parser-config.adapters', [
            'xml' => SimpleXML::class,
        ]);

        $parser = new BuymeParser();

        $parser->open($this->realHttpLinkWithParameters);

        $this->assertInstanceOf(SimpleXML::class, $parser->getDriver());
    }
}
