<?php

namespace Remorhaz\JSON\Data;

interface WriterInterface extends SelectorInterface
{

    /**
     * Copies data and returns a new instance of writer object associated with the copy. Throws an exception if data
     * is not set.
     *
     * @return WriterInterface
     */
    public function getAsWriter(): self;

    /**
     * @param ReaderInterface $source
     * @return $this
     */
    public function replaceData(ReaderInterface $source);


    /**
     * @param ReaderInterface $source
     * @return $this
     */
    public function insertProperty(ReaderInterface $source);


    /**
     * @return $this
     */
    public function removeProperty();


    /**
     * @param ReaderInterface $source
     * @return $this
     */
    public function appendElement(ReaderInterface $source);


    /**
     * @param ReaderInterface $source
     * @return $this
     */
    public function insertElement(ReaderInterface $source);


    /**
     * @return $this
     */
    public function removeElement();
}
