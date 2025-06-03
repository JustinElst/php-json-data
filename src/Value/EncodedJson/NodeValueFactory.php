<?php

declare(strict_types=1);

namespace Remorhaz\JSON\Data\Value\EncodedJson;

use Remorhaz\JSON\Data\Path\PathInterface;
use Remorhaz\JSON\Data\Value\DecodedJson;
use Remorhaz\JSON\Data\Value\NodeValueInterface;
use Throwable;

use function json_decode;

use const JSON_THROW_ON_ERROR;

final readonly class NodeValueFactory implements NodeValueFactoryInterface
{
    public static function create(): NodeValueFactoryInterface
    {
        return new self(DecodedJson\NodeValueFactory::create());
    }

    public function __construct(
        private DecodedJson\NodeValueFactoryInterface $decodedJsonNodeValueFactory,
    ) {
    }

    #[\Override]
    public function createValue(string $json, ?PathInterface $path = null): NodeValueInterface
    {
        try {
            /** @psalm-var mixed $decodedData */
            $decodedData = json_decode($json, false, 512, JSON_THROW_ON_ERROR);
        } catch (Throwable $throwable) {
            throw new Exception\JsonNotDecodedException($json, $throwable);
        }

        return $this
            ->decodedJsonNodeValueFactory
            ->createValue($decodedData, $path);
    }
}
