<?php

namespace Remorhaz\JSON\Data;

interface SelectorInterface extends ReaderInterface
{

    /**
     * Copies data and returns a new instance of selector object associated with the copy. Throws an exception if data
     * is not set.
     *
     * @return SelectorInterface
     */
    public function getAsSelector(): self;

    /**
     * @return $this
     */
    public function selectRoot();


    /**
     * @param string $name
     * @return $this
     */
    public function selectProperty(string $name);


    /**
     * @param int $index
     * @return $this
     */
    public function selectElement(int $index);


    /**
     * @return $this
     */
    public function selectNewElement();


    public function isProperty(): bool;


    public function isElement(): bool;


    public function isNewElement(): bool;
}
