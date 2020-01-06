<?php declare(strict_types=1);

namespace Gitnik\JmsTypedPropertiesDriver;

use Doctrine\Common\Annotations\Reader;
use JMS\Serializer\Builder\DefaultDriverFactory;
use JMS\Serializer\Builder\DriverFactoryInterface;
use JMS\Serializer\Naming\IdenticalPropertyNamingStrategy;
use Metadata\Driver\DriverInterface;

final class TypedPropertiesDriverFactory implements DriverFactoryInterface
{
    private DefaultDriverFactory $defaultDriverFactory;

    public function __construct(DefaultDriverFactory $defaultDriverFactory = null) {
        $this->defaultDriverFactory = $defaultDriverFactory ??
            new DefaultDriverFactory(new IdenticalPropertyNamingStrategy());
    }

    public function createDriver(array $metadataDirs, Reader $annotationReader): DriverInterface
    {
        return new TypedPropertiesDriver(
            $this->defaultDriverFactory->createDriver(
                $metadataDirs,
                $annotationReader
            )
        );
    }
}
