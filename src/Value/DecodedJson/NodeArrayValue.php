<?php

declare(strict_types=1);

namespace Remorhaz\JSON\Data\Value\DecodedJson;

use Iterator;
use Remorhaz\JSON\Data\Value\ArrayValueInterface;
use Remorhaz\JSON\Data\Path\PathInterface;
use Remorhaz\JSON\Data\Value\NodeValueInterface;

use function is_int;

final class NodeArrayValue implements NodeValueInterface, ArrayValueInterface
{
    public function __construct(
        private array $data,
        private PathInterface $path,
        private NodeValueFactoryInterface $valueFactory
    ) {
    }

    /**
     * @return Iterator<int, NodeValueInterface>
     */
    public function createChildIterator(): Iterator
    {
        $validIndex = 0;
        /** @psalm-var mixed $element */
        foreach ($this->data as $index => $element) {
            if (!is_int($index) || $index != $validIndex++) {
                throw new Exception\InvalidElementKeyException($index, $this->path);
            }
            yield $index => $this
                ->valueFactory
                ->createValue($element, $this->path->copyWithElement($index));
        }
    }

    public function getPath(): PathInterface
    {
        return $this->path;
    }
}
