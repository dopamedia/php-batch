<?php
/**
 * User: Andreas Penz <office@dopa.media>
 * Date: 14.10.17
 */

namespace Dopamedia\PhpBatch\Job\Job;

use Dopamedia\PhpBatch\Adapter\EventManagerAdapterInterface;
use Dopamedia\PhpBatch\BatchStatus;
use Dopamedia\PhpBatch\Event\EventInterface;
use Dopamedia\PhpBatch\ExitStatus;
use Dopamedia\PhpBatch\Job\AbstractJob;
use Dopamedia\PhpBatch\JobExecutionInterface;
use Dopamedia\PhpBatch\JobInterruptedException;
use Dopamedia\PhpBatch\Repository\JobRepositoryInterface;
use Dopamedia\PhpBatch\StepExecutionInterface;
use Dopamedia\PhpBatch\StepInterface;
use PHPUnit\Framework\TestCase;

class AbstractJobTest extends TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|EventManagerAdapterInterface
     */
    protected $eventManagerAdapterMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|JobRepositoryInterface
     */
    protected $jobRepositoryMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|JobExecutionInterface
     */
    protected $jobExecutionMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|StepInterface
     */
    protected $stepMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|StepExecutionInterface
     */
    protected $stepExecutionMock;

    protected function setUp()
    {
        $this->eventManagerAdapterMock = $this->createMock(EventManagerAdapterInterface::class);
        $this->jobRepositoryMock = $this->createMock(JobRepositoryInterface::class);
        $this->jobExecutionMock = $this->createMock(JobExecutionInterface::class);
        $this->stepMock = $this->createMock(StepInterface::class);
        $this->stepExecutionMock = $this->createMock(StepExecutionInterface::class);
    }

    public function testGetName()
    {
        $jobRepositoryMock = $this->jobRepositoryMock;
        $eventManagerAdapter = $this->eventManagerAdapterMock;

        $dummyAbstractJob = new class('name', $eventManagerAdapter, $jobRepositoryMock) extends AbstractJob {
            protected function doExecute(JobExecutionInterface $execution): void
            {
            }
        };

        $this->assertEquals('name', $dummyAbstractJob->getName());
    }

    public function testExecuteWithJobInterruptedException()
    {
        $jobRepositoryMock = $this->jobRepositoryMock;
        $eventManagerAdapter = $this->eventManagerAdapterMock;

        $dummyAbstractJob = new class('name', $eventManagerAdapter, $jobRepositoryMock) extends AbstractJob {
            protected function doExecute(JobExecutionInterface $execution): void
            {
                throw new JobInterruptedException();
            }
        };

        $this->jobExecutionMock->expects($this->once())
            ->method('setExitStatus')
            ->with($this->callback(function(ExitStatus $exitStatus) {
                return $exitStatus->getExitCode() === ExitStatus::STOPPED;
            }));

        $this->jobExecutionMock->expects($this->exactly(2))
            ->method('setStatus')
            ->withConsecutive(
                [$this->callback(function(BatchStatus $status) {
                    return $status->getValue() === BatchStatus::STARTED;
                })],
                [$this->callback(function(BatchStatus $status) {
                    return $status->getValue() === BatchStatus::STOPPED;
                })]
            );

        $this->jobExecutionMock->expects($this->once())
            ->method('addFailureException')
            ->with($this->isInstanceOf(JobInterruptedException::class));

        $jobRepositoryMock->expects($this->exactly(3))
            ->method('saveJobExecution')
            ->with($this->jobExecutionMock);

        $dummyAbstractJob->execute($this->jobExecutionMock);
    }

    public function testExecuteWithThrowable()
    {
        $jobRepositoryMock = $this->jobRepositoryMock;
        $eventManagerAdapter = $this->eventManagerAdapterMock;

        $dummyAbstractJob = new class('name', $eventManagerAdapter, $jobRepositoryMock) extends AbstractJob {
            protected function doExecute(JobExecutionInterface $execution): void
            {
                throw new \Exception();
            }
        };

        $this->jobExecutionMock->expects($this->once())
            ->method('setExitStatus')
            ->with($this->callback(function(ExitStatus $exitStatus) {
                return $exitStatus->getExitCode() === ExitStatus::FAILED;
            }));

        $this->jobExecutionMock->expects($this->exactly(2))
            ->method('setStatus')
            ->withConsecutive(
                [$this->callback(function(BatchStatus $status) {
                    return $status->getValue() === BatchStatus::STARTED;
                })],
                [$this->callback(function(BatchStatus $status) {
                    return $status->getValue() === BatchStatus::FAILED;
                })]
            );

        $this->jobExecutionMock->expects($this->once())
            ->method('addFailureException')
            ->with($this->isInstanceOf(\Exception::class));

        $jobRepositoryMock->expects($this->exactly(3))
            ->method('saveJobExecution')
            ->with($this->jobExecutionMock);

        $eventManagerAdapter->expects($this->exactly(2))
            ->method('attach')
            ->withConsecutive(
                [EventInterface::BEFORE_JOB_EXECUTION],
                [EventInterface::JOB_EXECUTION_FATAL_ERROR]
            );

        $dummyAbstractJob->execute($this->jobExecutionMock);
    }

    public function testExecuteStoppingWithoutSteps()
    {
        $jobRepositoryMock = $this->jobRepositoryMock;
        $eventManagerAdapter = $this->eventManagerAdapterMock;

        $dummyAbstractJob = new class('name', $eventManagerAdapter, $jobRepositoryMock) extends AbstractJob {
            protected function doExecute(JobExecutionInterface $execution): void
            {
            }
        };

        $this->jobExecutionMock->expects($this->exactly(2))
            ->method('getStatus')
            ->willReturn(BatchStatus::STOPPING());

        $this->jobExecutionMock->expects($this->once())
            ->method('setStatus')
            ->with($this->callback(function(BatchStatus $status) {
                return $status->getValue() === BatchStatus::STOPPED;
            }));

        $this->jobExecutionMock->expects($this->exactly(2))
            ->method('setExitStatus');

        $jobRepositoryMock->expects($this->exactly(3))
            ->method('saveJobExecution')
            ->with($this->jobExecutionMock);

        $dummyAbstractJob->execute($this->jobExecutionMock);
    }

    public function testHandleStepThrowsJobInterruptedException()
    {
        $jobRepositoryMock = $this->jobRepositoryMock;
        $eventManagerAdapter = $this->eventManagerAdapterMock;

        $dummyAbstractJob = new class('name', $eventManagerAdapter, $jobRepositoryMock) extends AbstractJob {
            protected function doExecute(JobExecutionInterface $execution): void
            {
            }
            public function doHandleStep(StepInterface $step, JobExecutionInterface $jobExecution)
            {
                $this->handleStep($step, $jobExecution);
            }
        };

        $this->jobExecutionMock->expects($this->once())
            ->method('isStopping')
            ->willReturn(true);

        $this->expectException(JobInterruptedException::class);
        $this->expectExceptionMessage('JobExecution interrupted.');

        $dummyAbstractJob->doHandleStep($this->stepMock, $this->jobExecutionMock);
    }

    public function testHandleStepStepThrowsJobInterruptedException()
    {
        $jobRepositoryMock = $this->jobRepositoryMock;
        $eventManagerAdapter = $this->eventManagerAdapterMock;

        $dummyAbstractJob = new class('name', $eventManagerAdapter, $jobRepositoryMock) extends AbstractJob {
            protected function doExecute(JobExecutionInterface $execution): void
            {
            }
            public function doHandleStep(StepInterface $step, JobExecutionInterface $jobExecution)
            {
                $this->handleStep($step, $jobExecution);
            }
        };

        $this->jobExecutionMock->expects($this->once())
            ->method('isStopping')
            ->willReturn(false);

        $jobRepositoryMock->expects($this->exactly(1))
            ->method('saveJobExecution')
            ->with($this->jobExecutionMock);


        $this->stepMock->expects($this->once())
            ->method('execute')
            ->willThrowException(new JobInterruptedException());

        $this->expectException(JobInterruptedException::class);

        $dummyAbstractJob->doHandleStep($this->stepMock, $this->jobExecutionMock);
    }

    public function testHandleStepStepStopped()
    {
        $jobRepositoryMock = $this->jobRepositoryMock;
        $eventManagerAdapter = $this->eventManagerAdapterMock;

        $dummyAbstractJob = new class('name', $eventManagerAdapter, $jobRepositoryMock) extends AbstractJob {
            protected function doExecute(JobExecutionInterface $execution): void
            {
            }
            public function doHandleStep(StepInterface $step, JobExecutionInterface $jobExecution)
            {
                $this->handleStep($step, $jobExecution);
            }
        };

        $this->jobExecutionMock->expects($this->once())
            ->method('createStepExecution')
            ->willReturn($this->stepExecutionMock);

        $this->stepExecutionMock->expects($this->once())
            ->method('getStatus')
            ->willReturn(BatchStatus::STOPPING());

        $this->expectException(JobInterruptedException::class);
        $this->expectExceptionMessage('Job interrupted by step execution');

        $dummyAbstractJob->doHandleStep($this->stepMock, $this->jobExecutionMock);
    }
}
