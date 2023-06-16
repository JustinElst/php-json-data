<?php

declare(strict_types=1);

namespace Remorhaz\JSON\Data\Export;

use Remorhaz\JSON\Data\Value\ArrayValueInterface;
use Remorhaz\JSON\Data\Value\ObjectValueInterface;
use Remorhaz\JSON\Data\Value\ScalarValueInterface;
use Remorhaz\JSON\Data\Value\ValueInterface;

final class ValueDecoder implements ValueDecoderInterface
{
    public function exportValue(ValueInterface $value): mixed
    {
        return match (true) {
            $value instanceof ScalarValueInterface => $value->getData(),
            $value instanceof ArrayValueInterface => $this->exportArrayValue($value),
            $value instanceof ObjectValueInterface => $this->exportObjectValue($value),
            default => throw new Exception\UnexpectedValueException($value),
        };
    }

    private function exportArrayValue(ArrayValueInterface $value): array
    {
        $result = [];
        foreach ($value->createChildIterator() as $index => $element) {
            /** @psalm-var mixed */
            $result[$index] = $this->exportValue($element);
        }

        return $result;
    }

    private function exportObjectValue(ObjectValueInterface $value): object
    {
        $result = (object) [];
        foreach ($value->createChildIterator() as $name => $property) {
            $result->{$name} = $this->exportValue($property);
        }

        return $result;
    }
}
