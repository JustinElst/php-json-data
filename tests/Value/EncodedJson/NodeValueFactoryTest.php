<?php
declare(strict_types=1);

namespace Remorhaz\JSON\Data\Test\Value\EncodedJson;

use PHPUnit\Framework\TestCase;
use Remorhaz\JSON\Data\Path\Path;
use Remorhaz\JSON\Data\Value\DecodedJson\NodeValueFactoryInterface;
use Remorhaz\JSON\Data\Value\EncodedJson\Exception\JsonNotDecodedException;
use Remorhaz\JSON\Data\Value\EncodedJson\NodeValueFactory;
use Remorhaz\JSON\Data\Value\NodeValueInterface;

/**
 * @covers \Remorhaz\JSON\Data\Value\EncodedJson\NodeValueFactory
 */
class NodeValueFactoryTest extends TestCase
{

    public function testCreate_Always_ReturnsNodeValueInstance(): void
    {
        self::assertInstanceOf(NodeValueFactory::class, NodeValueFactory::create());
    }

    public function testCreateValue_InvalidJson_ThrowsException(): void
    {
        $factory = NodeValueFactory::create();
        $this->expectException(JsonNotDecodedException::class);
        $factory->createValue('a');
    }

    public function testCreateValue_ValidJsonNoPath_PassesDecodedJsonAndNullToDecodedJsonFactory(): void
    {
        $decodedJsonFactory = $this->createMock(NodeValueFactoryInterface::class);
        $encodedJsonFactory = new NodeValueFactory($decodedJsonFactory);

        $decodedJsonFactory
            ->expects(self::once())
            ->method('createValue')
            ->with([1], null);
        $encodedJsonFactory->createValue('[1]');
    }

    public function testCreateValue_ValidJsonWithPath_PassesDecodedJsonAndSamePathInstanceToDecodedJsonFactory(): void
    {
        $decodedJsonFactory = $this->createMock(NodeValueFactoryInterface::class);
        $encodedJsonFactory = new NodeValueFactory($decodedJsonFactory);

        $path = new Path;
        $decodedJsonFactory
            ->expects(self::once())
            ->method('createValue')
            ->with([1], $path);
        $encodedJsonFactory->createValue('[1]', $path);
    }

    public function testCreateValue_DecodedJsonFactoryReturnsInstanceReturnsSameInstance(): void
    {
        $decodedJsonFactory = $this->createMock(NodeValueFactoryInterface::class);
        $encodedJsonFactory = new NodeValueFactory($decodedJsonFactory);

        $nodeValue = $this->createMock(NodeValueInterface::class);
        $decodedJsonFactory
            ->method('createValue')
            ->willReturn($nodeValue);

        self::assertSame($nodeValue, $encodedJsonFactory->createValue('true'));
    }
}
