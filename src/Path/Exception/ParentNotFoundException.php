<?php
declare(strict_types=1);

namespace Remorhaz\JSON\Data\Path\Exception;

use LogicException;
use Remorhaz\JSON\Data\Path\PathAwareInterface;
use Remorhaz\JSON\Data\Path\PathInterface;
use Throwable;
use function implode;

final class ParentNotFoundException extends LogicException implements ExceptionInterface, PathAwareInterface
{

    private $path;

    public function __construct(PathInterface $path, Throwable $previous = null)
    {
        $this->path = $path;
        parent::__construct("Parent not found in path {$this->buildPath()}", 0, $previous);
    }

    public function getPath(): PathInterface
    {
        return $this->path;
    }

    private function buildPath(): string
    {
        return '/' . implode('/', $this->path->getElements());
    }
}
