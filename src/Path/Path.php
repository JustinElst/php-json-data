<?php

declare(strict_types=1);

namespace Remorhaz\JSON\Data\Path;

use function array_slice;
use function array_values;
use function count;

final class Path implements PathInterface
{
    /**
     * @var list<int|string>
     */
    private array $elements;

    public function __construct(int|string ...$elements)
    {
        $this->elements = array_values($elements);
    }

    public function copyWithElement(int $index): PathInterface
    {
        return new self(...$this->elements, ...[$index]);
    }

    public function copyWithProperty(string $name): PathInterface
    {
        return new self(...$this->elements, ...[$name]);
    }

    public function copyParent(): PathInterface
    {
        return empty($this->elements)
            ? throw new Exception\ParentNotFoundException($this)
            : new self(...array_slice($this->elements, 0, -1));
    }

    /**
     * @return list<int|string>
     */
    public function getElements(): array
    {
        return $this->elements;
    }

    public function equals(PathInterface $path): bool
    {
        return $path->getElements() === $this->elements;
    }

    public function contains(PathInterface $path): bool
    {
        $subPath = array_slice($path->getElements(), 0, count($this->elements));

        return $subPath === $this->elements;
    }
}
