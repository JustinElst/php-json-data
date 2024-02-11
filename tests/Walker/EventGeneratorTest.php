<?php

declare(strict_types=1);

namespace Remorhaz\JSON\Data\Test\Walker;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Remorhaz\JSON\Data\Event\AfterArrayEvent;
use Remorhaz\JSON\Data\Event\AfterElementEvent;
use Remorhaz\JSON\Data\Event\AfterObjectEvent;
use Remorhaz\JSON\Data\Event\AfterPropertyEvent;
use Remorhaz\JSON\Data\Event\BeforeArrayEvent;
use Remorhaz\JSON\Data\Event\BeforeElementEvent;
use Remorhaz\JSON\Data\Event\BeforeObjectEvent;
use Remorhaz\JSON\Data\Event\BeforePropertyEvent;
use Remorhaz\JSON\Data\Event\ElementEventInterface;
use Remorhaz\JSON\Data\Event\EventInterface;
use Remorhaz\JSON\Data\Event\PropertyEventInterface;
use Remorhaz\JSON\Data\Event\ScalarEvent;
use Remorhaz\JSON\Data\Event\ScalarEventInterface;
use Remorhaz\JSON\Data\Path\Path;
use Remorhaz\JSON\Data\Value\DecodedJson\NodeValueFactory;
use Remorhaz\JSON\Data\Value\NodeValueInterface;
use Remorhaz\JSON\Data\Walker\EventGenerator;
use Remorhaz\JSON\Data\Walker\Exception\UnexpectedEntityException;

use function array_map;
use function iterator_to_array;

#[CoversClass(EventGenerator::class)]
class EventGeneratorTest extends TestCase
{
    /**
     * @param mixed       $value
     * @param list<array> $expectedEvents
     */
    #[DataProvider('providerValueEvents')]
    public function testInvoke_ConstructedWithValue_IteratesMatchingEvents(mixed $value, array $expectedEvents): void
    {
        $nodeFactory = new NodeValueFactory();
        $value = $nodeFactory->createValue($value, new Path(1));
        $generator = new EventGenerator($value, new Path(2));
        $events = iterator_to_array($generator(), false);
        self::assertSame($expectedEvents, $this->exportEvents(...$events));
    }

    /**
     * @return iterable<string, array{mixed, list<array>}>
     */
    public static function providerValueEvents(): iterable
    {
        return [
            'Scalar value' => [
                'a',
                [
                    [
                        'class' => ScalarEvent::class,
                        'path' => [2],
                        'data' => 'a',
                    ],
                ],
            ],
            'Array with two scalars' => [
                ['a', 'b'],
                [
                    [
                        'class' => BeforeArrayEvent::class,
                        'path' => [2],
                    ],
                    [
                        'class' => BeforeElementEvent::class,
                        'path' => [2, 0],
                        'index' => 0,
                    ],
                    [
                        'class' => ScalarEvent::class,
                        'path' => [2, 0],
                        'data' => 'a',
                    ],
                    [
                        'class' => AfterElementEvent::class,
                        'path' => [2, 0],
                        'index' => 0,
                    ],
                    [
                        'class' => BeforeElementEvent::class,
                        'path' => [2, 1],
                        'index' => 1,
                    ],
                    [
                        'class' => ScalarEvent::class,
                        'path' => [2, 1],
                        'data' => 'b',
                    ],
                    [
                        'class' => AfterElementEvent::class,
                        'path' => [2, 1],
                        'index' => 1,
                    ],
                    [
                        'class' => AfterArrayEvent::class,
                        'path' => [2],
                    ],
                ],
            ],
            'Object with two properties' => [
                (object) ['a' => 'b', 'c' => 'd'],
                [
                    [
                        'class' => BeforeObjectEvent::class,
                        'path' => [2],
                    ],
                    [
                        'class' => BeforePropertyEvent::class,
                        'path' => [2, 'a'],
                        'name' => 'a',
                    ],
                    [
                        'class' => ScalarEvent::class,
                        'path' => [2, 'a'],
                        'data' => 'b',
                    ],
                    [
                        'class' => AfterPropertyEvent::class,
                        'path' => [2, 'a'],
                        'name' => 'a',
                    ],
                    [
                        'class' => BeforePropertyEvent::class,
                        'path' => [2, 'c'],
                        'name' => 'c',
                    ],
                    [
                        'class' => ScalarEvent::class,
                        'path' => [2, 'c'],
                        'data' => 'd',
                    ],
                    [
                        'class' => AfterPropertyEvent::class,
                        'path' => [2, 'c'],
                        'name' => 'c',
                    ],
                    [
                        'class' => AfterObjectEvent::class,
                        'path' => [2],
                    ],
                ],
            ],
        ];
    }

    private function exportEvents(EventInterface ...$events): array
    {
        return array_map($this->exportEvent(...), $events);
    }

    private function exportEvent(EventInterface $event): array
    {
        return [
            'class' => $event::class,
            'path' => $event->getPath()->getElements(),
            ...match (true) {
                $event instanceof ScalarEventInterface => ['data' => $event->getData()],
                $event instanceof ElementEventInterface => ['index' => $event->getIndex()],
                $event instanceof PropertyEventInterface => ['name' => $event->getName()],
                default => [],
            },
        ];
    }

    public function testInvoke_ConstructedWithInvalidValue_ThrowsException(): void
    {
        $value = self::createStub(NodeValueInterface::class);
        $generator = new EventGenerator($value, new Path());
        $this->expectException(UnexpectedEntityException::class);
        iterator_to_array($generator());
    }
}
