<?php

declare(strict_types=1);

return [
    /*
    |------------------------------------------------------------------------------------------------------------
    | The package will use this mapping_parser for detect what adapter need connect to parse file by extension.
    |------------------------------------------------------------------------------------------------------------
    */
    "adapters" => [
        'xml' => \Buyme\Parser\Adapter\SimpleXML::class,
        'yml' => \Buyme\Parser\Adapter\XMLReader::class,
    ],
];
