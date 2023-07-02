# Package for work with Any files Adapter and can parse his

[![Latest Stable Version](https://poser.pugx.org/buymeua/parser/v/stable)](https://packagist.org/packages/buymeua/parser)
[![Total Downloads](https://poser.pugx.org/buymeua/parser/downloads)](https://packagist.org/packages/buymeua/parser)
[![Tests](https://github.com/buymeua/parser/actions/workflows/action.yml/badge.svg?branch=master)](https://github.com/buymeua/parser/actions)
[![Laravel](https://img.shields.io/badge/Laravel-FF2D20?logo=laravel&logoColor=white)](https://github.com/https://github.com/Buymeua/parser)
[![Made with GH Actions](https://img.shields.io/badge/CI-GitHub_Actions-blue?logo=github-actions&logoColor=white)](https://github.com/features/actions "Go to GitHub Actions homepage")
[![Fruitcake](https://img.shields.io/badge/Powered%20By-Splx-b2bc35.svg)](https://splx-rust.ml)

This package can parse files with any format with custom adapters!

## Installation

- Package requires php-xmlrpc and php-mbstring (but need check before install maybe you have this packages on the your server)

```bash
sudo apt-get install php-xmlrpc php-mbstring
```

- You can install this package via composer using this command:

```bash
composer require buymeua/parser
```

- The package will automatically register itself.

- You can publish the configs file using the following command:

```bash
php artisan vendor:publish --provider="Buyme\Parser\BuymeParserServiceProvider" --tag=buyme-parser-config
```

This will create the package's config file called `buyme_parser.php` in the `config` directory. These are the contents of the published config file:

```php
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
```

- You can publish the lang file using the following command:

```bash
php artisan vendor:publish --provider="Buyme\Parser\BuymeParserServiceProvider" --tag=buyme-parser-lang
```


## How to use

- After setting up the config file values you are all set to use the following methods:

- First you need to initialize an instance of the BuymeParser.php class using Facade or direct connection of the BuymeParser class:

 Facade
```php
use Buyme\Parser\Facades\BuymeParser;
```
 Direct
```php
use Buyme\Parser\BuymeParser;
```

 Use Container

```php
$byml = app(BuymeParser::class);
```
 OR Use ID
```php
public function __construct(private BuymeParser $buymeParser)
{
}
```

 By URL
```php
$this->buymeParser->open('https://example.com/file.yml');
```
 By Local Path
```php
$this->buymeParser->open('storage/xml/file.yml');
```

```php
$categories = $this->buymeParser->getCategories();
$currencies = $this->buymeParser->getCurrencies();
$products = $this->buymeParser->getProducts();
```

## Testing

You can run the tests with:

```bash
vendor/bin/phpunit
```

## License

The MIT License (MIT). Please see the [License File](LICENSE.md) for more information.