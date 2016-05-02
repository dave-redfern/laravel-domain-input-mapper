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
