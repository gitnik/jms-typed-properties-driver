## Warning
**Do not use this in a production environment. While the base cases work, I am sure there are plenty of untested edge-cases**


## Usage

#### Deserialization:
```php
class Make {
    private string $name;
}

class Car {
    private string $color;
    private Make $make;
}

$inputData = [
    'color' => 'red',
    'make' => [
        'name' => 'Tesla'
    ]
];

$serializer = SerializerBuilder::create()
    ->setMetadataDriverFactory(new TypedPropertiesDriverFactory())
    ->build();

$car = $serializer->deserialize(
   json_encode($inputData),
   Car::class,
   'json'

// interact with your Car object

);
```


#### Serialization:
```php
class Make {
    public string $name;
}

class Car {
    public string $color;
    public Make $make;
}

$make = new Make();
$make->name = 'Tesla';

$car = new Car();
$car->make = $make;
$car->color = 'red';

$serializer = SerializerBuilder::create()
    ->setMetadataDriverFactory(new TypedPropertiesDriverFactory())
    ->build();

$json = $serializer->serialize(
   $car,
   'json'

/*
{
    "color": "red",
    "make": {
        "name": "Tesla"
    }
}
*/
);
```


## How it works
JMS/Serializer has the concept of drivers, which are used to gather configuration information about an object it wants to (de)serialize.
An example of this would be the Annotation driver, which reads configuration via annotations.

What this library does is, wrap whatever driver is currently being used and enhance the information acquired by the "default" driver with type information gathered via reflection.

This means you can gradually migrate towards type-hinted properties on a property-by-property basis and also mix different ways of configuration, like so:

```php
class Car {
    /**
     * @SerializedName("colour") 
     */
    private string $color;
}
```


## Limitations:
1. While it can handle null values for (de)serialization, you are not allowed to omit properties during deserialization that have been typed-hinted, as those must always be initialized before they can be accessed
2. There has been a long-standing issue with the parent library regarding coercing values instead of type-checking them (https://github.com/schmittjoh/serializer/issues/561). This library has no influence on this behaviour