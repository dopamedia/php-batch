<?php
/**
 * User: Andreas Penz <office@dopa.media>
 * Date: 28.09.17
 */

namespace Dopamedia\PhpBatch\Console\Command;

use Dopamedia\PhpBatch\Job\JobParametersFactory;
use Dopamedia\PhpBatch\Job\JobParametersValidator;
use Dopamedia\PhpBatch\Job\JobRegistryInterface;
use Dopamedia\PhpBatch\Repository\JobRepositoryInterface;
use PHPUnit\Framework\TestCase;

class JobExecuteCommandTest extends TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|JobRegistryInterface
     */
    protected $jobRegistryMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|JobRepositoryInterface
     */
    protected $jobRepositoryMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|JobParametersFactory
     */
    protected $jobParametersFactoryMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|JobParametersValidator
     */
    protected $jobParametersValidatorMock;

    /**
     * @var JobExecuteCommand
     */
    private $command;

    protected function setUp()
    {
        $this->jobRegistryMock = $this->createMock(JobRegistryInterface::class);
        $this->jobRepositoryMock = $this->createMock(JobRepositoryInterface::class);
        $this->jobParametersFactoryMock = $this->createMock(JobParametersFactory::class);
        $this->jobParametersValidatorMock = $this->createMock(JobParametersValidator::class);

        $this->command = new JobExecuteCommand(
            $this->jobRegistryMock,
            $this->jobRepositoryMock,
            $this->jobParametersFactoryMock,
            $this->jobParametersValidatorMock
        );
    }

    public function testConfigure()
    {
        $this->assertSame('batch:job:execute', $this->command->getName());

        $commandDefinition = $this->command->getDefinition();

        $this->assertTrue($commandDefinition->hasArgument('code'));
        $this->assertTrue($commandDefinition->getArgument('code')->isRequired());

        $this->assertTrue($commandDefinition->hasOption('config'));
        $this->assertTrue($commandDefinition->getOption('config')->isValueRequired());
    }

}
