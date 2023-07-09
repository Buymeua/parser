<?php

declare(strict_types=1);

namespace Buyme\Parser\Adapter;

use Buyme\Parser\Entities\CategoryEntity;
use Buyme\Parser\Entities\ProductEntity;
use Buyme\Parser\Entities\CurrencyEntity;
use Closure;
use Exception;
use Generator;
use SimpleXMLElement;

class SimpleXML implements ParserInterface
{
    private SimpleXMLElement $xml;

    public function getCategories(): Generator
    {
        foreach ($this->xml->shop->categories->category as $category) {
            $arr = array_merge(
                ['value' => (string)$category],
                $this->getElementAttributes($category)
            );

            yield new CategoryEntity($arr);
        }
    }

    public function getProducts(Closure $filter = null): Generator
    {
        foreach ($this->xml->shop->offers->offer as $product) {
            $arr = $this->getElementAttributes($product);
            $arr['params'] = $this->parseParamsFromElement($product);
            $arr['pictures'] = $this->parsePicturesFromElement($product);

            foreach ($product->children() as $element) {
                $name = mb_strtolower($element->getName());

                if ($name != 'param') {
                    $arr[$name] = (string)$element;
                }
            }

            $returnValue = new ProductEntity($arr);

            if (!is_null($filter)) {
                if ($filter($returnValue)) {
                    yield $returnValue;
                }
            } else {
                yield $returnValue;
            }
        }
    }

    public function getCurrencies(): Generator
    {
        foreach ($this->xml->shop->currencies->currency as $category) {
            $arr = array_merge(
                ['value' => (string)$category],
                $this->getElementAttributes($category)
            );

            yield new CurrencyEntity($arr);
        }
    }

    public function countProducts(Closure $filter = null): int
    {
        $returnValue = 0;

        foreach ($this->getProducts($filter) as $el) {
            $returnValue++;
        }

        return $returnValue;
    }

    /**
     * @throws Exception
     */
    public function open(string $xml): bool
    {
        $this->xml = simplexml_load_file($xml);

        if (is_null($this->xml->shop->offers)) {
            throw new Exception(__("buyme-parser-lang::buyme_parser.errors.offers_is_empty"));
        }

        return (bool)$this->xml;
    }

    private function parseParamsFromElement(SimpleXMLElement $offer): Generator
    {
        foreach ($offer->children() as $element) {
            if (mb_strtolower($element->getName()) == 'param') {
                yield array_merge(
                    ['value' => (string)$element],
                    $this->getElementAttributes($element)
                );
            }
        }
    }

    private function parsePicturesFromElement(SimpleXMLElement $offer): Generator
    {
        foreach ($offer->children() as $element) {
            if (mb_strtolower($element->getName()) == 'picture') {
                yield (string)$element;
            }
        }
    }

    private function getElementAttributes(SimpleXMLElement $element): array
    {
        $returnArr = [];

        foreach ($element->attributes() as $attrName => $attrValue) {
            $returnArr[strtolower($attrName)] = (string)$attrValue;
        }

        return $returnArr;
    }
}