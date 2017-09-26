<?php
/**
 * User: Andreas Penz <office@dopa.media>
 * Date: 26.09.17
 */

namespace Dopamedia\PhpBatch\Event;

use Dopamedia\PhpBatch\StepExecutionInterface;
use PHPUnit\Framework\TestCase;

class StepExecutionEventTest extends TestCase
{
    public function testConstruction()
    {
        /** @var \PHPUnit_Framework_MockObject_MockObject|StepExecutionInterface $stepExecutionMock */
        $stepExecutionMock = $this->createMock(StepExecutionInterface::class);
        $stepExecution = new StepExecutionEvent($stepExecutionMock);
        $this->assertSame($stepExecutionMock, $stepExecution->getStepExecution());
    }
}
