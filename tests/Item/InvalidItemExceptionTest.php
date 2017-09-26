<?php
/**
 * User: Andreas Penz <office@dopa.media>
 * Date: 26.09.17
 */

namespace Dopamedia\PhpBatch\Item;

use PHPUnit\Framework\TestCase;

class InvalidItemExceptionTest extends TestCase
{
    public function testConstruction()
    {
        /** @var \PHPUnit_Framework_MockObject_MockObject|InvalidItemInterface $invalidItemMock */
        $invalidItemMock = $this->createMock(InvalidItemInterface::class);
        $invalidItemException = new InvalidItemException('', $invalidItemMock, ['key' => 'value']);
        $this->assertSame($invalidItemMock, $invalidItemException->getItem());
        $this->assertEquals(['key' => 'value'], $invalidItemException->getMessageParameters());
    }
}
