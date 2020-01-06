<?php declare(strict_types=1);

namespace Gitnik\JmsTypedPropertiesDriver\Tests\Stubs;

use JMS\Serializer\Annotation\SerializedName;

class MakeWithDifferentName {
    /**
     * @SerializedName("makeName")
     */
    public string $name;
}

