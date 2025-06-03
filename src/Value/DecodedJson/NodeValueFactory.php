<?php

declare(strict_types=1);

namespace Remorhaz\JSON\Data\Value\DecodedJson;

use Remorhaz\JSON\Data\Path\Path;
use Remorhaz\JSON\Data\Value\NodeValueInterface;
use Remorhaz\JSON\Data\Path\PathInterface;

use function is_array;
use function is_object;
use function is_scalar;

final class NodeValueFactory implements NodeValueFactoryInterface
{
    public static function create(): NodeValueFactoryInterface
    {
        return new self();
    }

    /**
     * {@inheritDoc}
     *
     * @param (null|object|scalar)[]|null|object|scalar $data
     *
     * @psalm-param array<int|string, null|object|scalar>|null|object|scalar $data
     */
    #[\Override]
    public function createValue(mixed $data, ?PathInterface $path = null): NodeValueInterface
    {
        $path ??= new Path();

        return match (true) {
            null === $data,
            is_scalar($data) => new NodeScalarValue($data, $path),
            is_array($data) => new NodeArrayValue($data, $path, $this),
            is_object($data) => new NodeObjectValue($data, $path, $this),
            default => throw new Exception\InvalidNodeDataException($data, $path),
        };
    }
}
