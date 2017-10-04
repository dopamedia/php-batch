<?php
/**
 * User: Andreas Penz <office@dopa.media>
 * Date: 04.10.17
 */

namespace Dopamedia\PhpBatch\Job\Job\JobParameters;

use Dopamedia\PhpBatch\Job\JobParameters\EmptyConstraintCollectionProvider;
use Dopamedia\PhpBatch\JobInterface;
use PHPUnit\Framework\TestCase;

class EmptyConstraintCollectionProviderTest extends TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|JobInterface
     */
    protected $jobMock;

    protected function setUp()
    {
        $this->jobMock = $this->createMock(JobInterface::class);
    }

    public function testGetConstraintCollection()
    {
        $provider = new EmptyConstraintCollectionProvider([]);
        $collection = $provider->getConstraintCollection();
        $this->assertEquals([0 => 'fields' ], $collection->getRequiredOptions());
    }

    public function testSupports()
    {
        $this->jobMock->expects($this->exactly(2))
            ->method('getName')
            ->will($this->onConsecutiveCalls('absent', 'jobName'));

        $provider = new EmptyConstraintCollectionProvider(['jobName']);
        $this->assertFalse($provider->supports($this->jobMock));
        $this->assertTrue($provider->supports($this->jobMock));
    }
}
