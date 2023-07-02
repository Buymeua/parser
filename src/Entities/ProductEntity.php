<?php

declare(strict_types=1);

namespace Buyme\Parser\Entities;

use ArrayObject;

class ProductEntity extends ArrayObject
{
    public function getPrice(string $currency = 'UAH'): int
    {
        return 0;
    }

    public function getPictures(): array
    {
        $pictures = [];

        foreach ($this->offsetGet('pictures') as $picture) {
            $pictures[] = $picture;
        }

        return $pictures;
    }

    public function getParams(): array
    {
        $params = [];

        foreach ($this->offsetGet('params') as $param) {
            $params[] = $param;
        }

        return $params;
    }

    public function toArray(): array
    {
        $data = iterator_to_array($this);

        $data['id'] = $this->offsetGet('id');
        $data['offer_id'] = (int)$this->offsetGet('id');
        $data['available'] = $this->offsetExists('available') && (bool)$this->offsetGet('available');
        $data['pickup'] = $this->offsetExists('pickup') && (bool)$this->offsetGet('pickup');
        $data['group_id'] = $this->offsetExists('group_id') ? (int)$this->offsetGet('group_id') : (int)$this->offsetGet('id');
        $data['price'] = $this->offsetExists('price') ? (float)$this->offsetGet('price') : 0;
        $data['vendorCode'] = $this->offsetExists('vendorCode') ? $this->offsetGet('vendorCode') : '';
        $data['country_of_origin'] = $this->offsetExists('country_of_origin') ? $this->offsetGet('country_of_origin') : '';
        $data['currency'] = $this->offsetExists('currency') ? $this->offsetGet('currency') : 'UAH';
        $data['categoryld'] = $this->offsetExists('categoryld') ? $this->offsetGet('categoryld') : 'UAH';
        $data['name_ua'] = $this->offsetExists('name_ua') ? $this->offsetGet('name_ua') : '';
        $data['description_ua'] = $this->offsetExists('description_ua') ? $this->offsetGet('description_ua') : '';
        $data['params'] = $this->getParams();
        $data['pictures'] = $this->getPictures();

        return $data;
    }
}