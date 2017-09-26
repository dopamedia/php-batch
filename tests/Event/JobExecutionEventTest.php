<?php
/**
 * User: Andreas Penz <office@dopa.media>
 * Date: 26.09.17
 */

namespace Dopamedia\PhpBatch\Event;

use Dopamedia\PhpBatch\JobExecutionInterface;
use PHPUnit\Framework\TestCase;

class JobExecutionEventTest extends TestCase
{
    public function testConstruction()
    {
        /** @var \PHPUnit_Framework_MockObject_MockObject|JobExecutionInterface $jobExecutionMock */
        $jobExecutionMock = $this->createMock(JobExecutionInterface::class);
        $jobExecutionEvent = new JobExecutionEvent($jobExecutionMock);
        $this->assertSame($jobExecutionEvent->getJobExecution(), $jobExecutionMock);
    }
}
