<?php
/**
 * User: Andreas Penz <office@dopa.media>
 * Date: 28.09.17
 */

namespace Dopamedia\Batch;

use Dopamedia\PhpBatch\Job\RuntimeErrorException;
use PHPUnit\Framework\TestCase;

class RuntimeErrorExceptionTest extends TestCase
{
    public function testGetMessageParameters()
    {
        $runtimeErrorException = new RuntimeErrorException('', ['param1', 'param2']);

        $this->assertEquals(['param1', 'param2'], $runtimeErrorException->getMessageParameters());

    }
}
