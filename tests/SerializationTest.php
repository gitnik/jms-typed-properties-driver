<?php declare(strict_types=1);

namespace Gitnik\JmsTypedPropertiesDriver\Tests;

use Doctrine\Common\Annotations\AnnotationRegistry;
use Gitnik\JmsTypedPropertiesDriver\Tests\Stubs\Car;
use Gitnik\JmsTypedPropertiesDriver\Tests\Stubs\Make;
use Gitnik\JmsTypedPropertiesDriver\Tests\Stubs\MakeWithDifferentName;
use Gitnik\JmsTypedPropertiesDriver\TypedPropertiesDriverFactory;

use JMS\Serializer\Annotation\SerializedName;
use JMS\Serializer\SerializerBuilder;
use JMS\Serializer\SerializerInterface;
use PHPUnit\Framework\TestCase;

class SerializationTest extends TestCase
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
        $make = new Make;
        $make->name = 'BMW';
        $make->yearFounded = 1916;

        $car = new Car;
        $car->horsePower = 200;
        $car->make = $make;
        $car->color = 'blue';

        $json = $this->serializer->serialize(
            $car,
            'json'
        );

        $expectedData = [
            'horsePower' => 200,
            'make' => [
                'name' => 'BMW',
                'yearFounded' => 1916
            ],
            'color' => 'blue'
        ];

        self::assertJsonStringEqualsJsonString(
            json_encode($expectedData),
            $json
        );
    }

    public function testWorksWithNullables()
    {
        $make = new Make;
        $make->name = 'BMW';
        $make->yearFounded = null;

        $car = new Car;
        $car->horsePower = 200;
        $car->make = $make;
        $car->color = 'blue';

        $json = $this->serializer->serialize(
            $car,
            'json'
        );

        $expectedData = [
            'horsePower' => 200,
            'make' => [
                'name' => 'BMW'
            ],
            'color' => 'blue'
        ];

        self::assertJsonStringEqualsJsonString(
            json_encode($expectedData),
            $json
        );
    }

    public function testWorksWithAnnotations()
    {
        $make = new MakeWithDifferentName;
        $make->name = 'BMW';

        $json = $this->serializer->serialize(
            $make,
            'json'
        );

        self::assertJsonStringEqualsJsonString(
            '{ "makeName": "BMW" }',
            $json
        );
    }
}
