## Domain Input Mapper Library

This library provides an abstraction between a request object e.g. Http\Request and your domain entities.
Instead of passing the request directly, it is converted to a DomainInput object that then contains the
information to be mapped to the domain objects. The domain input contains read-only collections of the
request data and files.

For Http, the Laravel Illuminate UploadedFile and Request are used, however any request could be used.

### Requirements

 * PHP 5.5+
 * Illuminate/Http 5.2+
 * Illuminate/Support 5.2+

### Installation

Install using composer, or checkout / pull the files from github.com.

 * composer require somnambulist/laravel-domain-input-mapper

### Domain Input

The first component is DomainInput and the associated factory class: DomainInputFactory. The factory
contains methods to create from a HttpRequest or directly from passed collections.

The DomainInput object contains all input and file data that is to be mapped into an entity or
aggregate route in the domain. By abstracting away the request type, the domain mapping / processing
can be kept clean of implementation and more easily used in other contexts.

The DomainInput is composed of:

 * inputs
 * files

Both are converted from standard collections to Immutable collections. These should not be modified
once created as they now represent the request into the domain.

DomainInput has accessors for input (aliased to get()) and to file(). Both support the Laravel dot
notation to access nested arrays of data e.g.: object.type.file.

### Domain Response

When returning data back from the domain, it can be preferable to represent the results as a single
unit that includes:

 * transformed domain data
 * the transaction / domain status
 * any messages (e.g. errors / warnings)

A basic interface and implementation are provided that implement a Domain Response.

This response is read-only and utilises the Immutable collection for storing the domain data and any
messages. In addition, the original Domain Input is associated with the response. This ensures that
the originating input is available when further processing the domain data.

An important feature is that domain processing result is provided in this response. It does not need
to be "discovered" again by a view / responder layer. The status can be any data type that your
application requires, though either a string or integer are suggested.

As the response is read-only and built via the constructor, any domain data should be collected in
a Collection and this passed into the constructor.

### Domain Mapper

The last component is an interface and basic aggregate implementation for mapping the DomainInput
to your entity / aggregates. This is a very simple interface containing two methods:

 * map
 * supports

map performs the work and accepts the DomainInput and a pre-created entity. It is important to note
that the mapper is not intended to create the main root entity / aggregate. This should be provided
to it via a separate factory step before the data is mapped to it. With that said, mappers can create
the needed sub-entities that will be attached to the root.

supports is a simple check to see if the mapper supports the passed entity. This will usually be an
instanceof type check against the entity. This is used in an aggregate mapper to prevent unsupported
mappers from being called with the entity.

The mapper itself can be as complex or as simple as needed; the one thing to keep in mind is that it
should only perform mapping for a single entity or sub-set of the aggregate. For example: you have
an aggregate composed of an order with sub-entities of line-items, the customer, an address. Each of
these may require separate repositories or additional support logic. This can be encapsulated within
individual mappers and an OrderMapper aggregate used to map the whole input at one time.

### Example

#### The main order mapper

    use Somnambulist\Domain\Contracts\DataInputMapper as DataInputMapperContract;
    class OrderMapper implements DataInputMapperContract
    {

        /**
         * @param Input $input
         * @param Order $entity
         */
        public function map(Input $input, $entity)
        {
            $entity
                ->setProperty($input->get('order.property'))
                // ... do other mapping
            ;
        }

        /**
         * @return boolean
         */
        public function supports($entity)
        {
            return ($entity instanceof Order);
        }
    }

#### Order item mapper

    use Somnambulist\Domain\Contracts\DataInputMapper as DataInputMapperContract;
    class OrderItemMapper implements DataInputMapperContract
    {

        protected $factory;

        public function __construct(OrderFactory $factory)
        {
            $this->factory = $factory;
        }

        /**
         * @param Input $input
         * @param Order $entity
         */
        public function map(Input $input, $entity)
        {
            // look up existing items, or make new ones
            foreach ($input->get('order.item') as $item) {
                $orderItem = $this->factory->createOrderItem($entity);

                // item will be an array, convert to collection
                $item = new Immutable($item);
                $orderItem
                    ->setSomeProperty($item->get('some_property))
                    // ... do other mapping
                ;
            }
        }

        /**
         * @return boolean
         */
        public function supports($entity)
        {
            return ($entity instanceof Order);
        }
    }

#### Address mapper

    use Contracts\AddressableEntity;
    use Somnambulist\Domain\Contracts\DataInputMapper as DataInputMapperContract;
    class AddressMapper implements DataInputMapperContract
    {

        /**
         * @param Input $input
         * @param Order $entity
         */
        public function map(Input $input, $entity)
        {
            $address = new Address();
            $address
                ->setAddressLine1($input->get('address.address_line_1')
                //... assign the rest
            ;

            // addresses should be value objects so we'll check if it is the same
            // these methods will all be defined in the AddressableEntity interface.
            // The address being a value object will have an isSameAs method.
            if (!$entity->hasAddress() || !$entity->getAddress()->isSameAs($address)) {
                $entity->setAddress($address);
            }
        }

        /**
         * @return boolean
         */
        public function supports($entity)
        {
            return ($entity instanceof AddressableEntity);
        }
    }

#### Putting them all together

    class OrderAggregateMapper extends AggregateMapper
    {

    }

    // in an input handler / command (better defined in the DI container)
    $mapper = new OrderAggregateMapper([
        new OrderMapper(),
        new OrderItemMapper(new OrderFactory()),
        new AddressMapper(),
    ]);

    $input  = $inputFactory->createFromHttpRequest($request);
    $entity = new Order();

    $mapper->map($input, $entity);

The benefit of this approach is segregation and isolation of each piece. This makes each part
easier to test, easier to manage and in some instances, the mapper can be re-used (e.g. address).
The alternative to this would be a single handler that either has to receive an entity manager
instance or many repositories to do the same job and what if you don't want to map all the data
or you want to partially map the aggregate root in multiple steps? Each would have to be handled
where as through this approach, simply add the mappers you need at the various stages.

Finally: it is important to remember that the data input mapper is a part of your domain and not
part of the controller or context types. It should not be aware of HTTP or CLI specifics.
