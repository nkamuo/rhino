<?php
namespace App\GraphQL\Catalog\Mutation;

use App\Entity\Account\User;
use App\Entity\Catalog\Product;
use App\Entity\Catalog\UserProduct;
use App\GraphQL\Catalog\Input\ProductCreationInput;
use App\Repository\Catalog\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Overblog\GraphQLBundle\Annotation as GQL;
use Overblog\GraphQLBundle\Error\UserError;
use Symfony\Bundle\SecurityBundle\Security;

#[GQL\Provider()]
class ProductMutationResolver
{

    public function __construct(
        private ProductRepository $productRepository,
        private EntityManagerInterface $entityManager,
        private Security $security,
    ){

    }

    #[GQL\Mutation()]
    public function createNewProduct(ProductCreationInput $input): Product{
        
        $user = $this->security->getUser();
        if(!($user instanceof User)){
            throw new UserError("Permission Denied: You may not perform the said operation");
        }

        $product = new UserProduct();
        $input->build($product);
        $product->setOwner($user);

        $this->entityManager->persist($product);
        $this->entityManager->flush();

        return $product;
    }
}
