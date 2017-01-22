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

use Somnambulist\Collection\Collection;
use Somnambulist\Domain\Contracts\DomainInput as DomainInputContract;
use Somnambulist\Domain\Contracts\DomainInputMapper as DomainInputMapperContract;

/**
 * Class AggregateMapper
 *
 * Allows a collection of input mappers to be run in sequence on an Entity and
 * DomainInput. This allows the mappers to be kept small and very specific to the
 * data they are mapping e.g. this could be a collection of:
 *
 *  * collection mapping
 *  * image/file handling
 *  * encryption/decryption etc.
 *
 * @package    Somnambulist\Domain
 * @subpackage Somnambulist\Domain\AggregateMapper
 * @author     Dave Redfern
 */
class AggregateMapper implements DomainInputMapperContract
{

    /**
     * @var Collection|DomainInputMapperContract[]
     */
    protected $mappers;



    /**
     * Constructor.
     *
     * @param array|DomainInputMapperContract[] $mappers
     */
    public function __construct(array $mappers = [])
    {
        $this->mappers = new Collection();

        foreach ($mappers as $mapper) {
            $this->addMapper($mapper);
        }
    }

    /**
     * @param DomainInputContract $input
     * @param object              $entity
     */
    public function map(DomainInputContract $input, $entity)
    {
        foreach ($this->mappers as $mapper) {
            if ($mapper->supports($entity)) {
                $mapper->map($input, $entity);
            }
        }
    }

    /**
     * Override to make the aggregate mapper class specific
     *
     * @param object $entity
     *
     * @return boolean
     */
    public function supports($entity)
    {
        return true;
    }



    /**
     * @return Collection
     */
    public function getMappers()
    {
        return $this->mappers;
    }

    /**
     * @param DomainInputMapperContract $mapper
     *
     * @return $this
     */
    public function addMapper(DomainInputMapperContract $mapper)
    {
        $this->mappers->add($mapper);

        return $this;
    }

    /**
     * @param DomainInputMapperContract $mapper
     *
     * @return $this
     */
    public function removeMapper(DomainInputMapperContract $mapper)
    {
        $this->mappers->removeElement($mapper);

        return $this;
    }
}
