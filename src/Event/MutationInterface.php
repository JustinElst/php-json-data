<?php
declare(strict_types=1);

namespace Remorhaz\JSON\Data\Event;

interface MutationInterface
{

    public function __invoke(EventInterface $event);
}