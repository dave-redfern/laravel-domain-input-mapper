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

namespace Somnambulist\Domain;

use Somnambulist\Domain\Collection\Collection;
use Somnambulist\Domain\Collection\Immutable;
use Somnambulist\Domain\Contracts\DomainInput as DomainInputContract;
use Somnambulist\Domain\Contracts\DomainResponse as DomainResponseContract;

/**
 * Class DomainResponse
 *
 * @package    Somnambulist\Domain
 * @subpackage Somnambulist\Domain\DomainResponse
 * @author     Dave Redfern
 */
class DomainResponse implements DomainResponseContract
{

    /**
     * @var Immutable
     */
    protected $data;

    /**
     * @var DomainInputContract
     */
    protected $input;

    /**
     * @var Immutable
     */
    protected $messages;

    /**
     * @var mixed
     */
    protected $status;



    /**
     * Constructor.
     *
     * @param DomainInputContract $input
     * @param Collection          $data
     * @param Collection          $messages
     * @param mixed               $status
     */
    public function __construct(DomainInputContract $input, Collection $data, Collection $messages, $status)
    {
        $this->input    = $input;
        $this->data     = $data->freeze();
        $this->messages = $messages->freeze();
        $this->status   = $status;
    }

    /**
     * @return Immutable
     */
    public function data()
    {
        return $this->data;
    }

    /**
     * @param string $key
     *
     * @return null|mixed
     */
    public function get($key)
    {
        return data_get($this->data, $key, null);
    }

    /**
     * @param string $key
     *
     * @return boolean
     */
    public function has($key)
    {
        return $this->data->has($key);
    }

    /**
     * @return DomainInputContract
     */
    public function input()
    {
        return $this->input;
    }

    /**
     * @return Immutable
     */
    public function messages()
    {
        return $this->messages;
    }

    /**
     * @return mixed
     */
    public function status()
    {
        return $this->status;
    }
}