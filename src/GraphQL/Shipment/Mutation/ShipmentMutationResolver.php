<?php
namespace App\GraphQL\Shipment\Mutation;

use App\Entity\Account\User;
use App\Entity\Addressing\UserAddress;
use App\Entity\Catalog\UserProduct;
use App\Entity\Shipment\Shipment;
use App\Entity\Shipment\ShipmentItem;
use App\GraphQL\Shipment\Input\ShipmentCreationInput;
use App\GraphQL\Shipment\Input\ShipmentItemInput;
use App\Repository\Addressing\UserAddressRepository;
use App\Repository\Catalog\UserProductRepository;
use App\Repository\Shipment\ShipmentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Overblog\GraphQLBundle\Annotation as GQL;
use Overblog\GraphQLBundle\Error\UserError;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Uid\Ulid;

#[GQL\Provider()]
class ShipmentMutationResolver
{
    

    public function __construct(
        private Security $security,
        private EntityManagerInterface $entityManager,
        private ShipmentRepository $shipmentRepository,
        private UserAddressRepository $userAddressRepository,
        private UserProductRepository $userProductRepository,
    ){

    }

    #[GQL\Mutation()]
    public function createNewShipment(ShipmentCreationInput $input): Shipment{
        $user = $this->security->getUser();
        if(!($user instanceof User)){
            throw new UserError("Permission Denied: You may not perform this operation");
        }


        $billingAddress =  $this->getUserAddress($input->billingAddressId,$user);
        $originAddress = $this->getUserAddress($input->originAddressId,$user);
        $destinationAddress = $this->getUserAddress($input->destinationAddressId,$user);

        $shipment = new Shipment();
        
        $shipment
            ->setType($input->type)
            ->setBillingAddress($billingAddress)
            ->setOriginAddress($originAddress)
            ->setDestinationAddress($destinationAddress)
            ->setOwner($user)
            ;

            foreach($input->items as $iInput){
                $item = $this->buildShipmentItem($iInput,$user);
                $shipment->addItem($item);
            }


            $this->entityManager->persist($shipment);
            $this->entityManager->flush();

        return $shipment;
    }


    private function buildShipmentItem(ShipmentItemInput $input, User $user): ShipmentItem{
        $item = new ShipmentItem();
        $product = $this->getUserProduct($input->productId, $user);
        $item
            ->setProduct($product)
            ->setQuantity($input->quantity)
            ->setDescription($input->description)
            ;
        return $item;
    }


    private function getUserProduct(Ulid $id, User $user): UserProduct{
        $address = $this->userProductRepository->find($id);
        if(null == $address || $address->getOwner() != $user){
            throw new UserError("Could not find your product with [id:{$id}]");
        }
        return $address;
    }

    private function getUserAddress(Ulid $id, User $user): UserAddress{
        $address = $this->userAddressRepository->find($id);
        if(null == $address || $address->getOwner() != $user){
            throw new UserError("Could not find your address with [id:{$id}]");
        }
        return $address;
    }
}
