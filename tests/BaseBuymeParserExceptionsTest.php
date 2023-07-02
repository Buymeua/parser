<?php

declare(strict_types=1);

namespace Buyme\Parser\Tests;

use Buyme\Parser\Adapter\SimpleXML;
use Buyme\Parser\BuymeParser;
use Exception;
use Illuminate\Support\Facades\Config;

class BaseBuymeParserExceptionsTest extends BaseBuymeParserTest
{
    private string $xmlNotExistsFile = __DIR__ . '/files/xml/notExists.xml';
    private string $xmlEmptyFile = __DIR__ . '/files/xml/empty.xml';
    private string $xmlEmptyHttpFile = __DIR__ . 'https://example.com/files/xml/empty.xml';
    private string $undefinedFileExtension = __DIR__ . '/files/txt/undefined.txt';
    private string $realHttpErrorFileLinkWithoutParameters = 'https://hammerite.prom.ua/products_feed.xml';

    /**
     * @throws Exception
     */
    public function testOpenLocalNotExistsFile()
    {
         Config::set('buyme-parser-config.adapters', [
            'xml' => SimpleXML::class,
        ]);

        $parser = new BuymeParser();

        $expectedMessage = __("buyme-parser-lang::buyme_parser.errors.file_not_exists", [
            'filename' => $this->xmlNotExistsFile
        ]);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage($expectedMessage);

        $parser->open($this->xmlNotExistsFile);
    }

    /**
     * @throws Exception
     */
    public function testOpenLocalEmptyFile()
    {
        Config::set('buyme-parser-config.adapters', [
            'xml' => SimpleXML::class,
        ]);

        $parser = new BuymeParser();

        $expectedMessage = __("buyme-parser-lang::buyme_parser.errors.file_is_empty", [
            'filename' => $this->xmlEmptyFile
        ]);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage($expectedMessage);

        $parser->open($this->xmlEmptyFile);
    }

    /**
     * @throws Exception
     */
    public function testOpenLocalFileWithEmptyConfigMappingParser()
    {
        Config::set('buyme-parser-config.adapters', []);

        $parser = new BuymeParser();

        $expectedMessage = __("buyme-parser-lang::buyme_parser.errors.config_mapping_parser_is_empty");

        $this->expectException(Exception::class);
        $this->expectExceptionMessage($expectedMessage);

        $parser->open($this->xmlFile);
    }

    /**
     * @throws Exception
     */
    public function testOpenLocalFileWithUndefinedConfigMappingParser()
    {
        Config::set('buyme-parser-config.adapters', [
            'xml' => SimpleXML::class,
        ]);

        $parser = new BuymeParser();

        $extension = pathinfo($this->undefinedFileExtension, PATHINFO_EXTENSION);

        $expectedMessage = __("buyme-parser-lang::buyme_parser.errors.config_mapping_parser_adapter_not_found", [
            'extension' => $extension
        ]);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage($expectedMessage);

        $parser->open($this->undefinedFileExtension);
    }

    public function testGetFileSizeWithRemoteFile()
    {
        Config::set('buyme-parser-config.adapters', [
            'xml' => SimpleXML::class,
        ]);

        /** @var BuymeParser $parser */
        $parser = $this->mockFunction(BuymeParser::class, 'getFileSize', 0);

        $expectedMessage = __("buyme-parser-lang::buyme_parser.errors.file_is_empty", [
            'filename' => $this->xmlEmptyHttpFile
        ]);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage($expectedMessage);

        $parser->open($this->xmlEmptyHttpFile);
    }

    /**
     * @throws Exception
     */
    public function testOpenHttpErrorFileSimpleXMLAdapter()
    {
        Config::set('buyme-parser-config.adapters', [
            'xml' => SimpleXML::class,
        ]);

        $parser = new BuymeParser();

        $expectedMessage = __("buyme-parser-lang::buyme_parser.errors.file_is_empty", [
            'filename' => $this->xmlEmptyHttpFile
        ]);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage($expectedMessage);

        $parser->open($this->xmlEmptyHttpFile);
    }
}
