<?php

namespace Somnambulist\Tests\Domain;

use Illuminate\Http\Request;
use Somnambulist\Domain\DomainInput;
use Somnambulist\Domain\DomainInputFactory;

/**
 * Class DomainInputFactoryTest
 *
 * @package    Somnambulist\Tests\Domain
 * @subpackage Somnambulist\Tests\Domain\DomainInputFactoryTest
 */
class DomainInputFactoryTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var DomainInputFactory
     */
    protected $factory;

    protected function setUp()
    {
        $this->factory = new DomainInputFactory();
    }

    protected function tearDown()
    {
        $this->factory = null;
    }

    public function testCreate()
    {
        $input = $this->factory->create();

        $this->assertInstanceOf(DomainInput::class, $input);
    }

    public function testCreateFromHttpRequest()
    {
        $input = $this->factory->createFromHttpRequest(Request::capture());

        $this->assertInstanceOf(DomainInput::class, $input);
    }
}
