<?php declare(strict_types=1);

namespace Gitnik\JmsTypedPropertiesDriver\Tests\Stubs;

use JMS\Serializer\Annotation\Type;

class Car {
    public int $horsePower;
    public Make $make;
    public string $color;
    /** @Type("DateTime<'Y-m-d'>") */
    public \DateTime $releaseDate;
}
