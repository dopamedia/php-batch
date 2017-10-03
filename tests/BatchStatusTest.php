<?php
/**
 * User: Andreas Penz <office@dopa.media>
 * Date: 20.09.17
 */

namespace Dopamedia\PhpBatch;

use PHPUnit\Framework\TestCase;

class BatchStatusTest extends TestCase
{
    public function testConstruct()
    {
        $this->assertEquals(BatchStatus::UNKNOWN, (new BatchStatus())->getValue());
    }

    public function testGetAllLabels()
    {
        $this->assertEquals([
            BatchStatus::COMPLETED => 'COMPLETED',
            BatchStatus::STARTING  => 'STARTING',
            BatchStatus::STARTED   => 'STARTED',
            BatchStatus::STOPPING  => 'STOPPING',
            BatchStatus::STOPPED   => 'STOPPED',
            BatchStatus::FAILED    => 'FAILED',
            BatchStatus::ABANDONED => 'ABANDONED',
            BatchStatus::UNKNOWN   => 'UNKNOWN'
        ], BatchStatus::getAllLabels());
    }

    public function testIsStarting()
    {
        $this->assertFalse(BatchStatus::STOPPED()->isStarting());
        $this->assertTrue(BatchStatus::STARTING()->isStarting());
    }

    public function testIsRunning()
    {
        $this->assertFalse(BatchStatus::UNKNOWN()->isRunning());
        $this->assertTrue(BatchStatus::STARTING()->isRunning());
        $this->assertTrue(BatchStatus::STARTED()->isRunning());
    }

    public function testIsUnsuccessful()
    {
        $this->assertFalse(BatchStatus::COMPLETED()->isUnsuccessful());
        $this->assertTrue(BatchStatus::FAILED()->isUnsuccessful());
        $this->assertTrue(BatchStatus::UNKNOWN()->isUnsuccessful());
    }

    public function testIsGreaterThan()
    {
        $this->assertFalse(BatchStatus::COMPLETED()->isGreaterThan(BatchStatus::FAILED()));
        $this->assertTrue(BatchStatus::FAILED()->isGreaterThan(BatchStatus::COMPLETED()));
    }

    public function testIsGreaterThanOrEqualTo()
    {
        $this->assertFalse(BatchStatus::COMPLETED()->isGreaterThan(BatchStatus::FAILED()));
        $this->assertTrue(BatchStatus::FAILED()->isGreaterThan(BatchStatus::COMPLETED()));
        $this->assertTrue(BatchStatus::FAILED()->isGreaterThanOrEqualTo(BatchStatus::FAILED()));
    }

    public function testIsLessThan()
    {
        $this->assertFalse(BatchStatus::FAILED()->isLessThan(BatchStatus::COMPLETED()));
        $this->assertTrue(BatchStatus::COMPLETED()->isLessThan(BatchStatus::FAILED()));
    }

    public function testIsLessThanOrEqualTo()
    {
        $this->assertFalse(BatchStatus::FAILED()->isLessThanOrEqualTo(BatchStatus::COMPLETED()));
        $this->assertTrue(BatchStatus::COMPLETED()->isLessThanOrEqualTo(BatchStatus::FAILED()));
        $this->assertTrue(BatchStatus::FAILED()->isLessThanOrEqualTo(BatchStatus::FAILED()));
    }

    public function testMax()
    {
        $status1 = BatchStatus::STARTED();
        $status2 = BatchStatus::STARTING();

        $this->assertSame($status1, BatchStatus::max($status1, $status2));

        $status1 = BatchStatus::UNKNOWN();
        $status2 = BatchStatus::COMPLETED();

        $this->assertSame($status1, BatchStatus::max($status1, $status2));
    }

    public function testUpgradeTo()
    {
        $this->assertEquals(
            BatchStatus::FAILED,
            BatchStatus::FAILED()->upgradeTo(BatchStatus::COMPLETED())->getValue()
        );

        $this->assertEquals(
            BatchStatus::FAILED,
            BatchStatus::COMPLETED()->upgradeTo(BatchStatus::FAILED())->getValue()
        );

        $this->assertEquals(
            BatchStatus::COMPLETED,
            BatchStatus::STARTING()->upgradeTo(BatchStatus::COMPLETED())->getValue()
        );

        $this->assertEquals(
            BatchStatus::COMPLETED,
            BatchStatus::COMPLETED()->upgradeTo(BatchStatus::STARTING())->getValue()
        );

        $this->assertEquals(
            BatchStatus::COMPLETED,
            BatchStatus::COMPLETED()->upgradeTo(BatchStatus::COMPLETED())->getValue()
        );

        $this->assertEquals(
            BatchStatus::STOPPED,
            BatchStatus::STOPPING()->upgradeTo(BatchStatus::STOPPED())->getValue()
        );

        $this->assertEquals(
            BatchStatus::STARTING,
            BatchStatus::STARTING()->upgradeTo(BatchStatus::STARTING())->getValue()
        );

    }

    public function testToString()
    {
        $batchStatus = BatchStatus::COMPLETED();
        $this->assertEquals('COMPLETED', $batchStatus->__toString());

        $batchStatus = BatchStatus::STARTING();
        $this->assertEquals('STARTING', $batchStatus->__toString());

        $batchStatus = BatchStatus::STARTED();
        $this->assertEquals('STARTED', $batchStatus->__toString());

        $batchStatus = BatchStatus::STOPPING();
        $this->assertEquals('STOPPING', $batchStatus->__toString());

        $batchStatus = BatchStatus::STOPPED();
        $this->assertEquals('STOPPED', $batchStatus->__toString());

        $batchStatus = BatchStatus::FAILED();
        $this->assertEquals('FAILED', $batchStatus->__toString());

        $batchStatus = BatchStatus::ABANDONED();
        $this->assertEquals('ABANDONED', $batchStatus->__toString());

        $batchStatus = BatchStatus::UNKNOWN();
        $this->assertEquals('UNKNOWN', $batchStatus->__toString());
    }
}
