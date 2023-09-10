<?php
namespace App\GraphQL\Catalog\Mutation;

use App\Entity\Catalog\Product;
use App\Entity\Catalog\UserProduct;
use App\GraphQL\Catalog\Input\ProductInput;
use App\Repository\Catalog\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Overblog\GraphQLBundle\Annotation as GQL;

#[GQL\Provider()]
class ProductMutationResolver
{

    public function __construct(
        private ProductRepository $productRepository,
        private EntityManagerInterface $entityManager,
    ){

    }

    #[GQL\Mutation()]
    public function createNewProduct(ProductInput $input): Product{
        $product = new UserProduct();
        // $input->build($product);
        
        $this->entityManager->persist($product);
        $this->entityManager->flush();

        return $product;
    }
}
