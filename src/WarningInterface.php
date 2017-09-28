<?php
/**
 * User: Andreas Penz <office@dopa.media>
 * Date: 27.09.17
 */

namespace Dopamedia\PhpBatch;

/**
 * Interface WarningInterface
 * @package Dopamedia\PhpBatch
 */
interface WarningInterface extends EntityInterface
{
    /**
     * @return StepExecutionInterface|null
     */
    public function getStepExecution(): ?StepExecutionInterface;

    /**
     * @param StepExecutionInterface $stepExecution
     * @return WarningInterface
     */
    public function setStepExecution(StepExecutionInterface $stepExecution): WarningInterface;

    /**
     * @return null|string
     */
    public function getReason(): ?string;

    /**
     * @param string $reason
     * @return WarningInterface
     */
    public function setReason(string $reason): WarningInterface;

    /**
     * @return array
     */
    public function getReasonParameters(): array;

    /**
     * @param array $reasonParameters
     * @return WarningInterface
     */
    public function setReasonParameters(array $reasonParameters): WarningInterface;

    /**
     * @return array|null
     */
    public function getItem(): ?array;

    /**
     * @param array $item
     * @return WarningInterface
     */
    public function setItem(array $item): WarningInterface;
}