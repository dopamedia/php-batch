<?php
/**
 * User: Andreas Penz <office@dopa.media>
 * Date: 28.09.17
 */

namespace Dopamedia\PhpBatch\Console\Command;

use Dopamedia\PhpBatch\Console\Cli;
use Dopamedia\PhpBatch\ExitStatus;
use Dopamedia\PhpBatch\Job\JobParameters;
use Dopamedia\PhpBatch\Job\JobParameters\ValidatorProviderInterface;
use Dopamedia\PhpBatch\Job\JobParameters\ValidatorProviderResult;
use Dopamedia\PhpBatch\Job\JobParametersFactory;
use Dopamedia\PhpBatch\Job\JobParametersValidator;
use Dopamedia\PhpBatch\Job\JobRegistryInterface;
use Dopamedia\PhpBatch\JobExecutionInterface;
use Dopamedia\PhpBatch\JobInstanceInterface;
use Dopamedia\PhpBatch\JobInterface;
use Dopamedia\PhpBatch\Repository\JobRepositoryInterface;
use Dopamedia\PhpBatch\StepExecutionInterface;
use Dopamedia\PhpBatch\WarningInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Tester\CommandTester;

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
     * @var \PHPUnit_Framework_MockObject_MockObject|JobInstanceInterface
     */
    protected $jobInstanceMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|JobInterface
     */
    protected $jobMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|JobParameters
     */
    protected $jobParametersMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|ValidatorProviderResult
     */
    protected $validatorProviderResultMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|JobExecutionInterface
     */
    protected $jobExecutionMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|ExitStatus
     */
    protected $exitStatusMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|StepExecutionInterface
     */
    protected $stepExecutionMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|WarningInterface
     */
    protected $warningMock;

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
        $this->jobInstanceMock = $this->createMock(JobInstanceInterface::class);
        $this->jobMock = $this->createMock(JobInterface::class);
        $this->jobParametersMock = $this->createMock(JobParameters::class);
        $this->validatorProviderResultMock = $this->createMock(ValidatorProviderResult::class);
        $this->jobExecutionMock = $this->createMock(JobExecutionInterface::class);
        $this->exitStatusMock = $this->createMock(ExitStatus::class);
        $this->stepExecutionMock = $this->createMock(StepExecutionInterface::class);
        $this->warningMock = $this->createMock(WarningInterface::class);

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

    public function testExecuteValidationFails()
    {
        $command = new CommandTester($this->command);

        $this->jobRepositoryMock->expects($this->once())
            ->method('getJobInstanceByCode')
            ->willReturn($this->jobInstanceMock);

        $this->jobInstanceMock->expects($this->once())
            ->method('getJobName')
            ->willReturn('');

        $this->jobRegistryMock->expects($this->once())
            ->method('getJob')
            ->willReturn($this->jobMock);

        $this->jobParametersValidatorMock->expects($this->once())
            ->method('validate')
            ->willReturn($this->validatorProviderResultMock);

        $this->validatorProviderResultMock->expects($this->once())
            ->method('hasMessages')
            ->willReturn(true);

        $this->expectException(\RuntimeException::class);

        $command->execute(['code' => 'code'], ['verbosity' => OutputInterface::VERBOSITY_VERBOSE]);
    }

    public function testExecuteWithIncompleteExitStatus()
    {
        $command = new CommandTester($this->command);

        $this->jobRepositoryMock->expects($this->once())
            ->method('getJobInstanceByCode')
            ->willReturn($this->jobInstanceMock);

        $this->jobInstanceMock->expects($this->once())
            ->method('getJobName')
            ->willReturn('');

        $this->jobRegistryMock->expects($this->once())
            ->method('getJob')
            ->willReturn($this->jobMock);

        $this->jobParametersValidatorMock->expects($this->once())
            ->method('validate')
            ->willReturn($this->validatorProviderResultMock);

        $this->validatorProviderResultMock->expects($this->once())
            ->method('hasMessages')
            ->willReturn(false);

        $this->jobRepositoryMock->expects($this->once())
            ->method('createJobExecution')
            ->willReturn($this->jobExecutionMock);

        $this->jobExecutionMock->expects($this->any())
            ->method('getExitStatus')
            ->willReturn($this->exitStatusMock);

        $this->exitStatusMock->expects($this->once())
            ->method('getExitCode')
            ->willReturn(ExitStatus::FAILED);

        $this->exitStatusMock->expects($this->once())
            ->method('__toString')
            ->willReturn('exitStatus string representation');

        $this->jobExecutionMock->expects($this->once())
            ->method('getStepExecutions')
            ->willReturn([$this->stepExecutionMock]);

        $this->stepExecutionMock->expects($this->once())
            ->method('getFailureExceptions')
            ->willReturn(
                ['failureException' =>
                    [
                        'code' => 'code',
                        'class' => 'Some\Class',
                        'message' => 'message',
                        'messageParameters' => ['parameter1', 'parameter2'],
                        'trace' => 'the exception trace'
                    ]
                ]
            );

        $command->execute(['code' => 'code'], ['verbosity' => OutputInterface::VERBOSITY_VERBOSE]);

        $this->assertEquals(Cli::RETURN_FAILURE, $command->getStatusCode());
        $this->assertContains( 'An error occurred during the execution.', $command->getDisplay());
        $this->assertContains('ExitStatus: exitStatus string representation', $command->getDisplay());
        $this->assertContains('Error #code in class Some\\Class: message', $command->getDisplay());
        $this->assertContains('the exception trace', $command->getDisplay());
    }

    public function testExecuteWithWarnings()
    {
        $command = new CommandTester($this->command);

        $this->jobRepositoryMock->expects($this->once())
            ->method('getJobInstanceByCode')
            ->willReturn($this->jobInstanceMock);

        $this->jobInstanceMock->expects($this->once())
            ->method('getJobName')
            ->willReturn('');

        $this->jobRegistryMock->expects($this->once())
            ->method('getJob')
            ->willReturn($this->jobMock);

        $this->jobParametersValidatorMock->expects($this->once())
            ->method('validate')
            ->willReturn($this->validatorProviderResultMock);

        $this->validatorProviderResultMock->expects($this->once())
            ->method('hasMessages')
            ->willReturn(false);

        $this->jobRepositoryMock->expects($this->once())
            ->method('createJobExecution')
            ->willReturn($this->jobExecutionMock);

        $this->jobExecutionMock->expects($this->any())
            ->method('getExitStatus')
            ->willReturn($this->exitStatusMock);

        $this->exitStatusMock->expects($this->once())
            ->method('getExitCode')
            ->willReturn(ExitStatus::COMPLETED);

        $this->jobExecutionMock->expects($this->once())
            ->method('getStepExecutions')
            ->willReturn([$this->stepExecutionMock]);

        $this->stepExecutionMock->expects($this->any())
            ->method('getWarnings')
            ->willReturn([$this->warningMock]);

        $this->warningMock->expects($this->once())
            ->method('getReason')
            ->willReturn('the warning reason');

        $this->jobInstanceMock->expects($this->once())
            ->method('getCode')
            ->willReturn('code');

        $command->execute(['code' => 'code'], ['verbosity' => OutputInterface::VERBOSITY_VERBOSE]);

        $this->assertEquals(Cli::RETURN_FAILURE, $command->getStatusCode());
        $this->assertContains('the warning reason', $command->getDisplay());
        $this->assertContains('code has been executed with 1 warnings', $command->getDisplay());
    }

    public function testExecute()
    {
        $command = new CommandTester($this->command);

        $this->jobRepositoryMock->expects($this->once())
            ->method('getJobInstanceByCode')
            ->willReturn($this->jobInstanceMock);

        $this->jobInstanceMock->expects($this->once())
            ->method('getJobName')
            ->willReturn('');

        $this->jobRegistryMock->expects($this->once())
            ->method('getJob')
            ->willReturn($this->jobMock);

        $this->jobInstanceMock->expects($this->once())
            ->method('getRawParameters')
            ->willReturn(['raw' => 'parameters']);

        $this->jobParametersFactoryMock->expects($this->once())
            ->method('create')
            ->with(
                $this->jobMock,
                [
                    'raw' => 'parameters',
                    'delimiter' => ';',
                    'enclosure' => '"'
                ]
            );

        $this->jobParametersValidatorMock->expects($this->once())
            ->method('validate')
            ->willReturn($this->validatorProviderResultMock);

        $this->validatorProviderResultMock->expects($this->once())
            ->method('hasMessages')
            ->willReturn(false);

        $this->jobRepositoryMock->expects($this->once())
            ->method('createJobExecution')
            ->willReturn($this->jobExecutionMock);

        $this->jobExecutionMock->expects($this->any())
            ->method('getExitStatus')
            ->willReturn($this->exitStatusMock);

        $this->exitStatusMock->expects($this->once())
            ->method('getExitCode')
            ->willReturn(ExitStatus::COMPLETED);

        $this->jobExecutionMock->expects($this->once())
            ->method('getStepExecutions')
            ->willReturn([$this->stepExecutionMock]);

        $this->stepExecutionMock->expects($this->any())
            ->method('getWarnings')
            ->willReturn([]);

        $this->jobInstanceMock->expects($this->once())
            ->method('getCode')
            ->willReturn('code');

        $command->execute(['code' => 'code', '--config' => '{"delimiter":";","enclosure":"\""}']);

        $this->assertEquals(Cli::RETURN_SUCCESS, $command->getStatusCode());
        $this->assertContains('code has been successfully executed', $command->getDisplay());
    }

}
