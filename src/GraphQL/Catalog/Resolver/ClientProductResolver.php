<?php

namespace App\GraphQL\Catalog\Resolver;

use App\Entity\Account\User;
use App\Entity\Catalog\Product;
use App\Entity\Catalog\ProductCategory;
use App\Entity\Catalog\UserProduct;
use App\GraphQL\Catalog\Input\ProductCreationInput;
use App\GraphQL\Catalog\Input\ProductUpdateInput;
use App\GraphQL\Catalog\Type\ProductConnection;
use App\GraphQL\Catalog\Type\ProductEdge;
use App\Repository\Catalog\ProductCategoryRepository;
use App\Repository\Catalog\ProductRepository;
use App\Repository\Catalog\UserProductRepository;
use App\Util\Doctrine\QueryBuilderHelper;
use Doctrine\ORM\EntityManagerInterface;
use Overblog\GraphQLBundle\Annotation as GQL;
use Overblog\GraphQLBundle\Annotation\Query;
use Overblog\GraphQLBundle\Definition\Argument;
use Overblog\GraphQLBundle\Error\UserError;
use Overblog\GraphQLBundle\Relay\Connection\ConnectionBuilder;
use Overblog\GraphQLBundle\Relay\Connection\PageInfoInterface;
use Overblog\GraphQLBundle\Relay\Connection\Paginator;
use Symfony\Bridge\Doctrine\Types\UlidType;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Uid\Ulid;


#[GQL\Provider(
    targetQueryTypes: ['ClientQuery'],
    targetMutationTypes: ['ClientMutation']
)]
class ClientProductResolver
{

    public function __construct(
        private ProductCategoryRepository $productCategoryRepository,
        private UserProductRepository $productRepository,
        private EntityManagerInterface $entityManager,
        private Security $security,
    ) {
    }



    #[Query(name: "get_product_item",)]
    #[GQL\Arg(
        name: 'id',
        type: 'Ulid'
    )]
    public function getProductItem(
        #[GQL\Arg(type: 'Ulid')] Ulid $id
    ): Product {

        $product = $this->getProductById($id);

        // if (!$this->security->isGranted('view', $product)) {
        //     throw new UserError(
        //         message: "Permision Denied: You may not view this resource"
        //     );
        // }



        return $product;
    }

    #[GQL\Query(name: "get_product_list")]
    #[GQL\Access("isGranted('ROLE_USER')")]
    public function getProductConnection(
        ?int $first,
        ?String $after,
        ?String $filter,
        ?String $sort,
    ): ProductConnection {

        $user = $this->getUser();
        
        $cb = new ConnectionBuilder(
            null,
            fn ($edges, PageInfoInterface $pageInfo) => new ProductConnection($edges, $pageInfo),
            fn (string $coursor, Product $product, int $index) => new ProductEdge($coursor, $product)
        );

        $qb = $this->productRepository->createQueryBuilder('product');


        $qb
            ->innerJoin('product.owner', 'owner')
            ->andWhere("owner.id = :owner")
            ->setParameter("owner", $user->getId(), UlidType::NAME);

        QueryBuilderHelper::applyCriteria($qb, $filter, 'product');

        $total = fn () => (int) (clone $qb)->select('COUNT(product.id)')->getQuery()->getSingleScalarResult();
        $paginator = new Paginator(function (?int $offset, ?int $limit) use ($qb) {
            return $qb
                ->setFirstResult($offset)
                ->setMaxResults($limit)
                ->getQuery()
                ->getResult();
        }, false, $cb);

        return $paginator->auto(new Argument(['first' => $first, 'after' => $after]), $total);
    }




    #[GQL\Mutation()]
    public function createNewProduct(ProductCreationInput $input): Product
    {

        $user = $this->security->getUser();
        if (!($user instanceof User)) {
            throw new UserError("Permission Denied: You may not perform this operation");
        }

        $category = $this->getProductCategoryById($input->categoryId);

        $product = new UserProduct();
        $input->build($product);
        $product->setCategory($category);
        $product->setOwner($user);

        $this->entityManager->persist($product);
        $this->entityManager->flush();

        return $product;
    }

    

    #[GQL\Mutation()]
    #[GQL\Arg(
        name: 'id',
        type: 'Ulid!'
    )]
    #[GQL\Arg(
        name: 'input',
        type: 'ProductUpdateInput!'
    )]
    public function updateProduct(Ulid $id, ProductUpdateInput $input): Product
    {
        $product = $this->getProductById($id);
        $category = $this->getProductCategoryById($input->categoryId);

        $input->build($product);
        $product->setCategory($category);

        $this->entityManager->persist($product);
        $this->entityManager->flush();

        return $product;
    }





    private function getUser(): User{
        $user = $this->security->getUser();
        if (!($user instanceof User)) {
            throw new UserError("Permission Denied: You may not perform this operation");
        }
        return $user;
    }


    private function getProductById(Ulid $id): Product{
        $user = $this->getUser();
        $product = $this->productRepository->find($id);
        if ($product === null) {
            throw new UserError(
                message: "Cannot find product with [id:$id]"
            );
        }
        if($product->getOwner() !== $user){
            throw new UserError("Permission Denied: You may not perform this operation");
        }
        return $product;
    }
    

    private function getProductCategoryById(Ulid $id): ProductCategory
    {
        $vType = $this->productCategoryRepository->find($id);
        if (null == $vType) {
            throw new UserError("Could not find Catalog type with [id:{$id}]");
        }
        return $vType;
    }
    
}
