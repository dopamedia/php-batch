<?php
/**
 * User: Andreas Penz <office@dopa.media>
 * Date: 04.10.17
 */

namespace Dopamedia\PhpBatch\Job\Job;

use Dopamedia\PhpBatch\Job\JobParameters;
use Dopamedia\PhpBatch\Job\JobParametersValidator;
use Dopamedia\PhpBatch\JobInterface;
use PHPUnit\Framework\TestCase;

class JobParametersValidatorTest extends TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|JobParameters\ValidatorProviderRegistryInterface
     */
    protected $validatorProviderRegistryMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|JobParameters\ValidatorProviderInterface
     */
    protected $validatorProviderMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|JobInterface
     */
    protected $jobMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|JobParameters
     */
    protected $jobParametersMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|JobParameters\ValidatorProviderResult
     */
    protected $validatorProviderResultMock;

    /**
     * @var JobParametersValidator
     */
    protected $jobParametersValidator;

    protected function setUp()
    {
        $this->validatorProviderRegistryMock = $this->createMock(JobParameters\ValidatorProviderRegistryInterface::class);
        $this->validatorProviderMock = $this->createMock(JobParameters\ValidatorProviderInterface::class);
        $this->jobMock = $this->createMock(JobInterface::class);
        $this->jobParametersMock = $this->createMock(JobParameters::class);
        $this->validatorProviderResultMock = $this->createMock(JobParameters\ValidatorProviderResult::class);

        $this->jobParametersValidator = new JobParametersValidator(
            $this->validatorProviderRegistryMock
        );
    }

    public function testValidate()
    {
        $this->validatorProviderRegistryMock->expects($this->once())
            ->method('get')
            ->willReturn($this->validatorProviderMock);

        $this->validatorProviderMock->expects($this->once())
            ->method('validate')
            ->with($this->jobParametersMock)
            ->willReturn($this->validatorProviderResultMock);

        $this->jobParametersValidator->validate(
            $this->jobMock,
            $this->jobParametersMock
        );
    }
}
