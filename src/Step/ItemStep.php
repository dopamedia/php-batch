<?php
/**
 * User: Andreas Penz <office@dopa.media>
 * Date: 26.09.17
 */

namespace Dopamedia\PhpBatch\Step;

use Dopamedia\PhpBatch\Adapter\EventManagerAdapterInterface;
use Dopamedia\PhpBatch\Item\FlushableInterface;
use Dopamedia\PhpBatch\Item\InitializableInterface;
use Dopamedia\PhpBatch\Item\InvalidItemException;
use Dopamedia\PhpBatch\Item\ItemProcessorInterface;
use Dopamedia\PhpBatch\Item\ItemReaderInterface;
use Dopamedia\PhpBatch\Item\ItemWriterInterface;
use Dopamedia\PhpBatch\Repository\JobRepositoryInterface;
use Dopamedia\PhpBatch\StepExecutionInterface;

/**
 * Class ItemStep
 * @package Dopamedia\PhpBatch\Step
 */
class ItemStep extends AbstractStep
{
    /**
     * @var ItemReaderInterface
     */
    private $reader;

    /**
     * @var ItemProcessorInterface
     */
    private $processor;

    /**
     * @var ItemWriterInterface
     */
    private $writer;

    /**
     * @var int
     */
    private $batchSize;

    /**
     * @var null|StepExecutionInterface
     */
    private $stepExecution;

    /**
     * ItemStep constructor.
     * @param string $name
     * @param EventManagerAdapterInterface $eventManagerAdapter
     * @param JobRepositoryInterface $jobRepository
     * @param ItemReaderInterface $reader
     * @param ItemProcessorInterface $processor
     * @param ItemWriterInterface $writer
     * @param int $batchSize
     */
    public function __construct(
        string $name,
        EventManagerAdapterInterface $eventManagerAdapter,
        JobRepositoryInterface $jobRepository,
        ItemReaderInterface $reader,
        ItemProcessorInterface $processor,
        ItemWriterInterface $writer,
        $batchSize = 100
    )
    {
        parent::__construct($name, $eventManagerAdapter, $jobRepository);
        $this->reader = $reader;
        $this->processor = $processor;
        $this->writer = $writer;
        $this->batchSize = $batchSize;
    }


    /**
     * @inheritDoc
     */
    protected function doExecute(StepExecutionInterface $stepExecution): void
    {
        $itemsToWrite = [];
        $writeCount = 0;

        $this->initializeStepElements($stepExecution);

        $stopExecution = false;
        while (!$stopExecution) {
            try {
                $readItem = $this->reader->read();
                if ($readItem === null) {
                    $stopExecution = true;
                    continue;
                }
            } catch (InvalidItemException $e) {
                $this->handleStepExecutionWarning($this->stepExecution, $this->reader, $e);
                continue;
            }

            $processedItem = $this->process($readItem);
            if ($processedItem !== null) {
                $itemsToWrite[] = $processedItem;
                $writeCount++;
                if (($writeCount % $this->batchSize ) === 0 ) {
                    $this->write($itemsToWrite);
                    $itemsToWrite = [];
                    $this->jobRepository->saveStepExecution($stepExecution);
                }
            }
        }

        if (count($itemsToWrite) > 0) {
            $this->write($itemsToWrite);
        }

        $this->flushStepElements();
    }

    /**
     * @param StepExecutionInterface $stepExecution
     * @return void
     */
    protected function initializeStepElements(StepExecutionInterface $stepExecution): void
    {
        $this->stepExecution = $stepExecution;
        foreach ($this->getStepElements() as $element) {
            if ($element instanceof StepExecutionAwareInterface) {
                $element->setStepExecution($stepExecution);
            }
            if ($element instanceof InitializableInterface) {
                $element->initialize();
            }
        }
    }

    /**
     * @return array
     */
    protected function getStepElements(): array
    {
        return [$this->reader, $this->processor, $this->writer];
    }

    /**
     * @param StepExecutionInterface $stepExecution
     * @param $element
     * @param InvalidItemException $e
     * @return void
     */
    protected function handleStepExecutionWarning(
        StepExecutionInterface $stepExecution,
        $element,
        InvalidItemException $e
    ) {
        $this->jobRepository->createWarning(
            $stepExecution,
            $e->getMessage(),
            $e->getMessageParameters(),
            $e->getItem()->getInvalidData()
        );

        $this->attachInvalidItemEvent(
            get_class($element),
            $e->getMessage(),
            $e->getMessageParameters(),
            $e->getItem()
        );
    }

    /**
     * @param mixed $readItem
     * @return mixed|null
     */
    protected function process($readItem)
    {
        try {
            return $this->processor->process($readItem);
        } catch (InvalidItemException $e) {
            $this->handleStepExecutionWarning($this->stepExecution, $this->processor, $e);

            return null;
        }
    }

    /**
     * @param array $processedItems
     */
    protected function write(array $processedItems): void
    {
        try {
            $this->writer->write($processedItems);
        } catch (InvalidItemException $e) {
            $this->handleStepExecutionWarning($this->stepExecution, $this->writer, $e);
        }
    }

    /**
     * @return void
     */
    public function flushStepElements(): void
    {
        foreach ($this->getStepElements() as $element) {
            if ($element instanceof FlushableInterface) {
                $element->flush();
            }
        }
    }



}