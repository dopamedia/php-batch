<?php
/**
 * User: Andreas Penz <office@dopa.media>
 * Date: 04.10.17
 */

namespace Dopamedia\PhpBatch\Job\Job;

use Dopamedia\PhpBatch\Job\JobParameters;
use Dopamedia\PhpBatch\Job\JobParameters\ConstraintCollectionProviderRegistryInterface;
use Dopamedia\PhpBatch\Job\JobParametersValidator;
use Dopamedia\PhpBatch\JobInterface;
use Symfony\Component\Validator\Constraints\Collection;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class JobParametersValidatorTest extends TestCase
{

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|ValidatorInterface
     */
    protected $validatorMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|ConstraintCollectionProviderRegistryInterface
     */
    protected $registryMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|JobParameters\ConstraintCollectionProviderInterface
     */
    protected $providerMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|JobInterface
     */
    protected $jobMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|Collection
     */
    protected $constraintCollectionMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|JobParameters
     */
    protected $jobParametersMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|ConstraintViolationListInterface
     */
    protected $constraintViolationListMock;

    /**
     * @var JobParametersValidator
     */
    protected $jobParametersValidator;

    protected function setUp()
    {
        $this->validatorMock = $this->createMock(ValidatorInterface::class);
        $this->registryMock = $this->createMock(ConstraintCollectionProviderRegistryInterface::class);
        $this->providerMock = $this->createMock(JobParameters\ConstraintCollectionProviderInterface::class);
        $this->jobMock = $this->createMock(JobInterface::class);
        $this->constraintCollectionMock = $this->createMock(Collection::class);
        $this->jobParametersMock = $this->createMock(JobParameters::class);
        $this->constraintViolationListMock = $this->createMock(ConstraintViolationListInterface::class);

        $this->jobParametersValidator = new JobParametersValidator(
            $this->validatorMock,
            $this->registryMock
        );
    }


    public function testValidate()
    {

        $this->registryMock->expects($this->once())
            ->method('get')
            ->willReturn($this->providerMock);

        $this->providerMock->expects($this->once())
            ->method('getConstraintCollection')
            ->willReturn($this->constraintCollectionMock);

        $this->jobParametersMock->expects($this->once())
            ->method('all')
            ->willReturn(['key' => 'value']);

        $this->validatorMock->expects($this->once())
            ->method('validate')
            ->with(['key' => 'value'], $this->constraintCollectionMock, [])
            ->willReturn($this->constraintViolationListMock);

        $this->jobParametersValidator->validate(
            $this->jobMock,
            $this->jobParametersMock,
            []
        );
    }

}
