<?php

declare(strict_types=1);

namespace Remorhaz\JSON\Data\Value\DecodedJson;

use Iterator;
use Remorhaz\JSON\Data\Value\ObjectValueInterface;
use Remorhaz\JSON\Data\Path\PathInterface;
use Remorhaz\JSON\Data\Value\NodeValueInterface;

final class NodeObjectValue implements NodeValueInterface, ObjectValueInterface
{
    public function __construct(
        private readonly object $data,
        private readonly PathInterface $path,
        private readonly NodeValueFactoryInterface $valueFactory,
    ) {
    }

    /**
     * @return Iterator<string, NodeValueInterface>
     */
    public function createChildIterator(): Iterator
    {
        /** @psalm-var mixed $property */
        foreach (get_object_vars($this->data) as $name => $property) {
            /** @psalm-suppress RedundantCast */
            $stringName = (string) $name;
            yield $stringName => $this
                ->valueFactory
                ->createValue($property, $this->path->copyWithProperty($stringName));
        }
    }

    public function getPath(): PathInterface
    {
        return $this->path;
    }
}
