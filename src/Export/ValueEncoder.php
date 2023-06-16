<?php

declare(strict_types=1);

namespace Remorhaz\JSON\Data\Export;

use Remorhaz\JSON\Data\Value\ValueInterface;
use Throwable;

use function json_encode;

use const JSON_THROW_ON_ERROR;
use const JSON_UNESCAPED_SLASHES;
use const JSON_UNESCAPED_UNICODE;

/**
 * @todo Don't use decoder
 */
final class ValueEncoder implements ValueEncoderInterface
{
    public function __construct(
        private ValueDecoderInterface $decoder,
    ) {
    }

    public function exportValue(ValueInterface $value): string
    {
        /** @psalm-var mixed $decodedValue */
        $decodedValue = $this
            ->decoder
            ->exportValue($value);
        try {
            return json_encode(
                $decodedValue,
                JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR,
            );
        } catch (Throwable $e) {
            throw new Exception\EncodingFailedException($decodedValue, $e);
        }
    }
}
