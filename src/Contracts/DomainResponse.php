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

namespace Somnambulist\Domain\Contracts;

use Somnambulist\Collection\Immutable;

/**
 * Interface DomainResponse
 *
 * The implementation should be read-only with the domain response not being modified directly.
 * Modifications should be made by presenters / filters in the responder layer.
 *
 * Both data and messages should be immutable collections.
 *
 * @package    Somnambulist\Domain\Contracts
 * @subpackage Somnambulist\Domain\Contracts\DomainResponse
 * @author     Dave Redfern
 */
interface DomainResponse
{

    /**
     * Returns the full domain data in the response
     *
     * @return Immutable
     */
    public function data();

    /**
     * Gets a single piece of data from the response
     *
     * @param string $key
     *
     * @return mixed|null
     */
    public function get($key);

    /**
     * Returns true if response has this data
     *
     * @param string $key
     *
     * @return boolean
     */
    public function has($key);

    /**
     * Returns the originating DomainInput object
     *
     * @return DomainInput
     */
    public function input();

    /**
     * Returns the collection of messages generated by the domain
     *
     * @return Immutable
     */
    public function messages();

    /**
     * A status representation that the domain processed and set
     *
     * @return mixed
     */
    public function status();

}
