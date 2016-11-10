<?php

namespace Remorhaz\JSON\Data;

interface ReaderInterface {

    /**
     * Copies data and returns a new instance of reader object associated with the copy. Throws an exception if data
     * is not set.
     *
     * @return ReaderInterface
     */
    public function getAsReader(): self;

    /**
     * Returns associated data in for of PHP structure, like json_decode(). Throws an exception if data is not set.
     *
     * @return mixed
     * @throws Exception
     */
    public function getAsStruct();

    /**
     * If associated data is set and has a string type then returns it, otherwise throws an exception.
     *
     * @return string
     * @throws Exception
     */
    public function getAsString(): string;

    /**
     * Checks if data is set and is equal to the associated data of the given reader. If not then throws an exception.
     *
     * @param ReaderInterface $value
     * @return $this
     */
    public function test(self $value);

    /**
     * Returns TRUE if data is set.
     *
     * @return bool
     */
    public function hasData(): bool;


    /**
     * Returns TRUE if data is an array. Throws an exception if data is not set.
     *
     * @return bool
     * @throws Exception
     */
    public function isArray(): bool;


    /**
     * Returns TRUE if data is an object. Throws an exception if data is not set.
     *
     * @return bool
     * @throws Exception
     */
    public function isObject(): bool;


    /**
     * Returns TRUE if data is a string. Throws an exception if data is not set.
     *
     * @return bool
     * @throws Exception
     */
    public function isString(): bool;


    /**
     * Returns TRUE if data is a number. Throws an exception if data is not set.
     *
     * @return bool
     * @throws Exception
     */
    public function isNumber(): bool;


    /**
     * Returns TRUE if data is a boolean. Throws an exception if data is not set.
     *
     * @return bool
     * @throws Exception
     */
    public function isBoolean(): bool;

    /**
     * Returns TRUE if data is a NULL. Throws an exception if data is not set.
     *
     * @return bool
     * @throws Exception
     */
    public function isNull(): bool;


    /**
     * If associated data is set and is an array then returns elements number, otherwise throws an exception.
     *
     * @return int
     * @throws Exception
     */
    public function getElementCount(): int;
}
