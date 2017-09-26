<?php
/**
 * User: Andreas Penz <office@dopa.media>
 * Date: 25.09.17
 */

namespace Dopamedia\PhpBatch;

use PHPUnit\Framework\TestCase;

class JobInterruptedExceptionTest extends TestCase
{
    public function testGetStatus()
    {
        $jobInterruptedException = new JobInterruptedException();
        $this->assertEquals(
            BatchStatus::STOPPED,
            $jobInterruptedException->getStatus()->getValue()
        );

        $batchStatus = BatchStatus::COMPLETED();

        $jobInterruptedException = new JobInterruptedException(
            '',
            0,
            null,
            $batchStatus
        );

        $this->assertSame($batchStatus, $jobInterruptedException->getStatus());
    }
}
