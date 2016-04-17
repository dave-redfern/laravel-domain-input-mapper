<?php

namespace Somnambulist\Tests\Domain\Collection;

use Somnambulist\Domain\Collection\Immutable;

/**
 * Class ImmutableTest
 *
 * @package    Somnambulist\Tests\Domain\Collection
 * @subpackage Somnambulist\Tests\Domain\Collection\ImmutableTest
 */
class ImmutableTest extends \PHPUnit_Framework_TestCase
{

    public function testCannotSetValueByArrayAccess()
    {
        $col = new Immutable();

        $this->setExpectedException('RuntimeException');
        $col['foo'] = 'bar';
    }

    public function testCannotSetValueByMagicSet()
    {
        $col = new Immutable();

        $this->setExpectedException('RuntimeException');
        $col->foo = 'bar';
    }

    public function testCannotResetCollection()
    {
        $col = new Immutable();

        $this->setExpectedException('RuntimeException');
        $col->reset();
    }

    public function testCannotUnsetByArrayAccess()
    {
        $col = new Immutable(['foo' => 'bar']);

        $this->setExpectedException('RuntimeException');
        unset($col['foo']);
    }

    public function testCannotUnsetByMagicUnset()
    {
        $col = new Immutable(['foo' => 'bar']);

        $this->setExpectedException('RuntimeException');
        unset($col->foo);
    }

    public function testCallingFreezeReturnsSelf()
    {
        $col = new Immutable(['foo' => 'bar']);

        $this->assertSame($col, $col->freeze());
    }

    public function testCannotAppend()
    {
        $col = new Immutable(['foo' => 'bar']);

        $this->setExpectedException('RuntimeException');
        $col->append(['bar' => 'baz']);
    }

    public function testCannotKsort()
    {
        $col = new Immutable(['foo' => 'bar']);

        $this->setExpectedException('RuntimeException');
        $col->ksort();
    }

    public function testCannotMerge()
    {
        $col = new Immutable(['foo' => 'bar']);

        $this->setExpectedException('RuntimeException');
        $col->merge(['bar' => 'baz']);
    }

    public function testCannotPad()
    {
        $col = new Immutable(['foo' => 'bar']);

        $this->setExpectedException('RuntimeException');
        $col->pad(10, 'bar');
    }

    public function testCannotReverse()
    {
        $col = new Immutable(['foo' => 'bar']);

        $this->setExpectedException('RuntimeException');
        $col->reverse();
    }

    public function testCannotRsort()
    {
        $col = new Immutable(['foo' => 'bar']);

        $this->setExpectedException('RuntimeException');
        $col->rsort();
    }

    public function testCannotSort()
    {
        $col = new Immutable(['foo' => 'bar']);

        $this->setExpectedException('RuntimeException');
        $col->sort();
    }

    public function testCannotUsort()
    {
        $col = new Immutable(['foo' => 'bar']);

        $this->setExpectedException('RuntimeException');
        $col->usort(function ($var) { return false; });
    }

    public function testCannotAdd()
    {
        $col = new Immutable(['foo' => 'bar']);

        $this->setExpectedException('RuntimeException');
        $col->add('bar');
    }

    public function testCannotAddIfNotInSet()
    {
        $col = new Immutable(['foo' => 'bar']);

        $this->setExpectedException('RuntimeException');
        $col->addIfNotInSet('bar');
    }

    public function testCannotSet()
    {
        $col = new Immutable(['foo' => 'bar']);

        $this->setExpectedException('RuntimeException');
        $col->set('bar', 'baz');
    }

    public function testCannotRemove()
    {
        $col = new Immutable(['foo' => 'bar']);

        $this->setExpectedException('RuntimeException');
        $col->remove('foo');
    }
}
