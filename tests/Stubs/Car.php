<?php declare(strict_types=1);

namespace Gitnik\JmsTypedPropertiesDriver\Tests\Stubs;

use JMS\Serializer\Annotation\Exclude;

class Car {
    public int $horsePower;
    public Make $make;
    public string $color;
    /** @Exclude() */
    public string $hidden;
}
