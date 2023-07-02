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
}
