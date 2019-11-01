# PHP JSON Data Accessors

[![Latest Stable Version](https://poser.pugx.org/remorhaz/php-json-data/v/stable)](https://packagist.org/packages/remorhaz/php-json-data)
[![License](https://poser.pugx.org/remorhaz/php-json-data/license)](https://packagist.org/packages/remorhaz/php-json-data)
[![Build Status](https://travis-ci.org/remorhaz/php-json-data.svg?branch=master)](https://travis-ci.org/remorhaz/php-json-data)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/remorhaz/php-json-data/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/remorhaz/php-json-data/?branch=master)
[![codecov](https://codecov.io/gh/remorhaz/php-json-data/branch/master/graph/badge.svg)](https://codecov.io/gh/remorhaz/php-json-data)
[![Mutation testing badge](https://badge.stryker-mutator.io/github.com/remorhaz/php-json-data/master)](https://stryker-mutator.github.io)

This library provides infrastructure for JSON documents processing.

## Requirements

- PHP 7.3+
- [JSON exception](https://www.php.net/manual/en/book.json.php) (ext-json)

## Node Values
`ValueInterface` can be used to represent JSON document or it's part. There is a set of descendant interfaces that provide access to typed values:

- `ScalarValueInterface`
- `ArrayValueInterface`
- `ObjectValueInterface`

`NodeValueInterface` includes information about value's path in the document, so it can be used to represent values that really exist in some JSON document. Calculated values that have no paths should implement basic `ValueInterface`. 

## Node Value Factories
This type of objects is intended to create node values from some source. This library includes two implementations of node value factories:

- `DecodedJson\NodeValueFactory` creates node value from raw PHP values (produced by `json_decode()` function or created manually).
- `EncodedJson\NodeValueFactory` creates node value directly from encoded JSON string.

### Example
```php
<?php

use Remorhaz\JSON\Data\Value\DecodedJson;
use Remorhaz\JSON\Data\Value\EncodedJson;

// Both values represent same JSON document:
$value1 = EncodedJson\NodeValueFactory::create()->createValue('{"a":"b"}');
$value2 = DecodedJson\NodeValueFactory::create()->createValue((object) ['a' => 'b']);
``` 

## Event Streams
JSON document can be represented as a stream of events. These events implement descendants of `EventInterface`. Such events can be emitted by any custom JSON parser, but this library also implements standard `ValueWalker` object that converts any `NodeValueInterface` to event stream.

Value walker is also able to use _mutations_ to alter the events. There're no standard mutations, you must implement `MutationInterface` by yourself.

You can also use `EventDecoder` object to convert event stream back to `NodeValueInterface`.

## Value exporters
Library includes a set of `ValueExporterInterface` implementations that allow to export `ValueInterface` to another representation:

- `ValueEncoder` converts value to JSON-encoded string.
- `ValueDecoder` converts value to raw PHP values.

## License

This library is licensed under the MIT License. Please see [LICENSE](./LICENSE) for more information.