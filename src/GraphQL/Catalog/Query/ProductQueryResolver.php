<?php

namespace App\GraphQL\Catalog\Query;


use App\Entity\Catalog\Product;
use App\GraphQL\Catalog\Type\ProductConnection;
use App\GraphQL\Catalog\Type\ProductEdge;
use App\Repository\Catalog\ProductRepository;
use App\Util\Doctrine\QueryBuilderHelper;
use Overblog\GraphQLBundle\Annotation as GQL;
use Overblog\GraphQLBundle\Annotation\Query;
use Overblog\GraphQLBundle\Definition\Argument;
use Overblog\GraphQLBundle\Error\UserError;
use Overblog\GraphQLBundle\Relay\Connection\ConnectionBuilder;
use Overblog\GraphQLBundle\Relay\Connection\PageInfoInterface;
use Overblog\GraphQLBundle\Relay\Connection\Paginator;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Uid\Ulid;


#[GQL\Provider()]
class ProductQueryResolver
{

    public function __construct(
        private ProductRepository $productRepository,
        private Security $security,
    ) {
    }



    #[Query(name: "get_product_item",)]
    #[GQL\Arg(
        name: 'name',
        type: 'Ulid'
    )]
    public function getProductItem(
        #[GQL\Arg(type: 'Ulid')] Ulid $id
    ): Product {

        $product = $this->productRepository->find($id);
        if ($product === null) {
            throw new UserError(
                message: "Cannot find product with [id:$id]"
            );
        }

        if (!$this->security->isGranted('view', $product)) {
            throw new UserError(
                message: "Permision Denied: You may not view this resource"
            );
        }



        return $product;
    }

    #[GQL\Query(name: "get_product_list")]
    public function getProductConnection(
        ?int $first,
        ?String $after,
        ?String $filter,
    ): ProductConnection {


        $cb = new ConnectionBuilder(
            null,
            fn ($edges, PageInfoInterface $pageInfo) => new ProductConnection($edges, $pageInfo),
            fn (string $coursor, Product $product, int $index) => new ProductEdge($coursor, $product)
        );

        $qb = $this->productRepository->createQueryBuilder('product');
        QueryBuilderHelper::applyCriteria($qb, $filter, 'product');

        $total = fn () => (int) (clone $qb)->select('COUNT(product.id)')->getQuery()->getSingleScalarResult();
        $paginator = new Paginator(function (?int $offset, ?int $limit) use ($qb) {
            return $qb->getQuery()->getResult();
        }, false, $cb);

        return $paginator->auto(new Argument(['first' => $first, 'after' => $after]), $total);
    }
}
