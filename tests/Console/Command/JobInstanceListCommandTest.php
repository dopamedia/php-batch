<?php
/**
 * User: Andreas Penz <office@dopa.media>
 * Date: 03.10.17
 */

namespace Dopamedia\PhpBatch\Console\Command;

use Dopamedia\PhpBatch\Console\Cli;
use Dopamedia\PhpBatch\JobInstanceInterface;
use Dopamedia\PhpBatch\Repository\JobRepositoryInterface;
use Symfony\Component\Console\Helper\TableFactory;
use Symfony\Component\Console\Helper\Table;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * Class JobInstanceListCommandTest
 * @package Dopamedia\Batch\Test\Unit\Console\Command
 * @group current
 */
class JobInstanceListCommandTest extends TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|JobRepositoryInterface
     */
    protected $jobRepositoryMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|TableFactory
     */
    protected $tableFactoryMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|Table
     */
    protected $tableMock;

    /**
     * @var JobInstanceListCommand
     */
    protected $command;

    protected function setUp()
    {
        $this->jobRepositoryMock = $this->createMock(JobRepositoryInterface::class);

        $this->tableFactoryMock = $this->getMockBuilder(TableFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['create'])
            ->getMock();

        $this->tableMock = $this->createMock(Table::class);

        $this->command = new JobInstanceListCommand(
            $this->jobRepositoryMock,
            $this->tableFactoryMock
        );
    }

    public function testConfigure()
    {
        $this->assertSame('batch:job-instance:list', $this->command->getName());
    }

    public function testExecuteWithoutInstances()
    {
        $this->jobRepositoryMock->expects($this->once())
            ->method('getJobInstances')
            ->willReturn([]);

        $commandTester = new CommandTester($this->command);

        $commandTester->execute([]);

        $this->assertEquals(Cli::RETURN_FAILURE, $commandTester->getStatusCode());
        $this->assertContains('No JobInstances defined', $commandTester->getDisplay());

    }

    public function testExecute()
    {
        /** @var \PHPUnit_Framework_MockObject_MockObject|JobInstanceInterface $jobInstanceMock */
        $jobInstanceMock = $this->createMock(JobInstanceInterface::class);

        $this->jobRepositoryMock->expects($this->exactly(2))
            ->method('getJobInstances')
            ->willReturn([$jobInstanceMock]);

        $this->tableFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($this->tableMock);

        $jobInstanceMock->expects($this->once())
            ->method('getId')
            ->willReturn(1);

        $jobInstanceMock->expects($this->once())
            ->method('getCode')
            ->willReturn('code');

        $jobInstanceMock->expects($this->once())
            ->method('getJobName')
            ->willReturn('jobName');

        $this->tableMock->expects($this->once())
            ->method('render');

        $commandTester = new CommandTester($this->command);

        $commandTester->execute([]);

        $this->assertEquals(Cli::RETURN_SUCCESS, $commandTester->getStatusCode());
    }
}
