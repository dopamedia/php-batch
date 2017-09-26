<?php
/**
 * User: Andreas Penz <office@dopa.media>
 * Date: 26.09.17
 */

namespace Dopamedia\PhpBatch\Event;

use Dopamedia\PhpBatch\Item\InvalidItemInterface;
use PHPUnit\Framework\TestCase;

class InvalidItemEventTest extends TestCase
{
    public function testConstruction()
    {
        /** @var \PHPUnit_Framework_MockObject_MockObject|InvalidItemInterface $invalidItemMock */
        $invalidItemMock = $this->createMock(InvalidItemInterface::class);

        $invalidItemEvent = new InvalidItemEvent($invalidItemMock, 'class', 'reason', ['key' => 'value']);

        $this->assertSame($invalidItemMock, $invalidItemEvent->getItem());
        $this->assertEquals('class', $invalidItemEvent->getClass());
        $this->assertEquals('reason', $invalidItemEvent->getReason());
        $this->assertEquals(['key' => 'value'], $invalidItemEvent->getReasonParameters());

    }
}
