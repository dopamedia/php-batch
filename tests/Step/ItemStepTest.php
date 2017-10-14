<?php
/**
 * User: Andreas Penz <office@dopa.media>
 * Date: 14.10.17
 */

namespace Dopamedia\PhpBatch\tests\Step;

use Dopamedia\PhpBatch\Adapter\EventManagerAdapterInterface;
use Dopamedia\PhpBatch\Item\InitializableInterface;
use Dopamedia\PhpBatch\Item\InvalidItemException;
use Dopamedia\PhpBatch\Item\InvalidItemInterface;
use Dopamedia\PhpBatch\Item\ItemProcessorInterface;
use Dopamedia\PhpBatch\Item\ItemReaderInterface;
use Dopamedia\PhpBatch\Item\ItemWriterInterface;
use Dopamedia\PhpBatch\Repository\JobRepositoryInterface;
use Dopamedia\PhpBatch\Step\ItemStep;
use Dopamedia\PhpBatch\Step\StepExecutionAwareInterface;
use Dopamedia\PhpBatch\StepExecutionInterface;
use PHPUnit\Framework\TestCase;

class ItemStepTest extends TestCase
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
     * @var \PHPUnit_Framework_MockObject_MockObject|ItemReaderInterface
     */
    protected $itemReaderMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|ItemProcessorInterface
     */
    protected $itemProcessorMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|ItemWriterInterface
     */
    protected $itemWriterMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|StepExecutionInterface
     */
    protected $stepExecutionMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|InvalidItemInterface
     */
    protected $invalidItemMock;

    protected function setUp()
    {
        $this->eventManagerAdapterMock = $this->createMock(EventManagerAdapterInterface::class);
        $this->jobRepositoryMock = $this->createMock(JobRepositoryInterface::class);
        $this->itemReaderMock = $this->createMock(ItemReaderInterface::class);
        $this->itemProcessorMock = $this->createMock(ItemProcessorInterface::class);
        $this->itemWriterMock = $this->createMock(ItemWriterInterface::class);
        $this->stepExecutionMock = $this->createMock(StepExecutionInterface::class);
        $this->invalidItemMock = $this->createMock(InvalidItemInterface::class);
    }

    public function testDoExecuteInitializesStepElements()
    {
        $eventManagerAdapterMock = $this->eventManagerAdapterMock;
        $jobRepositoryMock = $this->jobRepositoryMock;

        /** @var \PHPUnit_Framework_MockObject_MockObject|DummyItemReaderStepExecutionAwareInterface $itemReaderMock */
        $itemReaderMock = $this->createMock(DummyItemReaderStepExecutionAwareInterface::class);

        /** @var \PHPUnit_Framework_MockObject_MockObject|DummyItemProcessorInitializableInterface  $itemProcessorMock */
        $itemProcessorMock = $this->createMock(DummyItemProcessorInitializableInterface::class);

        $itemWriterMock = $this->itemWriterMock;

        $dummyItemStep = new class(
            '',
            $eventManagerAdapterMock,
            $jobRepositoryMock,
            $itemReaderMock,
            $itemProcessorMock,
            $itemWriterMock
        ) extends ItemStep {
            public function execute(StepExecutionInterface $execution): void
            {
                $this->doExecute($execution);
            }
        };

        $itemReaderMock->expects($this->once())
            ->method('setStepExecution')
            ->with($this->stepExecutionMock);

        $itemProcessorMock->expects($this->once())
            ->method('initialize');

        $dummyItemStep->execute($this->stepExecutionMock);
    }

    public function testDoExecuteReaderThrowsInvalidItemException()
    {
        $eventManagerAdapterMock = $this->eventManagerAdapterMock;
        $jobRepositoryMock = $this->jobRepositoryMock;
        $itemReaderMock = $this->itemReaderMock;
        $itemProcessorMock = $this->itemProcessorMock;
        $itemWriterMock = $this->itemWriterMock;

        $dummyItemStep = new class(
            '',
            $eventManagerAdapterMock,
            $jobRepositoryMock,
            $itemReaderMock,
            $itemProcessorMock,
            $itemWriterMock
        ) extends ItemStep {
            public function execute(StepExecutionInterface $execution): void
            {
                $this->doExecute($execution);
            }
        };

        $this->itemReaderMock->expects($this->at(0))
            ->method('read')
            ->willThrowException(
                new InvalidItemException(
                    'exception message',
                    $this->invalidItemMock,
                    ['param1', 'param2']
                )
            );

        $this->invalidItemMock->expects($this->once())
            ->method('getInvalidData')
            ->willReturn(['invalid data']);

        $this->jobRepositoryMock->expects($this->once())
            ->method('createWarning')
            ->with(
                $this->stepExecutionMock,
                'exception message',
                ['param1', 'param2'],
                ['invalid data']
            );

        $dummyItemStep->execute($this->stepExecutionMock);
    }

    public function testDoExecute()
    {
        $eventManagerAdapterMock = $this->eventManagerAdapterMock;
        $jobRepositoryMock = $this->jobRepositoryMock;
        $itemReaderMock = $this->itemReaderMock;
        $itemProcessorMock = $this->itemProcessorMock;
        $itemWriterMock = $this->itemWriterMock;

        $dummyItemStep = new class(
            '',
            $eventManagerAdapterMock,
            $jobRepositoryMock,
            $itemReaderMock,
            $itemProcessorMock,
            $itemWriterMock,
            2
        ) extends ItemStep {
            public function execute(StepExecutionInterface $execution): void
            {
                $this->doExecute($execution);
            }
        };

        $itemReaderMock->expects($this->exactly(4))
            ->method('read')
            ->will($this->onConsecutiveCalls(['first'], ['second'], ['third'], null));

        $itemProcessorMock->expects($this->exactly(3))
            ->method('process')
            ->will($this->onConsecutiveCalls(['first'], ['second'], ['third']));

        $this->jobRepositoryMock->expects($this->exactly(1))
            ->method('saveStepExecution');

        $dummyItemStep->execute($this->stepExecutionMock);

    }

}

interface DummyItemReaderStepExecutionAwareInterface extends ItemReaderInterface, StepExecutionAwareInterface {}
interface DummyItemProcessorInitializableInterface extends ItemProcessorInterface, InitializableInterface {}


