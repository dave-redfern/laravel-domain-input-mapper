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
use Illuminate\Http\Request;

/**
 * Class DomainInputFactory
 *
 * Creates domain input transfer objects from various contexts / sources.
 *
 * @package    Somnambulist\Domain
 * @subpackage Somnambulist\Domain\DomainInputFactory
 */
class DomainInputFactory
{

    /**
     * @param Collection|null $inputs
     * @param Collection|null $files
     *
     * @return DomainInput
     */
    public function create(Collection $inputs = null, Collection $files = null)
    {
        return new DomainInput($inputs, $files);
    }

    /**
     * @param Request $request
     *
     * @return DomainInput
     */
    public function createFromHttpRequest(Request $request)
    {
        $inputs = new Collection($request->input());
        $files  = new Collection($request->allFiles());

        return new DomainInput($inputs, $files);
    }
}
