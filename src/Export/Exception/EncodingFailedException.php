<?php
declare(strict_types=1);

namespace Remorhaz\JSON\Data\Export\Exception;

use LogicException;
use Remorhaz\JSON\Data\Value\DataAwareInterface;
use Throwable;

final class EncodingFailedException extends LogicException implements ExceptionInterface, DataAwareInterface
{

    private $data;

    public function __construct($data, Throwable $previous = null)
    {
        $this->data = $data;
        parent::__construct("Failed to encode data to JSON", 0, $previous);
    }

    public function getData()
    {
        return $this->data;
    }
}
