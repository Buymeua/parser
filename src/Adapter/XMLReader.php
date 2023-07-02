<?php

declare(strict_types=1);

namespace Buyme\Parser\Adapter;

use Buyme\Parser\Entities\CategoryEntity;
use Buyme\Parser\Entities\ProductEntity;
use Buyme\Parser\Entities\CurrencyEntity;
use Closure;
use Iterator;

class XMLReader implements ParserInterface
{
    private \XMLReader $xml;
    private string $filename;

    public function getCategories(): array
    {
        $returnArr = [];
        $this->moveToStart();
        $xml = $this->xml;

        while ($xml->read()) {
            if ($xml->nodeType == \XMLReader::ELEMENT && $xml->name == 'categories') {
                while ($xml->read() && $xml->name != 'categories') {
                    if ($xml->nodeType == \XMLReader::ELEMENT) {
                        $arr = [];

                        if ($xml->hasAttributes) {

                            while ($xml->moveToNextAttribute()) {
                                $arr[strtolower($xml->name)] = $xml->value;
                            }
                        }

                        $xml->read();
                        $arr['value'] = $xml->value;
                        $returnArr[] = new CategoryEntity($arr);

                        unset($arr);
                    }
                }
            }
        }

        return $returnArr;
    }
    public function getCurrencies(): array
    {

        $returnArr = [];
        $this->moveToStart();
        $xml = $this->xml;

        while ($xml->read()) {
            if ($xml->nodeType == \XMLReader::ELEMENT && $xml->name == 'currencies') {
                while ($xml->read() && $xml->name != 'currencies') {
                    if ($xml->nodeType == \XMLReader::ELEMENT) {
                        $arr = [];

                        if ($xml->hasAttributes) {

                            while ($xml->moveToNextAttribute()) {
                                $arr[strtolower($xml->name)] = $xml->value;
                            }
                        }

                        $xml->read();
                        $arr['value'] = $xml->value;
                        $returnArr[] = new CurrencyEntity($arr);

                        unset($arr);
                    }
                }
            }
        }

        return $returnArr;
    }
    public function getProducts(Closure $filter = null): Iterator
    {
        $this->moveToStart();
        $xml = $this->xml;

        while ($xml->read()) {
            if ($xml->nodeType == \XMLReader::ELEMENT && $xml->name == 'offers') {
                while ($xml->read() && $xml->name != 'offers') {
                    if ($xml->nodeType == \XMLReader::ELEMENT && $xml->name == 'offer') {
                        $arr = $this->getElementAttributes($xml);

                        while ($xml->read() && $xml->name != 'offer') {
                            if ($xml->nodeType == \XMLReader::ELEMENT) {
                                $name = mb_strtolower($xml->name);

                                if ($name == 'param') {
                                    $tmpArr = ['name' => $xml->getAttribute('name')];
                                }

                                $xml->read();

                                if ($name == 'param') {
                                    $arr['params'][] = array_merge(['value' => $xml->value], $tmpArr);
                                } else {
                                    if ($name == 'picture') {
                                        $arr['pictures'][] = $xml->value;
                                    }

                                    $arr[$name] = $xml->value;
                                }
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
            }
        }
    }
    private function getElementAttributes(\XMLReader $element): array
    {
        $returnArr = [];

        if ($element->hasAttributes) {
            while ($element->moveToNextAttribute()) {
                $returnArr[mb_strtolower($element->name)] = $element->value;
            }
        }

        return $returnArr;
    }
    /**
     * @param string $xml
     * @return bool
     */
    public function open(string $xml): bool
    {
        $this->filename = $xml;
        $this->xml = new \XMLReader();

        return $this->xml->open($xml);
    }
    public function countProducts(Closure $filter = null): int
    {
        $returnValue = 0;

        foreach ($this->getProducts($filter) as $el) {
            $returnValue++;
        }

        return $returnValue;
    }
    private function moveToStart()
    {
        $this->xml->close();

        return $this->xml->open($this->filename);
    }
}