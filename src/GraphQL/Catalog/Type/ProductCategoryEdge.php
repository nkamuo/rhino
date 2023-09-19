<?php

namespace App\GraphQL\Catalog\Type;

use Overblog\GraphQLBundle\Relay\Connection\Output\Edge;
use Overblog\GraphQLBundle\Annotation as GQL;

#[GQL\Relay\Edge(
    node: 'ProductCategory',
)]
class ProductCategoryEdge extends Edge
{
}
