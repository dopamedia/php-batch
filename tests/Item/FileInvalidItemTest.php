<?php
/**
 * User: Andreas Penz <office@dopa.media>
 * Date: 14.10.17
 */

namespace Dopamedia\PhpBatch\Item;

use PHPUnit\Framework\TestCase;

class FileInvalidItemTest extends TestCase
{
    public function testGetInvalidData()
    {
        $fileInvalidItem = new FileInvalidItem(['data'], 10);
        $this->assertEquals(['data'], $fileInvalidItem->getInvalidData());
    }

    public function testGetItemPosition()
    {
        $fileInvalidItem = new FileInvalidItem(['data'], 10);
        $this->assertEquals(10, $fileInvalidItem->getItemPosition());
    }
}
