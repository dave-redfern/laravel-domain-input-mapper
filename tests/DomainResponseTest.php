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

use Somnambulist\Collection\Collection;
use Somnambulist\Collection\Immutable;
use Somnambulist\Domain\DomainInput;
use Somnambulist\Domain\DomainResponse;

/**
 * Class DomainResponseTest
 *
 * @package    Somnambulist\Tests\Domain
 * @subpackage Somnambulist\Tests\Domain\DomainResponseTest
 * @author     Dave Redfern
 */
class DomainResponseTest extends \PHPUnit_Framework_TestCase
{

    public function testCreateResponse()
    {
        $response = new DomainResponse(new DomainInput(), new Collection(['foo' => 'bar']), new Collection(), 'ok');
        
        $this->assertInstanceOf(\Somnambulist\Domain\Contracts\DomainResponse::class, $response);
        $this->assertTrue($response->has('foo'));
        $this->assertEquals('ok', $response->status());
        $this->assertEquals('bar', $response->get('foo'));
        $this->assertInstanceOf(\Somnambulist\Domain\Contracts\DomainInput::class, $response->input());
        $this->assertInstanceOf(Immutable::class, $response->data());
        $this->assertInstanceOf(Immutable::class, $response->messages());
    }
}
