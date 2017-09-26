<?php
/**
 * User: Andreas Penz <office@dopa.media>
 * Date: 26.09.17
 */

namespace Dopamedia\PhpBatch\Event;

/**
 * Interface EventInterface
 * @package Dopamedia\PhpBatch\Event
 */
interface EventInterface
{
    public const BEFORE_JOB_EXECUTION = 'batch.before_job_execution';
    public const JOB_EXECUTION_STOPPED = 'batch.job_execution_stopped';
    public const JOB_EXECUTION_INTERRUPTED = 'batch.job_execution_interrupted';
    public const JOB_EXECUTION_FATAL_ERROR = 'batch.job_execution_fatal_error';
    public const BEFORE_JOB_STATUS_UPGRADE = 'batch.before_job_status_upgrade';
    public const AFTER_JOB_EXECUTION = 'batch.after_job_execution';

    public const BEFORE_STEP_EXECUTION = 'batch.before_step_execution';
    public const STEP_EXECUTION_SUCCEEDED = 'batch.step_execution_succeeded';
    public const STEP_EXECUTION_INTERRUPTED = 'batch.step_execution_interrupted';
    public const STEP_EXECUTION_ERRORED = 'batch.step_execution_errored';
    public const STEP_EXECUTION_COMPLETED = 'batch.step_execution_completed';
    public const INVALID_ITEM = 'batch.invalid_item';
}