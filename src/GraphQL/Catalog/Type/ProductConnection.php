<?php
namespace App\GraphQL\Catalog\Type;
use Overblog\GraphQLBundle\Relay\Connection\Output\Connection;
use Overblog\GraphQLBundle\Annotation as GQL;

#[GQL\Relay\Connection(
    edge: 'ProductEdge',
)]
class ProductConnection extends Connection{

}