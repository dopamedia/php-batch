<?php
/**
 * User: Andreas Penz <office@dopa.media>
 * Date: 04.10.17
 */

namespace Dopamedia\PhpBatch\Job\Job\JobParameters;

use Dopamedia\PhpBatch\Job\JobParameters;
use Dopamedia\PhpBatch\Job\JobParameters\EmptyValidatorProvider;
use Dopamedia\PhpBatch\JobInterface;
use PHPUnit\Framework\TestCase;

class EmptyValidatorProviderTest extends TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|JobInterface
     */
    protected $jobMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|JobParameters
     */
    protected $jobParametersMock;

    protected function setUp()
    {
        $this->jobMock = $this->createMock(JobInterface::class);
        $this->jobParametersMock = $this->createMock(JobParameters::class);
    }

    public function testValidate()
    {
        $emptyValidatorProvider = new EmptyValidatorProvider([]);
        $validatorResult = $emptyValidatorProvider->validate($this->jobParametersMock);
        $this->assertFalse($validatorResult->hasMessages());
    }

    public function testSupports()
    {
        $this->jobMock->expects($this->exactly(2))
            ->method('getName')
            ->will($this->onConsecutiveCalls('absent', 'jobName'));

        $emptyValidatorProvider = new EmptyValidatorProvider(['jobName']);

        $this->assertFalse($emptyValidatorProvider->supports($this->jobMock));
        $this->assertTrue($emptyValidatorProvider->supports($this->jobMock));
    }

}
