<?php
namespace App\GraphQL\Shipment\ResolverMap;

use Overblog\GraphQLBundle\Resolver\ResolverMapInterface;

class ShipmentResolverMap implements ResolverMapInterface
{

    public function covered(
        string|null $typeName = null
    ): array {
        die("covered -> CALLED");
        return [];
    }

    public function isResolvable(
        string $typeName,
        string $fieldName
    ): bool {
        die("covered -> CALLED");

        return false;
    }


    public function resolve(
        string $typeName,
        string $fieldName
    ): \Closure {

        die("resolve -> CALLED");
        return fn () => null;
    }
}
