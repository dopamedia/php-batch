<?php
/**
 * User: Andreas Penz <office@dopa.media>
 * Date: 04.10.17
 */

namespace Dopamedia\PhpBatch\Job\Job\JobParameters;

use Dopamedia\PhpBatch\Job\JobParameters\EmptyDefaultValuesProvider;
use Dopamedia\PhpBatch\JobInterface;
use PHPUnit\Framework\TestCase;

class EmptyDefaultValuesProviderTest extends TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|JobInterface
     */
    protected $jobMock;

    protected function setUp()
    {
        $this->jobMock = $this->createMock(JobInterface::class);
    }

    public function testGetDefaultValues()
    {
        $emptyDefaultValuesProvider = new EmptyDefaultValuesProvider([]);
        $this->assertEquals([], $emptyDefaultValuesProvider->getDefaultValues());
    }

    public function testSupports()
    {
        $this->jobMock->expects($this->exactly(2))
            ->method('getName')
            ->will($this->onConsecutiveCalls('absent', 'jobName'));

        $emptyDefaultValuesProvider = new EmptyDefaultValuesProvider(['jobName']);

        $this->assertFalse($emptyDefaultValuesProvider->supports($this->jobMock));
        $this->assertTrue($emptyDefaultValuesProvider->supports($this->jobMock));
    }
}
