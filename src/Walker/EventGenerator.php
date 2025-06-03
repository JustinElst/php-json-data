<?php

declare(strict_types=1);

namespace Remorhaz\JSON\Data\Walker;

use Iterator;
use Remorhaz\JSON\Data\Event\AfterArrayEvent;
use Remorhaz\JSON\Data\Event\AfterElementEvent;
use Remorhaz\JSON\Data\Event\AfterElementEventInterface;
use Remorhaz\JSON\Data\Event\AfterObjectEvent;
use Remorhaz\JSON\Data\Event\AfterPropertyEvent;
use Remorhaz\JSON\Data\Event\AfterPropertyEventInterface;
use Remorhaz\JSON\Data\Event\BeforeArrayEvent;
use Remorhaz\JSON\Data\Event\BeforeElementEvent;
use Remorhaz\JSON\Data\Event\BeforeElementEventInterface;
use Remorhaz\JSON\Data\Event\BeforeObjectEvent;
use Remorhaz\JSON\Data\Event\BeforePropertyEvent;
use Remorhaz\JSON\Data\Event\BeforePropertyEventInterface;
use Remorhaz\JSON\Data\Event\EventInterface;
use Remorhaz\JSON\Data\Event\ScalarEvent;
use Remorhaz\JSON\Data\Path\PathInterface;
use Remorhaz\JSON\Data\Value\ArrayValueInterface;
use Remorhaz\JSON\Data\Value\NodeValueInterface;
use Remorhaz\JSON\Data\Value\ObjectValueInterface;
use Remorhaz\JSON\Data\Value\ScalarValueInterface;

final class EventGenerator
{
    /**
     * @var list<EventInterface|NodeValueInterface>
     */
    private array $stack;

    public function __construct(
        NodeValueInterface $value,
        private PathInterface $path,
    ) {
        $this->stack = [$value];
    }

    /**
     * @return Iterator<EventInterface>
     */
    public function __invoke(): Iterator
    {
        while (true) {
            if ($this->stack === []) {
                return;
            }

            $entity = array_pop($this->stack);
            match (true) {
                $entity instanceof EventInterface => yield from $this->onEvent($entity),
                $entity instanceof ScalarValueInterface => yield from $this->onScalarValue($entity),
                $entity instanceof ArrayValueInterface => yield from $this->onArrayValue($entity),
                $entity instanceof ObjectValueInterface => yield from $this->onObjectValue($entity),
                default => throw new Exception\UnexpectedEntityException($entity),
            };
        }
    }

    /**
     * @return Iterator<EventInterface>
     */
    private function onEvent(EventInterface $event): Iterator
    {
        switch (true) {
            case $event instanceof BeforeElementEventInterface:
                $this->path = $this
                    ->path
                    ->copyWithElement($event->getIndex());
                break;

            case $event instanceof BeforePropertyEventInterface:
                $this->path = $this
                    ->path
                    ->copyWithProperty($event->getName());
                break;

            case $event instanceof AfterElementEventInterface:
            case $event instanceof AfterPropertyEventInterface:
                $this->path = $this
                    ->path
                    ->copyParent();
                break;
        }

        yield $event;
    }

    /**
     * @return Iterator<EventInterface>
     */
    private function onScalarValue(ScalarValueInterface $value): Iterator
    {
        yield new ScalarEvent($value->getData(), $this->path);
    }

    /**
     * @return Iterator<EventInterface>
     */
    private function onArrayValue(ArrayValueInterface $value): Iterator
    {
        $localStack = [];
        foreach ($value->createChildIterator() as $index => $child) {
            $elementPath = $this
                ->path
                ->copyWithElement($index);
            $localStack[] = new BeforeElementEvent($index, $elementPath);
            $localStack[] = $child;
            $localStack[] = new AfterElementEvent($index, $elementPath);
        }

        array_push(
            $this->stack,
            new AfterArrayEvent($this->path),
            ...array_reverse($localStack),
        );
        yield new BeforeArrayEvent($this->path);
    }

    /**
     * @return Iterator<EventInterface>
     */
    private function onObjectValue(ObjectValueInterface $value): Iterator
    {
        $localStack = [];
        foreach ($value->createChildIterator() as $name => $child) {
            $elementPath = $this
                ->path
                ->copyWithProperty($name);
            $localStack[] = new BeforePropertyEvent($name, $elementPath);
            $localStack[] = $child;
            $localStack[] = new AfterPropertyEvent($name, $elementPath);
        }

        array_push(
            $this->stack,
            new AfterObjectEvent($this->path),
            ...array_reverse($localStack),
        );
        yield new BeforeObjectEvent($this->path);
    }
}
