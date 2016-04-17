<?php

namespace Somnambulist\Tests\Domain;

use Somnambulist\Domain\AggregateMapper;
use Somnambulist\Domain\Contracts\DomainInputMapper;
use Somnambulist\Domain\DomainInput;

/**
 * Class AggregateMapperTest
 *
 * @package    Somnambulist\Tests\Domain
 * @subpackage Somnambulist\Tests\Domain\AggregateMapperTest
 */
class AggregateMapperTest extends \PHPUnit_Framework_TestCase
{

    public function testConstructor()
    {
        $mapper = new AggregateMapper();

        $this->assertInstanceOf(AggregateMapper::class, $mapper);
        $this->assertCount(0, $mapper->getMappers());
    }

    public function testConstructorInjectedMappers()
    {
        $mapper = new AggregateMapper([
            $this->getMock(DomainInputMapper::class),
            $this->getMock(DomainInputMapper::class),
        ]);

        $this->assertInstanceOf(AggregateMapper::class, $mapper);
        $this->assertCount(2, $mapper->getMappers());
    }

    public function testRemoveMapper()
    {
        $mapper = new AggregateMapper([
            $this->getMock(DomainInputMapper::class),
            $tmp = $this->getMock(DomainInputMapper::class),
        ]);

        $this->assertInstanceOf(AggregateMapper::class, $mapper);
        $this->assertCount(2, $mapper->getMappers());

        $mapper->removeMapper($tmp);

        $this->assertCount(1, $mapper->getMappers());
    }

    public function testMap()
    {
        $entity = new \stdClass();

        $map1 = $this->getMockBuilder(DomainInputMapper::class)->getMock();
        $map1
            ->expects($this->once())
            ->method('map')
            ->will(
                $this->returnCallback(function ($input, $entity) {
                    $entity->foo = 'bar';
                })
            )
        ;
        $map1
            ->expects($this->once())
            ->method('supports')
            ->will($this->returnValue(true))
        ;
        $map2 = $this->getMockBuilder(DomainInputMapper::class)->getMock();
        $map2
            ->expects($this->once())
            ->method('map')
            ->will(
                $this->returnCallback(function ($input, $entity) {
                    $entity->baz = 'bar';
                })
            )
        ;
        $map2
            ->expects($this->once())
            ->method('supports')
            ->will($this->returnValue(true))
        ;

        $mapper = new AggregateMapper([
            $map1, $map2,
        ]);

        $mapper->map(new DomainInput(), $entity);
        $this->assertObjectHasAttribute('foo', $entity);
        $this->assertEquals('bar', $entity->foo);
        $this->assertObjectHasAttribute('baz', $entity);
        $this->assertEquals('bar', $entity->baz);
    }

    public function testMapUnsupportedDoesNotSetProperties()
    {
        $entity = new \stdClass();

        $map1 = $this->getMockBuilder(DomainInputMapper::class)->getMock();
        $map1
            ->expects($this->once())
            ->method('supports')
            ->will($this->returnValue(false))
        ;
        $map2 = $this->getMockBuilder(DomainInputMapper::class)->getMock();
        $map2
            ->expects($this->once())
            ->method('map')
            ->will(
                $this->returnCallback(function ($input, $entity) {
                    $entity->baz = 'bar';
                })
            )
        ;
        $map2
            ->expects($this->once())
            ->method('supports')
            ->will($this->returnValue(true))
        ;

        $mapper = new AggregateMapper([
            $map1, $map2,
        ]);

        $mapper->map(new DomainInput(), $entity);
        $this->assertObjectNotHasAttribute('foo', $entity);
        $this->assertObjectHasAttribute('baz', $entity);
        $this->assertEquals('bar', $entity->baz);
    }

    public function testSupports()
    {
        $mapper = new AggregateMapper();

        $this->assertTrue($mapper->supports(new \stdClass()));
    }
}