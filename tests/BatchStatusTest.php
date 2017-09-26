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

    public function testMax()
    {
        $batchStatus1 = BatchStatus::STARTING();
        $batchStatus2 = BatchStatus::FAILED();

        $this->assertSame($batchStatus2, (new BatchStatus())::max($batchStatus1, $batchStatus2));

        $batchStatus1 = BatchStatus::ABANDONED();
        $batchStatus2 = BatchStatus::COMPLETED();

        $this->assertSame($batchStatus1, (new BatchStatus())::max($batchStatus1, $batchStatus2));
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

    public function testUpgradeTo()
    {
        $this->assertEquals(
            BatchStatus::FAILED,
            BatchStatus::STARTED()->upgradeTo(BatchStatus::FAILED())->getValue()
        );

        $this->assertEquals(
            BatchStatus::COMPLETED,
            BatchStatus::STARTING()->upgradeTo(BatchStatus::COMPLETED())->getValue()
        );

        $this->assertEquals(
            BatchStatus::STARTED,
            BatchStatus::STARTING()->upgradeTo(BatchStatus::STARTED())->getValue()
        );
    }

    public function testIsGreaterThan()
    {
        $this->assertFalse(BatchStatus::COMPLETED()->isGreaterThan(BatchStatus::UNKNOWN()));
        $this->assertTrue(BatchStatus::FAILED()->isGreaterThan(BatchStatus::STARTING()));
    }

    public function testIsLessThan()
    {
        $this->assertFalse(BatchStatus::STARTING()->isLessThan(BatchStatus::COMPLETED()));
        $this->assertTrue(BatchStatus::STOPPING()->isLessThan(BatchStatus::FAILED()));
    }

    public function testIsLessThanOrEqualTo()
    {
        $this->assertFalse(BatchStatus::STARTING()->isLessThanOrEqualTo(BatchStatus::COMPLETED()));
        $this->assertTrue(BatchStatus::STOPPING()->isLessThanOrEqualTo(BatchStatus::FAILED()));
        $this->assertTrue(BatchStatus::STOPPED()->isLessThanOrEqualTo(BatchStatus::STOPPED()));
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
