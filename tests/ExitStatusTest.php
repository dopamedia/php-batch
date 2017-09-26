<?php
/**
 * User: Andreas Penz <office@dopa.media>
 * Date: 20.09.17
 */

namespace Dopamedia\PhpBatch;

use PHPUnit\Framework\TestCase;

class ExitStatusTest extends TestCase
{
    public function testAndStatus()
    {
        $status = new ExitStatus('');
        $this->assertSame($status, $status->logicalAnd());

        $status = new ExitStatus(ExitStatus::EXECUTING, 'executing');
        $other = new ExitStatus(ExitStatus::STOPPED, 'stopped');

        $this->assertEquals(
            ExitStatus::EXECUTING,
            $status->logicalAnd($other)->getExitCode()
        );

        $status = new ExitStatus(ExitStatus::EXECUTING, 'executing');
        $other = new ExitStatus(ExitStatus::STOPPED, 'stopped');

        $this->assertEquals(
            'executing; stopped',
            $status->logicalAnd($other)->getExitDescription()
        );

        $status = new ExitStatus(ExitStatus::STOPPED, 'stopped');
        $other = new ExitStatus(ExitStatus::EXECUTING, 'executing');

        $this->assertEquals(
            ExitStatus::EXECUTING,
            $status->logicalAnd($other)->getExitCode()
        );

        $status = new ExitStatus(ExitStatus::STOPPED, 'stopped');
        $other = new ExitStatus(ExitStatus::EXECUTING, 'executing');

        $this->assertEquals(
            'stopped; executing',
            $status->logicalAnd($other)->getExitDescription()
        );
    }

    public function testAddExitDescription()
    {
        $status = new ExitStatus('');

        $this->assertNotEmpty(
            $status
                ->addExitDescription(new \Exception('Exception: an awesome exception'))
                ->getExitDescription()
        );

        $status = new ExitStatus('', 'an awesome description');

        $this->assertEquals(
            'an awesome description',
            $status->addExitDescription('')->getExitDescription()
        );

        $this->assertEquals(
            'an awesome description; an other one',
            $status->addExitDescription('an other one')->getExitDescription()
        );
    }

    public function testCompareTo()
    {
        $this->assertEquals(
            1,
            (new ExitStatus(ExitStatus::EXECUTING))->compareTo(new ExitStatus(ExitStatus::STOPPED))
        );

        $this->assertEquals(
            0,
            (new ExitStatus(ExitStatus::EXECUTING))->compareTo(new ExitStatus(ExitStatus::EXECUTING))
        );

        $this->assertEquals(
            -1,
            (new ExitStatus(ExitStatus::STOPPED))->compareTo(new ExitStatus(ExitStatus::EXECUTING))
        );
    }

    public function testReplaceExitCode()
    {
        $status = new ExitStatus('code');
        $this->assertNotSame($status, $status->replaceExitCode(''));

        $status = new ExitStatus('code');
        $this->assertSame('replaced code', $status->replaceExitCode('replaced code')->getExitCode());
    }

    public function testIsRunning()
    {
        $this->assertFalse((new ExitStatus(ExitStatus::STOPPED))->isRunning());
        $this->assertTrue((new ExitStatus(ExitStatus::EXECUTING))->isRunning());
        $this->assertTrue((new ExitStatus(ExitStatus::UNKNOWN))->isRunning());
    }

    public function testToString()
    {
        $this->assertSame(
            '[STOPPED] a description',
            (new ExitStatus(ExitStatus::STOPPED, 'a description'))->__toString()
        );
    }


}
