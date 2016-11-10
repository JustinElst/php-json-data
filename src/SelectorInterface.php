<?php

namespace Remorhaz\JSON\Data;

interface SelectorInterface extends ReaderInterface
{


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
