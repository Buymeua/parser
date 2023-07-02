<?php

declare(strict_types=1);

namespace Buyme\Parser\Tests;

use Buyme\Parser\Adapter\XMLReader;
use Buyme\Parser\BuymeParser;
use Buyme\Parser\Entities\ProductEntity;
use Exception;
use Generator;
use Illuminate\Support\Facades\Config;

class BaseBuymeParserAdapterXMLReaderGetProductsTest extends BaseBuymeParserTest
{
    /**
     * @throws Exception
     */
    public function testGetProductsAsGenerator()
    {
         Config::set('buyme-parser-config.adapters', [
            'xml' => XMLReader::class,
        ]);

        $parser = new BuymeParser();

        $parser->open($this->xmlFile);

        $products = $parser->getProducts();

        $this->assertInstanceOf(Generator::class, $products);
    }

    /**
     * @throws Exception
     */
    public function testGetProductItemAsProductEntity()
    {
         Config::set('buyme-parser-config.adapters', [
            'xml' => XMLReader::class,
        ]);

        $parser = new BuymeParser();

        $parser->open($this->xmlFile);

        foreach ($parser->getProducts() as $item) {
            $this->assertTrue($item->offsetExists('id'));
            $this->assertIsString($item->offsetGet('id'));

            $this->assertTrue($item->offsetExists('group_id'));
            $this->assertIsString($item->offsetGet('group_id'));

            $this->assertTrue($item->offsetExists('available'));
            $this->assertIsString($item->offsetGet('available'));

            $this->assertTrue($item->offsetExists('in_stock'));
            $this->assertIsString($item->offsetGet('in_stock'));

            $this->assertTrue($item->offsetExists('url'));
            $this->assertIsString($item->offsetGet('url'));

            $this->assertTrue($item->offsetExists('price'));
            $this->assertIsString($item->offsetGet('price'));

            $this->assertTrue($item->offsetExists('currencyid'));
            $this->assertIsString($item->offsetGet('currencyid'));

            $this->assertTrue($item->offsetExists('categoryid'));
            $this->assertIsString($item->offsetGet('currencyid'));

            $this->assertTrue($item->offsetExists('picture'));
            $this->assertIsString($item->offsetGet('picture'));

            $this->assertTrue($item->offsetExists('vendorcode'));
            $this->assertIsString($item->offsetGet('vendorcode'));

            $this->assertTrue($item->offsetExists('vendor'));
            $this->assertIsString($item->offsetGet('vendor'));

            $this->assertTrue($item->offsetExists('name'));
            $this->assertIsString($item->offsetGet('name'));

            $this->assertTrue($item->offsetExists('name_ua'));
            $this->assertIsString($item->offsetGet('name_ua'));

            $this->assertTrue($item->offsetExists('description'));
            $this->assertIsString($item->offsetGet('description'));

            $this->assertTrue($item->offsetExists('description_ua'));
            $this->assertIsString($item->offsetGet('description_ua'));

            $this->assertTrue($item->offsetExists('params'));
            $this->assertIsArray($item->offsetGet('params'));

            $this->assertTrue($item->offsetExists('pictures'));
            $this->assertIsArray($item->offsetGet('pictures'));

            $this->assertNotNull($item);
            $this->assertInstanceOf(ProductEntity::class, $item);
        }
    }

    /**
     * @throws Exception
     */
    public function testGetProductItemParamsAsGeneratorAndString()
    {
         Config::set('buyme-parser-config.adapters', [
            'xml' => XMLReader::class,
        ]);

        $parser = new BuymeParser();

        $parser->open($this->xmlFile);

        foreach ($parser->getProducts() as $item) {
            $this->assertTrue($item->offsetExists('params'));
            $this->assertIsArray($item->offsetGet('params'));

            $params = $item->offsetGet('params');

            foreach ($params as $param) {
                $this->assertTrue(isset($param['value']));
                $this->assertIsString($param['value']);

                $this->assertTrue(isset($param['name']));
                $this->assertIsString($param['name']);
            }
        }
    }

    /**
     * @throws Exception
     */
    public function testGetProductItemPicturesAsGeneratorAndString()
    {
         Config::set('buyme-parser-config.adapters', [
            'xml' => XMLReader::class,
        ]);

        $parser = new BuymeParser();

        $parser->open($this->xmlFile);

        foreach ($parser->getProducts() as $item) {
            $this->assertTrue($item->offsetExists('pictures'));
            $this->assertIsArray($item->offsetGet('pictures'));

            $pictures = $item->offsetGet('pictures');

            foreach ($pictures as $picture) {
                $this->assertIsString($picture);
            }
        }
    }

    /**
     * @throws Exception
     */
    public function testGetProductItemAsProductEntityMethodToArray()
    {
        Config::set('buyme-parser-config.adapters', [
            'xml' => XMLReader::class,
        ]);

        $parser = new BuymeParser();

        $parser->open($this->xmlFile);

        foreach ($parser->getProducts() as $item) {
            $array = $item->toArray();

            $this->assertNotNull($array);
            $this->assertIsArray($array);
        }
    }

    /**
     * @throws Exception
     */
    public function testGetProductItemAsProductEntityMethodGetPictures()
    {
        Config::set('buyme-parser-config.adapters', [
            'xml' => XMLReader::class,
        ]);

        $parser = new BuymeParser();

        $parser->open($this->xmlFile);

        foreach ($parser->getProducts() as $item) {
            $pictures = $item->getPictures();

            $this->assertNotEmpty($pictures);
            $this->assertIsArray($pictures);

            $tempData = [
                "https://kiborg.com.ua/content/images/20/dzhhut-turniket-dlia-zsu-78528459121857_+141bf65117.jpg",
                "https://kiborg.com.ua/content/images/34/remin-trokhtochkovyi-dlia-ak-rpk-olyva-21322376457063_+c4530a1b01.jpg",
                "https://kiborg.com.ua/content/images/34/remin-trokhtochkovyi-dlia-ak-rpk-olyva-35754194624852_+fe09c51158.png",
                "https://kiborg.com.ua/content/images/34/remin-trokhtochkovyi-dlia-ak-rpk-olyva-58682690243141_+bee7e87efd.png",
                "https://kiborg.com.ua/content/images/34/remin-trokhtochkovyi-dlia-ak-rpk-olyva-11508243675364_+8204b20661.png",
                "https://kiborg.com.ua/content/images/34/remin-trokhtochkovyi-dlia-ak-rpk-olyva-12187600282425_+267e13fd11.png",
                "https://kiborg.com.ua/content/images/34/remin-trokhtochkovyi-dlia-ak-rpk-olyva-21947506931055_+8e82f6fc6c.png",
                "https://kiborg.com.ua/content/images/34/remin-trokhtochkovyi-dlia-ak-rpk-olyva-77482128892051_+07a4b8be61.png",
                "https://kiborg.com.ua/content/images/34/remin-trokhtochkovyi-dlia-ak-rpk-olyva-90914079145847_+c1561ce6d1.png",
                "https://kiborg.com.ua/content/images/34/remin-trokhtochkovyi-dlia-ak-rpk-olyva-71710092670612_+9f4078c2aa.png",
                "https://kiborg.com.ua/content/images/34/remin-trokhtochkovyi-dlia-ak-rpk-olyva-63371027784007_+05b6272245.png",
            ];

            $this->assertTrue(array_diff($tempData, $pictures) === array_diff($pictures, $tempData));
        }
    }

    /**
     * @throws Exception
     */
    public function testGetProductItemAsProductEntityMethodGetParams()
    {
        Config::set('buyme-parser-config.adapters', [
            'xml' => XMLReader::class,
        ]);

        $parser = new BuymeParser();

        $parser->open($this->xmlFile);

        foreach ($parser->getProducts() as $item) {
            $params = $item->getParams();

            $this->assertNotNull($params);
            $this->assertIsArray($params);

            $tempData = [
                [
                    "value" => "Чорний",
                    "name" => "Цвет"
                ]
            ];

            $compareArrayFirst = $this->array_diff_assoc_recursive($tempData, $params);
            $compareArraySecond = $this->array_diff_assoc_recursive($tempData, $params);

            $this->assertTrue($compareArrayFirst === $compareArraySecond);
        }
    }
}
