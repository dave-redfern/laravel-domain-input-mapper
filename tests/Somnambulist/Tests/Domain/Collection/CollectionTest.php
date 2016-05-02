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

use Somnambulist\Domain\Collection\Collection;
use Somnambulist\Domain\Collection\Immutable;

class TestClass1
{
    public function toArray()
    {
        return ['foo' => 'bar',];
    }
}
class TestClass2
{
    public function asArray()
    {
        return ['foo' => 'bar',];
    }
}

class TestClass3
{
    public function toJson()
    {
        return '{"foo":"bar"}';
    }
}
class TestClass4
{
    public function asJson()
    {
        return '{"foo":"bar"}';
    }
}

/**
 * Class CollectionTest
 *
 * @package    Somnambulist\Tests\Domain\Collection
 * @subpackage Somnambulist\Tests\Domain\Collection\CollectionTest
 */
class CollectionTest extends \PHPUnit_Framework_TestCase
{

    public function testConstructor()
    {
        $col = new Collection();

        $this->assertInstanceOf(Collection::class, $col);
        $this->assertEmpty($col);
    }

    public function testConstructorWithArray()
    {
        $col = new Collection(['foo' => 'bar']);

        $this->assertCount(1, $col);
    }

    public function testConstructorWithCollection()
    {
        $col = new Collection(['foo' => 'bar']);
        $col2 = new Collection($col);

        $this->assertCount(1, $col2);
    }

    public function testConstructorWithStdClass()
    {
        $col = new \stdClass;
        $col->foo = 'bar';
        $col2 = new Collection($col);

        $this->assertCount(1, $col2);
    }

    public function testConstructorWithArrayObject()
    {
        $col = new \ArrayObject(['foo' => 'bar']);
        $col2 = new Collection($col);

        $this->assertCount(1, $col2);
    }

    public function testConstructorWithScalar()
    {
        $col = new Collection('bar');

        $this->assertCount(1, $col);
    }

    public function testConstructorWithObjectToArray()
    {
        $col = new Collection(new TestClass1());

        $this->assertCount(1, $col);
    }

    public function testConstructorWithObjectAsArray()
    {
        $col = new Collection(new TestClass2());

        $this->assertCount(1, $col);
    }

    public function testConstructorWithObjectToJson()
    {
        $col = new Collection(new TestClass3());

        $this->assertCount(1, $col);
    }

    public function testConstructorWithObjectAsJson()
    {
        $col = new Collection(new TestClass4());

        $this->assertCount(1, $col);
    }

    public function testConstructorWithNestedObjects()
    {
        $obj  = new \stdClass();
        $obj2 = new \stdClass();
        $obj3 = new \stdClass();
        $obj2->bar = 'baz';
        $obj2->bar = $obj3;

        $obj->foo = $obj2;

        $col = new Collection($obj);

        $this->assertCount(1, $col);
    }

    public function testCanRestoreState()
    {
        $col  = new Collection(new TestClass4());
        $test = var_export($col, true);

        eval('$col2 = ' . $test . ';');

        $this->assertInstanceOf(Collection::class, $col2);
        $this->assertCount(1, $col2);
        $this->assertFalse($col2->isModified());
    }

    public function testFreezeReturnsImmutable()
    {
        $col = new Collection();

        $this->assertInstanceOf(Immutable::class, $col->freeze());
    }

    public function testReset()
    {
        $col = new Collection(new TestClass4());

        $this->assertCount(1, $col);
        $col->reset();
        $this->assertCount(0, $col);
    }

    public function testMagicIsset()
    {
        $col = new Collection(new TestClass4());

        $this->assertTrue(isset($col->foo));
        $this->assertFalse(isset($col->bar));
    }

    public function testMagicSetGet()
    {
        $col = new Collection();
        $col->foo = 'bar';

        $this->assertEquals('bar', $col->foo);
    }

    public function testMagicUnset()
    {
        $col = new Collection();
        $col->foo = 'bar';

        $this->assertEquals('bar', $col->foo);
        unset($col->foo);
        $this->assertObjectNotHasAttribute('foo', $col);
    }

    public function testToArray()
    {
        $col = new Collection(new TestClass4());
        $arr = $col->toArray();

        $this->assertInternalType('array', $arr);
        $this->assertEquals(['foo' => 'bar'], $arr);
    }

    public function testSetModified()
    {
        $col = new Collection(new TestClass4());
        $col->bar = 'too';

        $this->assertTrue($col->isModified());
        $col->setModified(false);
        $this->assertFalse($col->isModified());
    }

    public function testCount()
    {
        $col = new Collection(new TestClass4());
        $col->bar = 'too';

        $this->assertCount(2, $col);
        $this->assertEquals(2, $col->count());
    }

    public function testOffsetExists()
    {
        $col = new Collection(new TestClass4());
        $col->bar = 'too';

        $this->assertTrue(isset($col['bar']));
        $this->assertFalse(isset($col['dog']));
    }

    public function testOffsetGetSet()
    {
        $col = new Collection(new TestClass4());
        $col['bar'] = 'too';

        $this->assertEquals('too', $col['bar']);
    }

    public function testOffsetUnset()
    {
        $col = new Collection(new TestClass4());
        $col['bar'] = 'too';
        unset($col['bar']);

        $this->assertObjectNotHasAttribute('bar', $col);
    }

    public function testIterator()
    {
        $col = new Collection(new TestClass4());
        $col['bar'] = 'too';

        foreach ($col as $key => $value) {

        }

        $this->assertTrue(true);
    }

    public function testGetIterator()
    {
        $col = new Collection(new TestClass4());
        $col['bar'] = 'too';

        $this->assertInstanceOf(\ArrayIterator::class, $col->getIterator());
    }

    public function testSerialize()
    {
        $col = new Collection(new TestClass4());
        $col['bar'] = 'too';

        $tmp = serialize($col);
        $col = unserialize($tmp);

        $this->assertInstanceOf(Collection::class, $col);
        $this->assertCount(2, $col);
    }

    public function testIsValueInSet()
    {
        $col = new Collection(new TestClass4());
        $col['bar'] = 'too';

        $this->assertTrue($col->isValueInSet('bar'));
        $this->assertFalse($col->isValueInSet('baz'));
    }

    public function testAppend()
    {
        $col = new Collection(new TestClass4());
        $col2 = ['bar' => 'too'];

        $this->assertCount(1, $col);
        $col->append($col2);
        $this->assertCount(2, $col);
    }

    public function testFind()
    {
        $col        = new Collection(new TestClass4());
        $col['bar'] = 'too';

        $this->assertNotFalse($col->find('bar'));
        $this->assertFalse($col->find('baz'));
    }

    public function testFlatten()
    {
        $obj      = new \stdClass();
        $obj->bar = 'baz';

        $col = new Collection(new TestClass1());
        $col->set('foobar', new Collection($obj));
        $col->get('foobar')->set('foobar2', new Collection(['you' => 'me']));

        $tmp = $col->flatten();

        $this->assertCount(3, $tmp);
        $this->assertEquals(['foo' => 'bar', 'bar' => 'baz', 'you' => 'me'], $tmp->toArray());
    }

    public function testKeys()
    {
        $col = new Collection([
            'bar' => 'baz',
            'baz' => 'foo',
            'foobar' => 'baz',
        ]);

        $this->assertEquals(['bar', 'baz', 'foobar'], $col->keys()->toArray());
    }

    public function testValues()
    {
        $col = new Collection([
            'bar' => 'baz',
            'baz' => 'foo',
            'foobar' => 'baz',
        ]);

        $this->assertEquals(['baz', 'foo', 'baz'], $col->values()->toArray());
    }

    public function testUnique()
    {
        $col = new Collection([
            'bar' => 'baz',
            'baz' => 'foo',
            'foobar' => 'baz',
        ]);

        $this->assertEquals(['bar' => 'baz', 'baz' => 'foo'], $col->unique()->toArray());
    }

    public function testKeysSearch()
    {
        $col = new Collection([
            'bar' => 'baz',
            'baz' => 'foo',
            'foobar' => 'baz',
        ]);

        $this->assertEquals(['baz'], $col->keys('foo')->toArray());
        $this->assertEquals(['bar', 'foobar'], $col->keys('baz')->toArray());
    }

    public function testReverse()
    {
        $col = new Collection([
            'bar' => 'baz',
            'baz' => 'foo',
            'foobar' => 'baz',
        ]);

        $this->assertEquals(['foobar' => 'baz', 'baz' => 'foo', 'bar' => 'baz'], $col->reverse()->toArray());
    }

    public function testPad()
    {
        $col = new Collection();
        $col->pad(10, 'a');

        $this->assertCount(10, $col);
    }

    public function testFindByRegex()
    {
        $col = new Collection([
            'test-1' => 'test',
            'test-2' => 'test',
            'test-abc' => 'test',
            'test-abe' => 'test',
            'test-abf' => 'test',
            'test-3' => 'test',
            'test-4' => 'test',
            'test-10' => 'test',
            'test-zad' => 'test',
        ]);

        $this->assertCount(9, $col);

        $tmp = $col->findByRegex('/^test-\d+/')->toArray();

        $this->assertCount(5, $tmp);
    }

    public function testKsort()
    {
        $col = new Collection([
            'ztest' => 'test',
            'atest' => 'test',
            'gtest' => 'test',
            'etest' => 'test',
            'utest' => 'test',
            'btest' => 'test',
        ]);

        $col->ksort();

        $this->assertEquals(['atest', 'btest', 'etest', 'gtest', 'utest', 'ztest'], $col->keys()->toArray());
    }

    public function testSort()
    {
        $col = new Collection([
            'ztest' => 'test',
            'atest' => 'testa',
            'gtest' => 'tests',
            'etest' => 'testd',
            'utest' => 'teste',
            'btest' => 'testp',
        ]);

        $col->sort();

        $expected = [
            'ztest' => 'test',
            'atest' => 'testa',
            'etest' => 'testd',
            'utest' => 'teste',
            'btest' => 'testp',
            'gtest' => 'tests',
        ];

        $this->assertEquals($expected, $col->toArray());
    }

    public function testAll()
    {
        $col = new Collection([
            'test-1' => 'test',
            'test-2' => 'test',
            'test-abc' => 'test',
            'test-abe' => 'test',
            'test-abf' => 'test',
            'test-3' => 'test',
            'test-4' => 'test',
            'test-10' => 'test',
            'test-zad' => 'test',
        ]);

        $this->assertInternalType('array', $col->all());
    }

    public function testGet()
    {
        $col = new Collection([
            'test-1' => 'test',
            'test-2' => 'test',
            'test-abc' => 'test',
            'test-abe' => 'test',
        ]);

        $this->assertEquals('test', $col->get('test-abe'));
        $this->assertNull($col->get('abe'));
    }

    public function testHas()
    {
        $col = new Collection([
            'test-1' => 'test',
            'test-2' => 'test',
            'test-abc' => 'test',
            'test-abe' => 'test',
        ]);

        $this->assertTrue($col->has('test-abe'));
        $this->assertFalse($col->has('abe'));
    }

    public function testHasValueFor()
    {
        $col = new Collection([
            'test-1' => 'test',
            'test-2' => null,
            'test-abc' => false,
            'test-abe' => 'test',
        ]);

        $this->assertTrue($col->hasValueFor('test-abe'));
        $this->assertFalse($col->hasValueFor('test-2'));
        $this->assertFalse($col->hasValueFor('test-abc'));
    }

    public function testContains()
    {
        $col = new Collection([
            'test-1' => 'test',
            'test-2' => null,
            'test-abc' => false,
            'test-abe' => 'test',
        ]);

        $this->assertTrue($col->contains('test'));
        $this->assertFalse($col->contains(1234));
    }

    public function testAdd()
    {
        $col = new Collection();
        $col->add('value')->add('value2');

        $this->assertCount(2, $col);
    }

    public function testAddIfNotInSet()
    {
        $col = new Collection();
        $col->add('value')->add('value2');
        $col->addIfNotInSet('value');

        $this->assertCount(2, $col);
    }

    public function testSet()
    {
        $col = new Collection();
        $col->set('key', 'value');

        $this->assertTrue($col->has('key'));
        $this->assertEquals('value', $col->get('key'));
    }

    public function testRemove()
    {
        $col = new Collection();
        $col->set('key', 'value');

        $this->assertTrue($col->has('key'));

        $col->remove('key');

        $this->assertCount(0, $col);
    }

    public function testRemoveElement()
    {
        $col = new Collection();
        $col->set('key', 'value');

        $this->assertTrue($col->has('key'));

        $col->removeElement('value');

        $this->assertCount(0, $col);
    }

    public function testFirst()
    {
        $col = new Collection([
            'test-1' => 'test',
            'test-2' => null,
            'test-abc' => false,
            'test-abe' => 'test',
        ]);

        $this->assertEquals('test', $col->first());
    }

    public function testLast()
    {
        $col = new Collection([
            'test-1' => 'test',
            'test-2' => null,
            'test-abc' => false,
            'test-abe' => 'test-abc',
        ]);

        $this->assertEquals('test-abc', $col->last());
    }

    public function testImplode()
    {
        $col = new Collection([
            'test-1' => 'test',
            'test-2' => null,
            'test-abc' => false,
            'test-abe' => 'test-abc',
        ]);

        $this->assertEquals('test|||test-abc', $col->implode('|'));
    }

    public function testImplodeKeys()
    {
        $col = new Collection([
            'test-1' => 'test',
            'test-2' => null,
            'test-abc' => false,
            'test-abe' => 'test-abc',
        ]);

        $this->assertEquals('test-1|test-2|test-abc|test-abe', $col->implodeKeys('|'));
    }
}
