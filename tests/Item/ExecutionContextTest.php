<?php
/**
 * User: Andreas Penz <office@dopa.media>
 * Date: 26.09.17
 */

namespace Dopamedia\PhpBatch\Item;

use PHPUnit\Framework\TestCase;

class ExecutionContextTest extends TestCase
{
    public function testIsDirty()
    {
        $executionContext = new ExecutionContext();
        $this->assertFalse($executionContext->isDirty());

        $executionContext = new ExecutionContext();
        $executionContext->put('key', 'value');
        $this->assertTrue($executionContext->isDirty());
    }

    public function testClearDirtyFlag()
    {
        $executionContext = new ExecutionContext();
        $executionContext->put('key', 'value');
        $this->assertTrue($executionContext->isDirty());

        $executionContext->clearDirtyFlag();
        $this->assertFalse($executionContext->isDirty());
    }

    public function testGet()
    {
        $executionContext = new ExecutionContext();
        $this->assertNull($executionContext->get('key'));

        $executionContext = new ExecutionContext();
        $executionContext->put('key', 'value');
        $this->assertEquals('value', $executionContext->get('key'));
    }

    public function testRemove()
    {
        $executionContext = new ExecutionContext();
        $executionContext->put('key', 'value');
        $this->assertEquals('value', $executionContext->get('key'));

        $executionContext->remove('key');
        $this->assertNull($executionContext->get('key'));
    }

    public function testGetKeys()
    {
        $executionContext = new ExecutionContext();
        $this->assertEmpty($executionContext->getKeys());

        $executionContext = new ExecutionContext();
        $executionContext->put('lorem', 'ipsum');
        $executionContext->put('dolor', 'sit');

        $this->assertEquals(['lorem', 'dolor'], $executionContext->getKeys());
    }
}
