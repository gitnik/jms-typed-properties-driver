<?php declare(strict_types=1);

namespace Gitnik\JmsTypedPropertiesDriver;

use JMS\Serializer\Metadata\PropertyMetadata;
use JMS\Serializer\Type\Parser;
use JMS\Serializer\Type\ParserInterface;
use Metadata\ClassMetadata;
use Metadata\Driver\DriverInterface;

class TypedPropertiesDriver implements DriverInterface
{
    private DriverInterface $defaultDriver;
    private Parser $typeParser;

    public function __construct(
        DriverInterface $defaultDriver,
        ParserInterface $parser = null
    ) {
        $this->defaultDriver = $defaultDriver;
        $this->typeParser = $parser ?? new Parser();
    }

    public function loadMetadataForClass(\ReflectionClass $class): ?ClassMetadata
    {
        $classMetadata = $this->defaultDriver->loadMetadataForClass($class);

        $className = $class->getName();

        foreach ($class->getProperties() as $property) {
            // skip properties on different classes or non-typed properties
            if ($property->class !== $className || !$property->getType() instanceof \ReflectionNamedType) {
                continue;
            }

            if (!array_key_exists($property->getName(), $classMetadata->propertyMetadata)) {
                continue;
            }

            /**
             * @var PropertyMetadata $propertyMetadata
             */
            $propertyMetadata = $classMetadata->propertyMetadata[$property->getName()];

            $propertyMetadata->setType(
                $this->typeParser->parse(
                    $property->getType()->getName()
                )
            );
        }

        return $classMetadata;
    }
}
