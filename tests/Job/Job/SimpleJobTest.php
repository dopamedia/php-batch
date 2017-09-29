<?php
/**
 * User: Andreas Penz <office@dopa.media>
 * Date: 25.09.17
 */

namespace Dopamedia\PhpBatch\Job;

use Dopamedia\PhpBatch\Repository\JobRepositoryInterface;
use Dopamedia\PhpBatch\Adapter\EventManagerAdapterInterface;
use Dopamedia\PhpBatch\StepInterface;
use PHPUnit\Framework\TestCase;

class SimpleJobTest extends TestCase
{

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|JobRepositoryInterface
     */
    protected $jobRepositoryMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|EventManagerAdapterInterface
     */
    protected $eventManagerAdapterMock;

    protected function setUp()
    {
        $this->jobRepositoryMock = $this->createMock(JobRepositoryInterface::class);
        $this->eventManagerAdapterMock = $this->createMock(EventManagerAdapterInterface::class);
    }

    public function testGetStepNames()
    {
        $simpleJob = new SimpleJob('', $this->eventManagerAdapterMock, $this->jobRepositoryMock);
        $this->assertEmpty($simpleJob->getStepNames());

        /** @var \PHPUnit_Framework_MockObject_MockObject|StepInterface $firstStepMock */
        $firstStepMock = $this->createMock(StepInterface::class);

        $firstStepMock->expects($this->once())
            ->method('getName')
            ->willReturn('firstStep');

        /** @var \PHPUnit_Framework_MockObject_MockObject|StepInterface $secondStepMock */
        $secondStepMock = $this->createMock(StepInterface::class);

        $secondStepMock->expects($this->once())
            ->method('getName')
            ->willReturn('secondStep');

        $simpleJob = new SimpleJob('', $this->eventManagerAdapterMock, $this->jobRepositoryMock, [$firstStepMock, $secondStepMock]);

        $this->assertEquals(['firstStep', 'secondStep'], $simpleJob->getStepNames());
    }

    public function testGetStep()
    {
        $simpleJob = new SimpleJob('', $this->eventManagerAdapterMock, $this->jobRepositoryMock);
        $this->assertNull($simpleJob->getStep('absent'));

        /** @var \PHPUnit_Framework_MockObject_MockObject|StepInterface $firstStepMock */
        $firstStepMock = $this->createMock(StepInterface::class);

        $firstStepMock->expects($this->once())
            ->method('getName')
            ->willReturn('firstStep');

        $simpleJob = new SimpleJob('', $this->eventManagerAdapterMock, $this->jobRepositoryMock, [$firstStepMock]);

        $this->assertSame($firstStepMock, $simpleJob->getStep('firstStep'));
    }
}
