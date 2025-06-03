<?php

declare(strict_types=1);

namespace Remorhaz\JSON\Data\Value\DecodedJson;

use Iterator;
use Remorhaz\JSON\Data\Value\ObjectValueInterface;
use Remorhaz\JSON\Data\Path\PathInterface;
use Remorhaz\JSON\Data\Value\NodeValueInterface;

final readonly class NodeObjectValue implements NodeValueInterface, ObjectValueInterface
{
    public function __construct(
        private object $data,
        private PathInterface $path,
        private NodeValueFactoryInterface $valueFactory,
    ) {
    }

    /**
     * @return Iterator<string, NodeValueInterface>
     */
    #[\Override]
    public function createChildIterator(): Iterator
    {
        /** @psalm-var mixed $property */
        foreach (get_object_vars($this->data) as $name => $property) {
            $stringName = $name;
            yield $stringName => $this
                ->valueFactory
                ->createValue($property, $this->path->copyWithProperty($stringName));
        }
    }

    #[\Override]
    public function getPath(): PathInterface
    {
        return $this->path;
    }
}
