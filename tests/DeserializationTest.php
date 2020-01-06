<?php declare(strict_types=1);

namespace Gitnik\JmsTypedPropertiesDriver\Tests;

use Doctrine\Common\Annotations\AnnotationRegistry;
use Gitnik\JmsTypedPropertiesDriver\Tests\Stubs\Car;
use Gitnik\JmsTypedPropertiesDriver\Tests\Stubs\MakeWithDifferentName;
use Gitnik\JmsTypedPropertiesDriver\TypedPropertiesDriverFactory;
use JMS\Serializer\SerializerBuilder;
use JMS\Serializer\SerializerInterface;
use PHPUnit\Framework\TestCase;


class DeserializationTest extends TestCase
{
    private SerializerInterface $serializer;

    public function setUp(): void
    {
        parent::setUp();

        AnnotationRegistry::registerLoader('class_exists');

        $serializerBuilder = SerializerBuilder::create();
        $this->serializer = $serializerBuilder
            ->setMetadataDriverFactory(new TypedPropertiesDriverFactory())
            ->build();

    }

    public function testSerializationWorks()
    {
        $inputData = [
            'horsePower' => 200,
            'make' => [
                'name' => 'BMW',
                'yearFounded' => 1916
            ],
            'color' => 'blue'
        ];

        /**
         * @var Car $car
         */
        $car = $this->serializer->deserialize(
            json_encode($inputData),
            Car::class,
            'json'
        );

        self::assertSame(200, $car->horsePower);
        self::assertSame('BMW', $car->make->name);
        self::assertSame(1916, $car->make->yearFounded);
        self::assertSame('blue', $car->color);
    }

    public function testWorksWithNullables()
    {
        $inputData = [
            'horsePower' => 200,
            'make' => [
                'name' => 'BMW',
                'yearFounded' => null
            ],
            'color' => 'blue'
        ];

        /**
         * @var Car $car
         */
        $car = $this->serializer->deserialize(
            json_encode($inputData),
            Car::class,
            'json'
        );

        self::assertSame(200, $car->horsePower);
        self::assertSame('BMW', $car->make->name);
        self::assertNull($car->make->yearFounded);
        self::assertSame('blue', $car->color);
    }

    public function testWorksWithAnnotations()
    {
        $inputData = [
            'makeName' => 'BMW'
        ];

        /**
         * @var MakeWithDifferentName $make
         */
        $make = $this->serializer->deserialize(
            json_encode($inputData),
            MakeWithDifferentName::class,
            'json'
        );

        self::assertSame('BMW', $make->name);
    }
}
