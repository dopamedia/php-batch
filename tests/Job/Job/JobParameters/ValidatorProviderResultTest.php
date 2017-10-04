<?php
/**
 * User: Andreas Penz <office@dopa.media>
 * Date: 04.10.17
 */

namespace Dopamedia\PhpBatch\Job\Job\JobParameters;

use Dopamedia\PhpBatch\Job\JobParameters\ValidatorProviderResult;
use PHPUnit\Framework\TestCase;

class ValidatorProviderResultTest extends TestCase
{
    /**
     * @var ValidatorProviderResult
     */
    protected $validatorProviderResult;

    protected function setUp()
    {
        $this->validatorProviderResult = new ValidatorProviderResult();
    }

    public function testAddMessage()
    {
        $this->validatorProviderResult->addMessage('message');

        $this->assertEquals(['message'], $this->validatorProviderResult->getMessages());
    }

    public function testHasMessage()
    {
        $this->assertFalse($this->validatorProviderResult->hasMessages());
        $this->validatorProviderResult->addMessage('message');
        $this->assertTrue($this->validatorProviderResult->hasMessages());
    }

    public function testGetMessages()
    {
        $this->assertEmpty($this->validatorProviderResult->getMessages());
        $this->validatorProviderResult->addMessage('message');
        $this->assertEquals(['message'], $this->validatorProviderResult->getMessages());
    }

}
