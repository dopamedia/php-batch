<?php
/**
 * User: Andreas Penz <office@dopa.media>
 * Date: 04.10.17
 */

namespace Dopamedia\PhpBatch\Job;

use Dopamedia\PhpBatch\Job\JobParameters\DefaultValuesProviderInterface;
use Dopamedia\PhpBatch\Job\JobParameters\DefaultValuesProviderRegistryInterface;
use Dopamedia\PhpBatch\JobInterface;
use PHPUnit\Framework\TestCase;

class JobParametersFactoryTest extends TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|DefaultValuesProviderRegistryInterface
     */
    protected $defaultValuesProviderRegistryMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|DefaultValuesProviderInterface
     */
    protected $defaultValuesProviderMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|JobInterface
     */
    protected $jobMock;

    protected function setUp()
    {
        $this->defaultValuesProviderRegistryMock = $this->createMock(DefaultValuesProviderRegistryInterface::class);
        $this->jobMock = $this->createMock(JobInterface::class);
        $this->defaultValuesProviderMock = $this->createMock(DefaultValuesProviderInterface::class);
    }

    public function testCreate()
    {
        $jobParametersFactory = new JobParametersFactory(
            $this->defaultValuesProviderRegistryMock,
            JobParameters::class
        );

        $this->defaultValuesProviderRegistryMock->expects($this->once())
            ->method('get')
            ->with($this->jobMock)
            ->willReturn($this->defaultValuesProviderMock);

        $this->defaultValuesProviderMock->expects($this->once())
            ->method('getDefaultValues')
            ->willReturn(['first' => '']);

        $jobParameters = $jobParametersFactory->create(
            $this->jobMock,
            ['second' => '']
        );

        $this->assertEquals(['first' => '', 'second' => ''], $jobParameters->all());
    }
}
