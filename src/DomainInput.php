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
use Illuminate\Http\UploadedFile;

/**
 * Class DomainInput
 *
 * Domain input transfer object abstracting request method (http, cli etc) from
 * the input / files. This container is read-only and should not be modified
 * once created.
 *
 * @package    Somnambulist\Domain
 * @subpackage Somnambulist\Domain\DomainInput
 * @author     Dave Redfern
 */
class DomainInput implements DomainInputContract
{

    /**
     * @var Immutable
     */
    protected $inputs;

    /**
     * @var Immutable
     */
    protected $files;



    /**
     * Constructor.
     *
     * @param Collection $inputs
     * @param Collection $files
     */
    public function __construct(Collection $inputs = null, Collection $files = null)
    {
        if (!$inputs) $inputs = new Immutable();
        if (!$files)  $files  = new Immutable();

        $this->inputs = $inputs->freeze();
        $this->files  = $files->freeze();
    }

    /**
     * @return Immutable
     */
    public function inputs()
    {
        return $this->inputs;
    }

    /**
     * @return Immutable
     */
    public function files()
    {
        return $this->files;
    }

    /**
     * Alias for input
     *
     * @param string $key
     * @param mixed  $default
     *
     * @return mixed
     */
    public function get($key, $default = null)
    {
        return $this->input($key, $default);
    }

    /**
     * @param string $key
     *
     * @return boolean
     */
    public function has($key)
    {
        return $this->inputs->has($key);
    }

    /**
     * Fetch an input parameter
     *
     * @param string $key
     * @param mixed  $default
     *
     * @return mixed
     */
    public function input($key, $default = null)
    {
        return data_get($this->inputs, $key, $default);
    }

    /**
     * Get an uploaded file by parameter name
     *
     * @param string $key
     *
     * @return UploadedFile
     */
    public function file($key)
    {
        return data_get($this->files, $key, null);
    }
}
