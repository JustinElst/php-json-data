# PHP JSON Data Accessors

[![Latest Stable Version](https://poser.pugx.org/remorhaz/php-json-data/v/stable)](https://packagist.org/packages/remorhaz/php-json-data)
[![License](https://poser.pugx.org/remorhaz/php-json-data/license)](https://packagist.org/packages/remorhaz/php-json-data)
[![Build Status](https://travis-ci.org/remorhaz/php-json-data.svg?branch=master)](https://travis-ci.org/remorhaz/php-json-data)
[![Code Climate](https://codeclimate.com/github/remorhaz/php-json-data/badges/gpa.svg)](https://codeclimate.com/github/remorhaz/php-json-data)
[![Test Coverage](https://codeclimate.com/github/remorhaz/php-json-data/badges/coverage.svg)](https://codeclimate.com/github/remorhaz/php-json-data/coverage)

There are two classes of data accessors: _readers_ and _writers_, readers can be _selectable_ or not, writers are always
selectable.

You can use RawSelectableReader/RawSelectableWriter classes that work with native PHP structures or implement
your own accessors.
