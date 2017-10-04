<?php
/**
 * User: Andreas Penz <office@dopa.media>
 * Date: 04.10.17
 */

namespace Dopamedia\PhpBatch\Job\Job;

use Dopamedia\PhpBatch\Job\JobParameters;
use Dopamedia\PhpBatch\Job\UndefinedJobParameterException;
use PHPUnit\Framework\TestCase;

class JobParametersTest extends TestCase
{
    public function testHas()
    {
        $jobParameters = new JobParameters([]);
        $this->assertFalse($jobParameters->has('key'));

        $jobParameters = new JobParameters(['key' => 'value']);
        $this->assertTrue($jobParameters->has('key'));
    }

    public function testGetThrowsException()
    {
        $this->expectException(UndefinedJobParameterException::class);
        $this->expectExceptionMessage('Parameter "key" is undefined');

        $jobParameters = new JobParameters([]);
        $jobParameters->get('key');
    }

    public function testGet()
    {
        $jobParameters = new JobParameters(['key' => 'value']);
        $this->assertEquals('value', $jobParameters->get('key'));
    }

    public function testAll()
    {
        $jobParameters = new JobParameters(['first' => '', 'second' => '']);
        $this->assertEquals(['first' => '', 'second' => ''], $jobParameters->all());
    }

    public function testGetIterator()
    {
        $jobParameters = new JobParameters(['first' => 'first', 'second' => 'second']);

        $iterator = $jobParameters->getIterator();

        $iterator->rewind();

        $this->assertEquals('first', $iterator->current());

        $iterator->next();
        $this->assertEquals('second', $iterator->current());

        $iterator->next();
        $this->assertFalse($iterator->valid());
    }

    public function testCount()
    {
        $jobParameters = new JobParameters(['first' => '', 'second' => '']);

        $this->assertEquals(2, $jobParameters->count());

    }

}
