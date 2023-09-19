<?php

namespace App\GraphQL\Catalog\Resolver;


use App\Entity\Catalog\ProductCategory;
use App\GraphQL\Catalog\Type\ProductCategoryConnection;
use App\GraphQL\Catalog\Type\ProductCategoryEdge;
use App\Repository\Catalog\ProductCategoryRepository;
use App\Service\Identity\CodeGeneratorInterface;
use App\Util\Doctrine\QueryBuilderHelper;
use Doctrine\ORM\EntityManagerInterface;
use Overblog\GraphQLBundle\Annotation as GQL;
use Overblog\GraphQLBundle\Annotation\Query;
use Overblog\GraphQLBundle\Definition\Argument;
use Overblog\GraphQLBundle\Error\UserError;
use Overblog\GraphQLBundle\Relay\Connection\ConnectionBuilder;
use Overblog\GraphQLBundle\Relay\Connection\PageInfoInterface;
use Overblog\GraphQLBundle\Relay\Connection\Paginator;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Uid\Ulid;


#[GQL\Provider(
    targetQueryTypes: ['Query', 'ClientQuery', 'DriverQuery', 'AdminQuery'],
)]
class DefaultProductCategoryResolver
{

    public function __construct(
        private EntityManagerInterface $entityManager,
        private ProductCategoryRepository $productCategoryRepository,
        private Security $security,
    ) {
    }


    #[Query(name: "get_product_category_item",)]
    #[GQL\Arg(
        name: 'id',
        type: 'Ulid'
    )]
    public function getProductCategoryItem(
        Ulid $id
    ): ProductCategory {
        return $this->getProductCategoryById($id);
    }

    #[GQL\Query(name: "get_product_category_list")]
    public function getProductCategoryConnection(
        ?int $first,
        ?String $after,
        ?String $filter,
        ?String $sort,
    ): ProductCategoryConnection {


        $cb = new ConnectionBuilder(
            null,
            fn ($edges, PageInfoInterface $pageInfo) => new ProductCategoryConnection($edges, $pageInfo),
            fn (string $coursor, ProductCategory $productCategory, int $index) => new ProductCategoryEdge($coursor, $productCategory)
        );

        $qb = $this->productCategoryRepository->createQueryBuilder('productCategory');
        QueryBuilderHelper::applyCriteria($qb, $filter, 'productCategory');

        $total = fn () => (int) (clone $qb)->select('COUNT(productCategory.id)')->getQuery()->getSingleScalarResult();
        $paginator = new Paginator(function (?int $offset, ?int $limit) use ($qb) {
            return $qb
                ->setFirstResult($offset)
                ->setMaxResults($limit)
                ->getQuery()
                ->getResult();
        }, false, $cb);

        return $paginator->auto(new Argument(['first' => $first, 'after' => $after]), $total);
    }




    private function getProductCategoryById(Ulid $id): ProductCategory
    {
        $vType = $this->productCategoryRepository->find($id);
        if (null == $vType) {
            throw new UserError("Could not find Product type with [id:{$id}]");
        }
        return $vType;
    }
}
