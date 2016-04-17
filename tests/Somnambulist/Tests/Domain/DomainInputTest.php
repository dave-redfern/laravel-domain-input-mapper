<?php

namespace Somnambulist\Tests\Domain;

use Illuminate\Http\UploadedFile;
use Somnambulist\Domain\Collection\Collection;
use Somnambulist\Domain\Collection\Immutable;
use Somnambulist\Domain\DomainInput;

/**
 * Class DomainInputTest
 *
 * @package    Somnambulist\Tests\Domain
 * @subpackage Somnambulist\Tests\Domain\DomainInputTest
 */
class DomainInputTest extends \PHPUnit_Framework_TestCase
{

    public function testGetInput()
    {
        $input = new DomainInput(new Collection(['foo' => ['bar' => 'baz']]));

        $this->assertEquals('baz', $input->get('foo.bar'));
        $this->assertEquals('baz', $input->input('foo.bar'));
    }

    public function testGetInputReturnsDefault()
    {
        $input = new DomainInput(new Collection(['foo' => ['bar' => 'baz']]));

        $this->assertEquals('baz', $input->get('bar', 'baz'));
        $this->assertEquals('baz', $input->input('bar', 'baz'));
    }

    public function testGetFile()
    {
        $input = new DomainInput(new Collection(), new Collection([
            'file' => new UploadedFile(__FILE__, __FILE__, 'plain/text'),
        ]));

        $this->assertInstanceOf(UploadedFile::class, $input->file('file'));
    }

    public function testGetFileReturnsNullIfDoesNotExist()
    {
        $input = new DomainInput(new Collection([
            'file' => new UploadedFile(__FILE__, __FILE__, 'plain/text'),
        ]));

        $this->assertNull($input->file('bar'));
    }

    public function testPassedCollectionsAreConvertedToImmutable()
    {
        $input = new DomainInput(new Collection(), new Collection());

        $this->assertInstanceOf(Immutable::class, $input->inputs());
        $this->assertInstanceOf(Immutable::class, $input->files());
    }
}
