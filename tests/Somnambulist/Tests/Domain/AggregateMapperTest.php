<?php
/*
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the MIT license.
 */

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