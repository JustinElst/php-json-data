# PHP JSON Data Accessors

[![Latest Stable Version](https://poser.pugx.org/remorhaz/php-json-data/v/stable)](https://packagist.org/packages/remorhaz/php-json-data)
[![License](https://poser.pugx.org/remorhaz/php-json-data/license)](https://packagist.org/packages/remorhaz/php-json-data)
[![Build Status](https://travis-ci.org/remorhaz/php-json-data.svg?branch=master)](https://travis-ci.org/remorhaz/php-json-data)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/remorhaz/php-json-data/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/remorhaz/php-json-data/?branch=master)
[![codecov](https://codecov.io/gh/remorhaz/php-json-data/branch/master/graph/badge.svg)](https://codecov.io/gh/remorhaz/php-json-data)

There are three classes of data accessors: _readers_, _selectors_ and _writers_.

* **Readers** implement `\Remorhaz\JSON\Data\ReaderInterface` that provides basic read-only access to associated data:
type checks, export, etc.
* **Selectors** extend readers and implement `\Remorhaz\JSON\Data\SelectorInterface` that allows to switch data by moving
internal cursor step by step deeper into JSON structures.
* **Writers** extend selectors and implement `\Remorhaz\JSON\Data\WriterInterface` that allows modification of currently
selected data.

You can use `Reference/Reader`, `Reference/Selector` and `Reference/Writer` classes that work with native PHP structures
by reference or implement your own accessors.
