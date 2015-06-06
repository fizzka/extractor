# extractor
html extraction library, based on [SimpleXML](http://php.net/manual/en/book.simplexml.php) &amp; [nokogiri](https://github.com/olamedia/nokogiri) XpathSubquery.php

[![Build Status](https://travis-ci.org/fizzka/extractor.svg)](https://travis-ci.org/fizzka/extractor)

## Benefits
* Simple
* Minimal code
* Fast
* Query results are `SimpleXMLElement` instances
* Supports nested css/xpath queries

## Installation
```shell
#Using packagist:
composer require 'fizzka/extractor'
```

## Basic Usage
```php
<?php
require_once 'vendor/autoload.php';

$html = gzdecode(file_get_contents('http://habrahabr.ru/'));

$ex = Extractor::fromHtml($html);
var_dump($ex->get('a.habracut'));
```

## Advanced Usage
```php
echo $ex->cssPathFirst('div.post')->xpathFirst('.//@href');
```

## Testing
Just run `phpunit` from the top of project

## Contribute
Feel free to use & contribute ;)

## License
MIT
