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
    private string $realHttpLinkWithParameters = 'https://vent-motor.prom.ua/products_feed.xml?hash_tag=75dd42d833264faabc17e317301a9c60&sales_notes=&product_ids=1413018733%2C1413016876%2C1413012645%2C1385814792%2C1385801423%2C1385782517%2C1385770173%2C1385768238%2C1385745766%2C1385743170%2C1385739318%2C1385733918%2C1385728405%2C1385637881%2C1385621084%2C1385605087%2C1385591595&label_ids=&exclude_fields=&html_description=0&yandex_cpa=&process_presence_sure=&languages=uk%2Cru&group_ids=';

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
