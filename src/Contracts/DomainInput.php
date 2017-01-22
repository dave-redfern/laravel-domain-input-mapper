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
use Illuminate\Http\UploadedFile;

/**
 * Class DomainInput
 *
 * Input transfer object abstracting request method (http, cli etc) from
 * the input / files. This container is read-only and should not be modified
 * once created.
 *
 * @package    Somnambulist\Domain
 * @subpackage Somnambulist\Domain\DomainInput
 * @author     Dave Redfern
 */
interface DomainInput
{

    /**
     * The input collection / array of input data
     *
     * @return Immutable
     */
    public function inputs();

    /**
     * The uploaded files collection / array
     *
     * @return Immutable
     */
    public function files();

    /**
     * Alias for input
     *
     * @param string $key
     * @param mixed  $default
     *
     * @return mixed
     */
    public function get($key, $default = null);

    /**
     * Fetch an input parameter
     *
     * @param string $key
     * @param mixed  $default
     *
     * @return mixed
     */
    public function input($key, $default = null);

    /**
     * Get an uploaded file by parameter name
     *
     * @param string $key
     *
     * @return UploadedFile
     */
    public function file($key);
}
