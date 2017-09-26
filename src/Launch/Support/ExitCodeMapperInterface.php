<?php
/**
 * User: Andreas Penz <office@dopa.media>
 * Date: 25.09.17
 */

namespace Dopamedia\PhpBatch\Launch\Support;

/**
 * Interface ExitCodeMapperInterface
 * @package Dopamedia\PhpBatch\Launch\Support
 */
interface ExitCodeMapperInterface
{
    public static final const NO_SUCH_JOB = 'NO_SUCH_JOB';
    public static final const JOB_NOT_PROVIDED = 'JOB_NOT_PROVIDED';
}